<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = "";
if ($userid != 1) {

    $userQry = $connect->query("SELECT group_id, report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
        $group_id = $rowuser['group_id'];
        $report_access = $rowuser['report_access'];
    
    if($report_access =='1'){
        $group_id = explode(',', $group_id);
        $sub_area_list = array();
        foreach ($group_id as $group) {
            $groupQry = $connect->query("SELECT sub_area_id FROM area_group_mapping WHERE map_id = $group ");
            $row_sub = $groupQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);

        $user_based = "AND (cp.area_confirm_subarea IN ($sub_area_list) OR req.sub_area IN ($sub_area_list)) AND req.update_login_id = '$userid' ";
    }
}

$where = "1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "(date(req.dor) >= '" . $from_date . "') and (date(req.dor) <= '" . $to_date . "') ";
}

$where  .= $user_based;

$cus_status = "";
$join_condition = "";
$ag_join = "req.agent_id";
$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];
// Check if type and sel_screen are selected by the user
if (isset($_POST['type']) && isset($_POST['sel_screen'])) {
    $type = $_POST['type'];
    $sel_screen = $_POST['sel_screen'];

    // Determine cus_status based on type and sel_screen
    if ($type == '1') { // '1' for Cancel
        switch ($sel_screen) {
            case '1':
                $cus_status = '4'; // Request
                break;
            case '2':
                $cus_status = '5'; // Verification
                break;
            case '3':
                $cus_status = '6'; // Approval
                break;
            case '4':
                $cus_status = '7'; // Acknowledgement
                break;
        }
    } elseif ($type == '2') { // '2' for Revoke
        switch ($sel_screen) {
            case '1':
                $cus_status = '8'; // Request
                break;
            case '2':
                $cus_status = '9'; // Verification
                break;
        }
    }
}   // Append the cus_status condition if it's set

if ($cus_status != "") {
    // Updated WHERE clause
    $where .= " AND req.cus_status = '$cus_status' ";

} else {
    $where .= " AND req.cus_status BETWEEN 4 AND 9 ";

}

if($cus_status =='5' || $cus_status =='6' || $cus_status =='7' || $cus_status =='9'){
    $join_condition = "JOIN in_verification iv ON req.req_id = iv.req_id";
    $ag_join = "iv.agent_id";
}

$statusLabels = [
    '0' => "In Request",
    '1' => 'In Verification',
    '2' => 'In Approval',
    '3' => 'In Acknowledgement',
    '4' => 'Cancel - Request',
    '5' => 'Cancel - Verification',
    '6' => 'Cancel - Approval',
    '7' => 'Cancel - Acknowledgement',
    '8' => 'Revoke - Request',
    '9' => 'Revoke - Verification',
    '10' => 'In Verification',
    '11' => 'In Verification',
    '12' => 'In Verification',
    '13' => 'In Issue',
    '14' => 'Collection',
    '15' => 'Collection Error',
    '16' => 'Collection Legal',
    '17' => 'Collection',
    '20' => 'Closed',
    '21' => 'NOC',
];

$column = array(
    'req.req_id',
    'req.req_code',
    'req.dor',
    'req.cus_id',
    'req.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'lcc.loan_category_creation_name',
    'req.sub_category',
    'req.loan_amt',
    'u.role',
    'u.fullname',
    'req.req_id',
    'req.responsible',
    'req.cus_data',
    'req.req_id',
    'req.prompt_remark'
);
$query = "SELECT 
    req.*,
    al.area_name,
    sal.sub_area_name,
    lcc.loan_category_creation_name,
    ag.ag_name,
    u.role,
    u.fullname
FROM 
    request_creation req 
$join_condition
JOIN 
    area_list_creation al ON req.area = al.area_id
JOIN 
    sub_area_list_creation sal ON req.sub_area = sal.sub_area_id
JOIN 
    loan_category_creation lcc ON req.loan_category = lcc.loan_category_creation_id
LEFT JOIN 
    agent_creation ag ON $ag_join = ag.ag_id         
JOIN 
    user u ON req.update_login_id = u.user_id
LEFT JOIN 
    customer_profile cp ON req.req_id = cp.req_id
WHERE 
    $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " and (req.cus_id LIKE '%" . $_POST['search'] . "%' OR
                req.cus_name LIKE '%" . $_POST['search'] . "%' OR
                al.area_name LIKE '%" . $_POST['search'] . "%' OR
                sal.sub_area_name LIKE '%" . $_POST['search'] . "%' OR
                u.role LIKE '%" . $_POST['search'] . "%' OR
                u.fullname LIKE '%" . $_POST['search'] . "%' OR
                lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%' OR
                req.sub_category LIKE '%" . $_POST['search'] . "%' OR
                ag.ag_name LIKE '%" . $_POST['search'] . "%' OR
                req.cus_data LIKE '%" . $_POST['search'] . "%' ) ";
    }
}


if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;
    $sub_array[] = $row['req_code'];
    $sub_array[] = date('d-m-Y', strtotime($row['dor']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_category_creation_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = $role_arr[$row['role']];
    $sub_array[] = $row['fullname'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = ($row['responsible'] == '0') ? 'Yes' : 'No';
    $sub_array[] = $row['cus_data'];
    $sub_array[] = $statusLabels[$row['cus_status']];
    $sub_array[] = $row['prompt_remark'];

    $data[]      = $sub_array;
    $sno = $sno + 1;
}
$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function count_all_data($connect)
{
    $query     = "SELECT req_id FROM request_creation ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}
function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

// Close the database connection
$connect = null;
