<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $sql = $connect->query("SELECT ag_id FROM user where user_id = '$userid'");
    $login_user_type = $sql->fetch()['ag_id'];
    if ($login_user_type == null or $login_user_type == '') {
        $login_user_type = 0;
    }
}
if (isset($_SESSION["request_list_access"])) {
    $request_list_access = $_SESSION["request_list_access"]; //if request_list_access is granted to the current user, they can access Whole request list
}

$column = array(
    'rc.req_id',
    'rc.dor',
    'rc.cus_id',
    'rc.cus_name',
    'bc.branch_name',
    'ag.group_name',
    'alm.line_name',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'rc.sub_category',
    'rc.loan_amt',
    'rc.user_type',
    'rc.user_name',
    'rc.agent_id',
    'rc.responsible',
    'rc.cus_data',
    'rc.cus_status',
    'rc.status'
);

$query = "SELECT rc.*, a.area_name, sa.sub_area_name, ag.group_name, bc.branch_name, alm.line_name,lcc.loan_category_creation_name
    FROM request_creation rc
    JOIN area_list_creation a ON rc.area = a.area_id
    JOIN sub_area_list_creation sa ON rc.sub_area = sa.sub_area_id
    JOIN area_group_mapping ag ON FIND_IN_SET(sa.sub_area_id, ag.sub_area_id)
    JOIN branch_creation bc ON ag.branch_id = bc.branch_id
    JOIN area_line_mapping alm ON FIND_IN_SET(sa.sub_area_id, alm.sub_area_id)
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = rc.loan_category
    WHERE rc.status = 0 
    AND (rc.cus_status NOT IN (4, 5, 6, 7, 8, 9) AND rc.cus_status < 14) 
    AND rc.insert_login_id = '$userid' "; //hide if issued or revoked(after issued cus_status = 7 , request revoked = 8, verification revoked = 9)
if ($userid == 1 or $request_list_access == 0) { //if request_list_access is granted to the current user
    $query = "SELECT rc.*, a.area_name, sa.sub_area_name, ag.group_name, bc.branch_name, alm.line_name,lcc.loan_category_creation_name
    FROM request_creation rc
    JOIN area_list_creation a ON rc.area = a.area_id
    JOIN sub_area_list_creation sa ON rc.sub_area = sa.sub_area_id
    JOIN area_group_mapping ag ON FIND_IN_SET(sa.sub_area_id, ag.sub_area_id)
    JOIN branch_creation bc ON ag.branch_id = bc.branch_id
    JOIN area_line_mapping alm ON FIND_IN_SET(sa.sub_area_id, alm.sub_area_id)
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = rc.loan_category
    WHERE rc.status = 0 
    AND (rc.cus_status NOT IN (4, 5, 6, 7, 8, 9) AND rc.cus_status < 14) ";
}
if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "AND ( rc.dor LIKE '%" . $_POST['search'] . "%'
            OR rc.cus_id LIKE '%" . $_POST['search'] . "%'
            OR rc.cus_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR ag.group_name LIKE '%" . $_POST['search'] . "%'
            OR alm.line_name LIKE '%" . $_POST['search'] . "%'
            OR a.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
            OR rc.sub_category LIKE '%" . $_POST['search'] . "%'
            OR rc.loan_amt LIKE '%" . $_POST['search'] . "%'
            OR rc.user_type LIKE '%" . $_POST['search'] . "%'
            OR rc.responsible LIKE '%" . $_POST['search'] . "%'
            OR rc.cus_data LIKE '%" . $_POST['search'] . "%')  ";
    // OR rc.user_name LIKE '%" . $_POST['search'] . "%' 
}
// print_r($query);die;
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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

    $sub_array[] = date('d-m-Y', strtotime($row['dor']));

    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];

    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["loan_category_creation_name"];
    $sub_array[] = $row['sub_category'];

    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = $row['user_type'];
    $sub_array[] = $row['user_name'];

    $ag_id = $row['agent_id'];
    if ($ag_id != '') {

        $qry = $connect->query("SELECT * FROM agent_creation where ag_id = $ag_id ");
        $row1 = $qry->fetch();
        $sub_array[] = $row1['ag_name'];
    } else {
        $sub_array[] = '';
    }

    if ($row['responsible'] == '0') {
        $sub_array[] = 'Yes';
    }else if (!empty($ag_id) && $row['responsible'] != '0') {
        $sub_array[] = 'No';
    } else {
        $sub_array[] = '';
    }

    $sub_array[] = $row['cus_data'];
    $id = $row['req_id'];
    $cus_id = $row['cus_id'];

    $cus_status = $row['cus_status'];
    $status_messages = [
        '0' => "<button class='btn btn-outline-secondary sub_verification' value='$id' data-value='$cus_id'><span class='icon-arrow_forward'></span></button>",
        '1' => 'In Verification',
        '10' => 'In Verification',
        '11' => 'In Verification',
        '12' => 'In Verification',
        '2' => 'In Approval',
        '3' => 'In Acknowledgement',
        '4' => 'Cancel - Request',
        '5' => 'Cancel - Verification',
        '6' => 'Cancel - Approval',
        '7' => 'Cancel - Acknowledgement',
        '13' => 'In Issue',
        '14' => 'Issued'
    ];

    $sub_array[] = isset($status_messages[$cus_status]) ? $status_messages[$cus_status] : 'Unknown Status';


    $id = $row['req_id'];
    $user_type = $row['user_type'];
    $cus_id = $row['cus_id'];

    $action = "<div class='dropdown' >
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";
    if ($cus_status == '0') {
        $action .= "<a href='request&upd=$id'>Edit Request</a>
        <a href='#' data-reqId = '$id' class='cancelrequest'>Cancel Request</a>
        <a href='#' data-reqId = '$id' class='revokerequest'>Revoke Request</a>";
    }
    if ($cus_status == '4' or $cus_status == '5' or $cus_status == '6') {
        $action .= "<a href='request&del=$id' class='removerequest'>Remove Request</a>";
    }
    if ($login_user_type == 0 or $userid == 1) {
        $action .= "<a href='' data-value ='" . $cus_id . "' data-value1 = '$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'>Customer Status</a>";
    }

    $action .= "</div></div>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

//Format number in Indian Format
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

function count_all_data($connect)
{
    $query     = "SELECT * FROM request_creation";
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