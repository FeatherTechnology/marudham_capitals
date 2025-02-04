<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
    }

    $line_id = explode(',', $line_id);
    $sub_area_list = array();
    foreach ($line_id as $line) {
        $lineQry = $connect->query("SELECT * FROM area_line_mapping where map_id = $line ");
        $row_sub = $lineQry->fetch();
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
    'cp.id',
    'cp.cus_id',
    'cp.cus_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'al.line_name',
    'cp.mobile1',
    'cp.id'
);

if ($userid == 1) {
    $query = 'SELECT cp.cus_id as cp_cus_id,cp.cus_name,ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id 
    FROM acknowlegement_customer_profile cp 
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    where ii.status = 0 and ii.cus_status = 20 GROUP BY ii.cus_id '; // Only Issued and all lines not relying on sub area
} else {
    $query = "SELECT cp.cus_id as cp_cus_id,cp.cus_name,ac.area_name, sa.sub_area_name, al.line_name, bc.branch_name,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id 
    FROM acknowlegement_customer_profile cp 
    JOIN in_issue ii ON cp.cus_id = ii.cus_id
    JOIN area_list_creation ac ON cp.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cp.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping al ON FIND_IN_SET(sa.sub_area_id, al.sub_area_id)
    JOIN branch_creation bc ON al.branch_id = bc.branch_id
    where ii.status = 0 and ii.cus_status = 20 and cp.area_confirm_subarea IN ($sub_area_list) GROUP BY ii.cus_id "; //show only issued customers within the same lines of user. 
}
// echo $query;

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= " AND(cp.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR al.line_name LIKE '%" . $_POST['search'] . "%'
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%') ";
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

    $sub_array[] = $row['cp_cus_id'];
    $sub_array[] = $row['cus_name'];

    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];

    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cp_cus_id'];
    $id          = $row['req_id'];
    // When in_issue and closed status count is equal then move to noc button will be shown. //if multiple request completed the collection means then complete closed for one time only so we check whether the request closed submit or not.. Move to Noc button wil not be show until all closed status submit.
    $ii_cus_id          = $row['ii_cus_id'];

    $ii_count = $connect->query("SELECT id FROM `in_issue` WHERE `cus_status` = '20' && `cus_id`='" . $ii_cus_id . "' ");
    $ii_cnt = $ii_count->rowCount();
    $closed_sts_count = $connect->query("SELECT id FROM `closed_status` WHERE `cus_sts` ='20' && `cus_id`='" . $ii_cus_id . "'");
    $close_cnt = $closed_sts_count->rowCount();

    // if($ii_cnt == $close_cnt){// if all request present in closed loan list are closed, then only it will allow to move that customer to closed
    if ($close_cnt > 0) { //if any one of the request got closed then that can be moved to noc straight
        $action = "<button class='btn btn-outline-secondary Move_to_noc' data-value='$ii_cus_id' data-id='$id'><span class = 'icon-arrow_forward'></span></button>";
    } else {
        $action = "<a href='closed&upd=$id&cusidupd=$cus_id' title='Edit details' ><button class='btn btn-success' style='background-color:#009688;'>Close </button></a>";
    }



    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT cp.cus_id as cp_cus_id,cp.cus_name,cp.area_confirm_area,cp.area_confirm_subarea,cp.area_line,cp.mobile1, ii.cus_id as ii_cus_id, ii.req_id FROM 
    acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id
    where ii.status = 0 and ii.cus_status = 20 GROUP BY ii.cus_id ";
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