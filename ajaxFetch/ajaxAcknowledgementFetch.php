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
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
    }
    $group_id = explode(',', $group_id);
    $sub_area_list = array();
    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT * FROM area_group_mapping where map_id = $group ");
        $row_sub = $groupQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',', $sub_area_ids);
}

$column = array(
    'v.req_id',
    'v.dor',
    'v.cus_id',
    'v.cus_name',
    'bc.branch_name',
    'ag.group_name',
    'alm.line_name',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'v.sub_category',
    'v.loan_amt',
    'v.user_type',
    'v.user_name',
    'v.agent_id',
    'v.responsible',
    'v.cus_data',
    'v.cus_status',
    'v.status'
);

if ($userid == 1) {
    $query = 'SELECT v.*,a.area_name, sa.sub_area_name, ag.group_name, bc.branch_name, alm.line_name,lcc.loan_category_creation_name 
    FROM in_verification v
    JOIN area_list_creation a ON v.area = a.area_id
    JOIN sub_area_list_creation sa ON v.sub_area = sa.sub_area_id
    JOIN area_group_mapping ag ON FIND_IN_SET(sa.sub_area_id, ag.sub_area_id)
    JOIN branch_creation bc ON ag.branch_id = bc.branch_id
    JOIN area_line_mapping alm ON FIND_IN_SET(sa.sub_area_id, alm.sub_area_id)
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = v.loan_category
    WHERE v.status = 0 and v.cus_status IN (3,13) ';
} else {
    $query = "SELECT v.*,a.area_name, sa.sub_area_name, ag.group_name, bc.branch_name, alm.line_name,lcc.loan_category_creation_name 
    FROM in_verification v
    JOIN area_list_creation a ON v.area = a.area_id
    JOIN sub_area_list_creation sa ON v.sub_area = sa.sub_area_id
    JOIN area_group_mapping ag ON FIND_IN_SET(sa.sub_area_id, ag.sub_area_id)
    JOIN branch_creation bc ON ag.branch_id = bc.branch_id
    JOIN area_line_mapping alm ON FIND_IN_SET(sa.sub_area_id, alm.sub_area_id)
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = v.loan_category
    WHERE v.status = 0 and v.cus_status IN (3,13) and v.sub_area IN ($sub_area_list) "; //show only Approved Verification in Acknowledgement. // 13 Move to Issue. 
}

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " AND (v.dor LIKE '%" . $_POST['search'] . "%'
            OR v.cus_id LIKE '%" . $_POST['search'] . "%'
            OR v.cus_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR ag.group_name LIKE '%" . $_POST['search'] . "%'
            OR alm.line_name LIKE '%" . $_POST['search'] . "%'
            OR a.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
            OR v.sub_category LIKE '%" . $_POST['search'] . "%'
            OR v.loan_amt LIKE '%" . $_POST['search'] . "%'
            OR v.user_type LIKE '%" . $_POST['search'] . "%'
            OR v.responsible LIKE '%" . $_POST['search'] . "%'
            OR v.cus_data LIKE '%" . $_POST['search'] . "%' ) ";
    }
}
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
    if ($cus_status == '3') {
        $cus_doc = $connect->query("SELECT submitted FROM `acknowlegement_documentation` WHERE `req_id` ='$id'");
        $cus_doc_row =  $cus_doc->fetch();

        if (isset($cus_doc_row['submitted']) && $cus_doc_row['submitted'] == '1') {
            $sub_array[] = "<button class='btn btn-outline-secondary move_issue' value='$id' data-cusid='$cus_id'><span class = 'icon-arrow_forward'></span></button>";
        } else {

            $sub_array[] = 'In Acknowledgement';
        }
    }
    if ($cus_status == '7') {
        $sub_array[] = 'Cancel - Acknowledgement';
    }
    if ($cus_status == '13') {
        $sub_array[] = 'In Issue';
    }
    if ($cus_status == '14') {
        $sub_array[] = 'Issued';
    }

    $id          = $row['req_id'];
    $user_type = $row['user_type'];
    $cus_id = $row['cus_id'];

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";
    if ($cus_status == '3') {
        $action .= "<a href='acknowledgement_creation&upd=$id&pge=1' class='customer_profile' value='$id' > Edit Acknowledgement </a>";
        $action .= "<a href='#' data-reqid = '$id' class='ack-cancel' value='$id' > Cancel </a>";
    } else if ($cus_status == '7') {
        $action .= "<a href='acknowledgement_creation&rem=$id&pge=1' class='ack-remove' value='$id' > Remove </a>";
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
    $query     = "SELECT * FROM in_verification";
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