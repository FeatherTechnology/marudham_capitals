<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$follow_up_sts = '';
$follow_up_date = '';

$sno = 1;
$Obj = new promotionListClass($connect);
$sub_area_list = $Obj->sub_area_list;

$column = array(
    'cp.id',                  
    'cp.cus_id',              
    'cp.cus_name',            
    'al.area_name',           
    'sl.sub_area_name',       
    'bc.branch_name',         
    'agm.group_name',                   
    'alm.line_name',           
    'cp.mobile1',
    'cp.id',
    'cs.consider_level',
    'cs.created_date',
    'cp.id',
    'cp.id',
    'np.status',
    'np.follow_date'
);

$search = '';
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = " and (cp.cus_id LIKE '%" . $_POST['search'] . "%' or cp.cus_name LIKE '%" . $_POST['search'] . "%' or al.area_name LIKE '%" . $_POST['search'] . "%'or sl.sub_area_name LIKE '%" . $_POST['search'] . "%' or bc.branch_name LIKE '%" . $_POST['search'] . "%' or agm.group_name LIKE '%" . $_POST['search'] . "%' or alm.line_name LIKE '%" . $_POST['search'] . "%' or cp.mobile1 LIKE '%" . $_POST['search'] . "%'  or np.status LIKE '%" . $_POST['search'] . "%' ) ";
}

$order = '';
if (isset($_POST['order'])) {
    $order = ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

    //only closed customers who dont have any loans in current.
    // Simplified main query to fetch closed customers without loans
    $qry = "SELECT cp.req_id, cp.cus_id, cp.cus_name, al.area_name, sl.sub_area_name, bc.branch_name,agm.group_name, alm.line_name, cp.mobile1, cs.consider_level, cs.created_date, np.status AS followup_sts, np.follow_date 
        FROM acknowlegement_customer_profile cp
        JOIN (
            SELECT req_id, cus_id, consider_level, MAX(created_date) AS created_date 
            FROM closed_status 
            WHERE closed_sts = 1 
            GROUP BY cus_id 
        ) cs ON cs.cus_id = cp.cus_id 
        LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id 
        LEFT JOIN sub_area_list_creation sl ON cp.area_confirm_subarea = sl.sub_area_id 
        LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id, agm.sub_area_id) 
        LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sl.sub_area_id, alm.sub_area_id) 
        LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
        LEFT JOIN (
            SELECT cus_id, MAX(follow_date) AS follow_date, status
            FROM new_promotion
            GROUP BY cus_id
        ) np ON cs.cus_id = np.cus_id 
        WHERE cp.area_confirm_subarea IN ($sub_area_list) AND NOT EXISTS ( SELECT 1 FROM closed_status cs2 WHERE cs2.cus_id = cp.cus_id AND cs2.closed_sts IN (2,3)) AND NOT EXISTS ( SELECT 1 FROM request_creation r WHERE r.cus_id = cs.cus_id AND (r.cus_status NOT IN (4,5,6,7,8,9)) AND r.cus_status < 20 ) ";

    if($_POST['followUpSts']){
        $follow_up_sts = $_POST['followUpSts'];
        $qry_sts = ($follow_up_sts =='tofollow') ? "AND np.status IS NULL " : "AND TRIM(REPLACE(np.status,' ','')) = '$follow_up_sts' ";

        $qry .= $qry_sts;
    }

    if($_POST['dateType']){
        $date_type = $_POST['dateType'];//1=Closed date, 2=Followup date.
        $qry_date = ($date_type == '1') ? "AND cs.created_date BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' " : "AND np.follow_date BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' ";

        $qry .= $qry_date;
    }    

    $qry .= "$search GROUP BY cp.cus_id $order ";

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
    
        $sub_array[] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a><a class='cust-profile' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Profile</span></a><a class='loan-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan History</span></a><a class='doc-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Document History</span></a></div></div>";

        //for intrest or not intrest choice to make
        $sub_array[] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a><a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a></div></div>";

        $sub_array[] = $row['followup_sts'];
        $sub_array[] = (isset($row['follow_date'])) ? date('d-m-Y', strtotime($row['follow_date'])) : '';

        $data[] = $sub_array;
        $sno++;
    }

function count_all_data($connect)
{
    $query = "SELECT cs.cus_id FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id WHERE cs.closed_sts = 1";
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