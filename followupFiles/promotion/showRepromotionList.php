<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$follow_up_sts = '';
$follow_up_date = '';

$sno = 1;
$Obj = new promotionListClass($connect);
$sub_area_list = $Obj->sub_area_list;

$column = array(
    'cp.cus_reg_id',                  
    'cp.cus_id',              
    'cp.customer_name',            
    'al.area_name',           
    'sl.sub_area_name',       
    'bc.branch_name',         
    'agm.group_name',                   
    'alm.line_name',           
    'cp.mobile1',
    'cp.cus_reg_id',
    'req.cus_status',
    'req.updated_date',
    'cp.cus_reg_id',
    'cp.cus_reg_id',
    'np.status',
    'np.follow_date'
);

$search = '';
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = " and (cp.cus_id LIKE '%" . $_POST['search'] . "%' or cp.customer_name LIKE '%" . $_POST['search'] . "%' or al.area_name LIKE '%" . $_POST['search'] . "%' or sl.sub_area_name LIKE '%" . $_POST['search'] . "%' or bc.branch_name LIKE '%" . $_POST['search'] . "%' or agm.group_name LIKE '%" . $_POST['search'] . "%' or alm.line_name LIKE '%" . $_POST['search'] . "%' or cp.mobile1 LIKE '%" . $_POST['search'] . "%' or np.status LIKE '%" . $_POST['search'] . "%' ) ";
}

$order = '';
if (isset($_POST['order'])) {
    $order = ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

    $qry = "SELECT req.req_id, req.cus_data, req.cus_id, cp.customer_name, al.area_name, sl.sub_area_name, bc.branch_name, agm.group_name, alm.line_name, cp.mobile1, req.cus_status AS consider_level, req.updated_date, np.status AS followup_sts, np.follow_date 
    FROM request_creation req 
    LEFT JOIN customer_register cp ON req.cus_id = cp.cus_id 
    LEFT JOIN (
        SELECT DISTINCT cus_id 
        FROM request_creation 
        WHERE cus_status NOT BETWEEN 4 AND 9 
        AND cus_status < 20 
    ) rc ON req.cus_id = rc.cus_id 
    LEFT JOIN area_list_creation al ON al.area_id = CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_area ELSE cp.area END
    LEFT JOIN sub_area_list_creation sl ON sl.sub_area_id = CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_subarea ELSE cp.sub_area END
    LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id, agm.sub_area_id) 
    LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sl.sub_area_id, alm.sub_area_id) 
    LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id 
    LEFT JOIN ( SELECT cus_id, MAX(follow_date) AS follow_date, status FROM new_promotion GROUP BY cus_id ) np ON req.cus_id = np.cus_id
    WHERE req.cus_status BETWEEN 4 AND 9 
    AND CASE WHEN req.cus_status IN (6, 7) THEN cp.area_confirm_subarea ELSE cp.sub_area END IN  ($sub_area_list) AND rc.cus_id IS NULL ";

    if($_POST['followUpSts']){
        $follow_up_sts = $_POST['followUpSts'];
        $qry_sts = ($follow_up_sts =='tofollow') ? "AND np.status IS NULL " : "AND TRIM(REPLACE(np.status,' ','')) = '$follow_up_sts' ";

        $qry .= $qry_sts;
    }

    if($_POST['dateType']){
        $date_type = $_POST['dateType'];//1=Closed date, 2=Followup date.
        $qry_date = ($date_type == '1') ? "AND req.updated_date BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' " : "AND np.follow_date BETWEEN '".$_POST['followUpFromDate']."' AND '".$_POST['followUpToDate']."' ";

        $qry .= $qry_date;
    }     
    
        $qry .= "$search GROUP BY req.cus_id $order ";

        // Count query for filtered rows
        $num_qry = $connect->query($qry);
        $number_filter_row = $num_qry->rowCount();

        
    $limit = '';
    if ($_POST['length'] != -1) {
        $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    // Main query to fetch customers with specific status and filter those without recent loan requests
    $sql = $connect->query($qry . $limit);

    $status = [4 => 'Request', 5 => 'Verification', 6 => 'Approval', 7 => 'Acknowledgement', 8 => 'Request', 9 => 'Verification'];

    $sub_status = [4 => 'Cancel', 5 => 'Cancel', 6 => 'Cancel', 7 => 'Cancel', 8 => 'Revoke', 9 => 'Revoke'];

$data = array();
while ($row = $sql->fetch()) {
    $sub_array = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['customer_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $status[$row['consider_level']];
    $sub_array[] = $sub_status[$row['consider_level']]; //fetched from request table above mentioned 
    $sub_array[] = $row['cus_data'];

    $sub_array[] = (isset($row['updated_date'])) ? date('d-m-Y', strtotime($row['updated_date'])) : '';

    $sub_array[] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a></div></div>";

    //for intrest or not intrest choice to make
    $sub_array[] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a><a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a></div></div>";

    $sub_array[] = $row['followup_sts'];
    $sub_array[] = (isset($row['follow_date'])) ? date('d-m-Y', strtotime($row['follow_date'])) : '';

    $data[] = $sub_array;
    $sno++;
}

function count_all_data($connect)
{
    $query = "SELECT req.cus_id FROM request_creation req LEFT JOIN customer_profile cp ON req.req_id = cp.req_id WHERE req.cus_status BETWEEN 4 AND 9 GROUP BY req.cus_id";
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