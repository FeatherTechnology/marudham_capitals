<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$follow_up_sts = '';
$follow_up_date = '';

$sno = 1;
$Obj = new promotionListClass($connect);
$sub_area_list = $Obj->sub_area_list;
$accessType = $Obj->accessType;
$column = array(
    'cr.cus_reg_id',                  
    'cr.cus_id',              
    'cr.autogen_cus_id',              
    'cr.customer_name',            
    'al.area_name',           
    'sl.sub_area_name',       
    'bc.branch_name',         
    'agm.group_name',                   
    'alm.line_name',           
    'cr.mobile1',
    'cr.cus_reg_id',
    'cs.consider_level',
    'cs.created_date',
    'cr.cus_reg_id',
    'cr.cus_reg_id',
    'np.status',
    'np.follow_date'
);

$search = '';
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = " and (cr.cus_id LIKE '%" . $_POST['search'] . "%' OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' OR cr.customer_name LIKE '%" . $_POST['search'] . "%' OR al.area_name LIKE '%" . $_POST['search'] . "%'OR sl.sub_area_name LIKE '%" . $_POST['search'] . "%' OR bc.branch_name LIKE '%" . $_POST['search'] . "%' OR agm.group_name LIKE '%" . $_POST['search'] . "%' OR alm.line_name LIKE '%" . $_POST['search'] . "%' OR cr.mobile1 LIKE '%" . $_POST['search'] . "%'  OR np.status LIKE '%" . $_POST['search'] . "%' ) ";
}
if (isset($_POST['re_active']) && $_POST['re_active'] != "") {
    $re_active = "HAVING CURDATE() >= DATE_ADD(DATE_ADD(LAST_DAY(MAX(created_date)), INTERVAL 1 DAY),INTERVAL 3 MONTH)";
}
else{
    $re_active ="HAVING CURDATE() < DATE_ADD( DATE_ADD(LAST_DAY(MAX(created_date)), INTERVAL 1 DAY),INTERVAL 3 MONTH)";
}

$order = '';
if (isset($_POST['order'])) {
    $order = ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}
$areaColumn = ($accessType == 3) 
    ? "cr.area_confirm_area" 
    : "cr.area_confirm_subarea";
    //only closed customers who dont have any loans in current.
    // Simplified main query to fetch closed customers without loans
    $qry = "SELECT cr.req_ref_id as req_id, cr.cus_id, cr.autogen_cus_id, cr.customer_name as cus_name, al.area_name, sl.sub_area_name, bc.branch_name, agm.group_name, alm.line_name, cr.mobile1, cs.consider_level, cs.created_date, np.status AS followup_sts, np.follow_date 
        FROM  customer_register cr
        JOIN (
            SELECT req_id, cus_id, consider_level, MAX(created_date) AS created_date 
            FROM closed_status 
            WHERE closed_sts = 1 
            GROUP BY cus_id $re_active
        ) cs ON cs.cus_id = cr.cus_id 
        LEFT JOIN area_list_creation al ON cr.area_confirm_area = al.area_id 
        LEFT JOIN sub_area_list_creation sl ON cr.area_confirm_subarea = sl.sub_area_id 
        LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sl.sub_area_id
        LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id 
        LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sl.sub_area_id
        LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id 
        LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
        LEFT JOIN new_promotion np ON np.cus_id = cs.cus_id AND np.created_date = (SELECT MAX(np1.created_date) FROM new_promotion np1 WHERE np1.cus_id = cs.cus_id)
        WHERE $areaColumn IN ($sub_area_list) AND NOT EXISTS ( SELECT 1 FROM closed_status cs2 WHERE cs2.cus_id = cr.cus_id AND cs2.closed_sts IN (2,3)) AND NOT EXISTS ( SELECT 1 FROM request_creation r WHERE r.cus_id = cs.cus_id AND ((r.cus_status IN (4,5,6,7,8,9)) OR r.cus_status <= 20)) ";

    if($_POST['followUpSts']){
        $follow_up_sts = $_POST['followUpSts'];
        $qry_sts = ($follow_up_sts =='tofollow') ? "AND np.status IS NULL " : "AND TRIM(REPLACE(np.status,' ','')) = '$follow_up_sts' ";

        $qry .= $qry_sts;
    }

    if($_POST['dateType']){
        $date_type = $_POST['dateType'];//1=Closed date, 2=Followup date.
        $qry_date = ($date_type == '1') ? "AND DATE(cs.created_date) BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' " : "AND DATE(np.follow_date) BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' ";

        $qry .= $qry_date;
    }    

    $qry .= "$search GROUP BY cr.cus_id $order ";
    
    // Count query for filtering (use the same logic but without limit)
    $num_qry = $connect->query($qry);
    $number_filter_row = $num_qry->rowCount();
    
    $limit = '';
    if ($_POST['length'] != -1) {
        $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    
    $sql = $connect->query($qry . $limit);

    $sub_status = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];

    $data = array();
    while ($row = $sql->fetch()) {
        $sub_array = array();
        $sub_array[] = $sno;
        $sub_array[] = $row['cus_id'];
        $sub_array[] = $row['autogen_cus_id'];
        $sub_array[] = $row['cus_name'];
        $sub_array[] = $row['area_name'];
        $sub_array[] = $row['sub_area_name'];
        $sub_array[] = $row['branch_name'];
        $sub_array[] = $row['group_name'];
        $sub_array[] = $row['line_name'];
        $sub_array[] = $row['mobile1'];
        $sub_array[] = 'Consider';
        $sub_array[] = $sub_status[$row['consider_level']]; //fetched from closed status table above mentioned    

        //take last closed date of this customer to show when this customer added to promotion list
        $sub_array[] = date('d-m-Y', strtotime($row['created_date']));
    
        $sub_array[] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a><a class='cust-profile' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Profile</span></a><a class='customer-status' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Status</span></a><a class='loan-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan History</span></a><a class='doc-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Document History</span></a></div></div>";

        //for intrest or not intrest choice to make
        $sub_array[] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a><a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a></div></div>";

        $sub_array[] = $row['followup_sts'];
        $sub_array[] = (isset($row['follow_date'])) ? date('d-m-Y', strtotime($row['follow_date'])) : '';

        $data[] = $sub_array;
        $sno++;
    }

function count_all_data($connect)
{
    $query = "SELECT cs.cus_id FROM closed_status cs WHERE cs.closed_sts = 1";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;