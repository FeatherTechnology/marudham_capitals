<?php
include("../../ajaxconfig.php");
include("./promotionListClass.php");

$type = $_POST['type'];
$sno = 1;
$arr = array();
$Obj = new promotionListClass($connect);
$sub_area_list = $Obj->sub_area_list;

$column = array(
     'cp.id',                  
    'cp.cus_id',              
    'cp.cus_name',            
    'al.area_name',           
    'sl.sub_area_name',       
    'bc.branch_name',         
    'cp.area_group',                   
    'cp.area_line',           
    'cp.mobile1',
    'cp.id',
    'cp.id',
    'cp.id',
    'cp.id',
    'cp.id',
    'cp.id'
);

$search = '';
if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = " and (cp.cus_id LIKE '%" . $_POST['search'] . "%' or cp.cus_name LIKE '%" . $_POST['search'] . "%' or cp.mobile1 LIKE '%" . $_POST['search'] . "%' or al.area_name LIKE '%" . $_POST['search'] . "%'or sl.sub_area_name LIKE '%" . $_POST['search'] . "%') ";
}

$order = '';
if (isset($_POST['order'])) {
    $order = ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

$limit = '';
if ($_POST['length'] != -1) {
    $limit = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


if ($type == 'existing') {
    //only closed customers who dont have any loans in current.
    // Simplified main query to fetch closed customers without loans
    $sql = $connect->query("SELECT cs.cus_id, cs.consider_level, cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id LEFT JOIN ( SELECT cus_id,req_id FROM request_creation WHERE (cus_status NOT BETWEEN 4 AND 9) AND cus_status < 20 ORDER BY req_id DESC ) rc ON cs.cus_id = rc.cus_id  LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id LEFT JOIN sub_area_list_creation sl ON cp.area_confirm_subarea = sl.sub_area_id LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id,agm.sub_area_id) LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sl.sub_area_id,alm.sub_area_id) LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id WHERE cs.cus_sts >= '20' AND cp.area_confirm_subarea IN  ($sub_area_list)  AND cs.closed_sts = 1  AND rc.cus_id IS NULL $search GROUP BY cp.cus_id $order  $limit ");

    // Count query for filtering (use the same logic but without limit)
    $num_qry = $connect->query("SELECT cs.cus_id, cs.consider_level, cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id LEFT JOIN ( SELECT cus_id,req_id FROM request_creation WHERE (cus_status NOT BETWEEN 4 AND 9) AND cus_status < 20 ORDER BY req_id DESC ) rc ON cs.cus_id = rc.cus_id LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id LEFT JOIN sub_area_list_creation sl ON cp.area_confirm_subarea = sl.sub_area_id LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id,agm.sub_area_id) LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sl.sub_area_id,alm.sub_area_id) LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id WHERE cs.cus_sts >= '20' AND cp.area_confirm_subarea IN  ($sub_area_list)  AND cs.closed_sts = 1  AND rc.cus_id IS NULL $search GROUP BY cp.cus_id $order ");

    $number_filter_row = $num_qry->rowCount();
    $arr = [];
    while ($row = $sql->fetch()) {
        $last_closed_date = date('Y-m-d', strtotime($row['updated_date']));
        $arr[] = array(
            'cus_id' => $row['cus_id'],
            'sub_status' => $row['consider_level'],
            'last_updated_date' => $last_closed_date
        );
    }
} else {

    // Main query to fetch customers with specific status and filter those without recent loan requests
    $sql = $connect->query("SELECT req.cus_id, req.cus_status, req.updated_date 
        FROM request_creation req
        LEFT JOIN customer_profile cp ON req.req_id = cp.req_id
        LEFT JOIN (
            SELECT cus_id 
            FROM request_creation 
            WHERE (cus_status NOT BETWEEN 4 AND 9) AND cus_status < 20
            ORDER BY req_id DESC
        ) rc ON req.cus_id = rc.cus_id
        WHERE req.cus_status BETWEEN 4 AND 9  AND (req.sub_area IN ($sub_area_list) OR cp.area_confirm_subarea IN ($sub_area_list)) AND rc.cus_id IS NULL $search GROUP BY req.cus_id $order $limit ");

    // Count query for filtered rows
    $num_qry = $connect->query("SELECT req.cus_id, req.cus_status, req.updated_date 
        FROM request_creation req
        LEFT JOIN customer_profile cp ON req.req_id = cp.req_id
        LEFT JOIN (
            SELECT cus_id 
            FROM request_creation 
            WHERE (cus_status NOT BETWEEN 4 AND 9) AND cus_status < 20
            ORDER BY req_id DESC
        ) rc ON req.cus_id = rc.cus_id
        WHERE req.cus_status BETWEEN 4 AND 9 AND (req.sub_area IN ($sub_area_list) OR cp.area_confirm_subarea IN ($sub_area_list)) AND rc.cus_id IS NULL $search GROUP BY req.cus_id  $order");

    $number_filter_row = $num_qry->rowCount();

    // Process results
    $arr = [];
    while ($row = $sql->fetch()) {
        $last_updated_date = date('Y-m-d', strtotime($row['updated_date']));
        $arr[] = array(
            'cus_id' => $row['cus_id'],
            'sub_status' => $row['cus_status'],
            'last_updated_date' => $last_updated_date
        );
    }
}


if ($type == 'existing') {

    $orgin_table_id = 'existing';

    $sub_status = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
} else {
    $orgin_table_id = 'repromotion';

    $status = [4 => 'Request', 5 => 'Verification', 6 => 'Approval', 7 => 'Acknowledgement', 8 => 'Request', 9 => 'Verification'];

    $sub_status = [4 => 'Cancel', 5 => 'Cancel', 6 => 'Cancel', 7 => 'Cancel', 8 => 'Revoke', 9 => 'Revoke'];
}
$data = array();
foreach ($arr as $val) {
    $sub_array = array();
    $sql = $connect->query("SELECT cp.req_id,cp.cus_id,cp.cus_name,cp.area_group,cp.area_line,cp.mobile1,al.area_name,sl.sub_area_name,bc.branch_name from customer_profile cp LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id LEFT JOIN sub_area_list_creation sl ON cp.area_confirm_subarea = sl.sub_area_id LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id,agm.sub_area_id) LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id WHERE cp.cus_id = " . $val['cus_id'] . " ORDER BY cp.id DESC LIMIT 1");
    if ($sql->rowCount() == '0') {
        $sql = $connect->query("SELECT cp.req_ref_id AS req_id,cp.cus_id,cp.customer_name AS cus_name,cp.area_group,cp.area_line,cp.mobile1,al.area_name,sl.sub_area_name,bc.branch_name,agm.group_name,alm.line_name from customer_register cp LEFT JOIN area_list_creation al ON cp.area = al.area_id  LEFT JOIN sub_area_list_creation sl ON cp.sub_area = sl.sub_area_id  LEFT JOIN area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id,agm.sub_area_id) LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sl.sub_area_id,alm.sub_area_id) LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id WHERE cp.cus_id = " . $val['cus_id'] . " ORDER BY cp.cus_reg_id DESC LIMIT 1");
    }

    $row = $sql->fetch();

    $sub_array[] = $sno;
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'] ?? $row['customer_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['area_group'] ?? $row['group_name'];
    $sub_array[] = $row['area_line'] ?? $row['line_name'];
    $sub_array[] = $row['mobile1'];

    if ($type == 'existing') {
        $sub_array[] = 'Consider';
        $sub_array[] = $sub_status[$val['sub_status']]; //fetched from closed status table above mentioned    

        $qry = $connect->query("SELECT created_date FROM closed_status WHERE cus_id = '" . $row['cus_id'] . "' ORDER BY id DESC limit 1");
        //take last closed date of this customer to show when this customer added to promotion list
        if ($qry->rowCount() > 0) {
            $ldate = $qry->fetch()['created_date'];
            $sub_array[] = date('d-m-Y', strtotime($ldate));
        } else {
            $sub_array[] = '';
        }
    } else {
        $sub_array[] = $status[$val['sub_status']];
        $sub_array[] = $sub_status[$val['sub_status']]; //fetched from request table above mentioned     

        if ($type != 'existing') {
            $qry = $connect->query("SELECT prompt_remark FROM request_creation WHERE cus_id = '" . $row['cus_id'] . "' and prompt_remark != '' ORDER BY updated_date DESC limit 1");
            if ($qry->rowCount() > 0) {
                $sub_array[] = $qry->fetch()['prompt_remark'];
            } else {
                $sub_array[] = '';
            }
        }

        $sub_array[] = date('d-m-Y', strtotime($val['last_updated_date']));
    }
    $action = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='promo-chart' data-id='" . $row['cus_id'] . "' data-toggle='modal' data-target='#promoChartModal'><span>Promotion Chart</span></a><a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='" . $row['cus_id'] . "'><span>Personal Info</span></a>";
    if ($type == 'existing') {
        $action .= "<a class='cust-profile' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Customer Profile</span></a><a class='loan-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Loan History</span></a><a class='doc-history' data-reqid='" . $row['req_id'] . "' data-cusid='" . $row['cus_id'] . "'><span>Document History</span></a>";
    }
    $action .= "</div></div>";
    $sub_array[] = $action;

    //for intrest or not intrest choice to make
    $action = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> <a class='intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Interested</span></a><a class='not-intrest' data-toggle='modal' data-target='#addPromotion' data-id='" . $row['cus_id'] . "'><span>Not Interested</span></a></div></div>";
    $sub_array[] = $action;

    $qry = $connect->query("SELECT follow_date FROM new_promotion WHERE cus_id = '" . $row['cus_id'] . "' ORDER BY created_date DESC limit 1");
    //take last promotion follow up date inserted from new promotion table
    if ($qry->rowCount() > 0) {
        $fdate = $qry->fetch()['follow_date'];
        $sub_array[] = date('d-m-Y', strtotime($fdate));
    } else {
        $sub_array[] = '';
    }


    $data[] = $sub_array;
    $sno++;
}

function count_all_data($connect, $type)
{
    if ($type == 'existing') {
        $query = "SELECT cs.cus_id,cs.consider_level,cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id WHERE cs.cus_sts >= '20' and cs.closed_sts = 1";
    } else {
        $query = "SELECT req.cus_id, req.cus_status, req.updated_date FROM request_creation req LEFT JOIN customer_profile cp ON req.req_id = cp.req_id WHERE req.cus_status BETWEEN 4 AND 9 GROUP BY req.cus_id";
    }
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect, $type),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;