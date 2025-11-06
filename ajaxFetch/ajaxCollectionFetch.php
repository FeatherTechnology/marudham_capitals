<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT role, group_id, line_id, ag_id FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $role = $rowuser['role'];
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
        $ag_id = $rowuser['ag_id'];
    }

    $line_id = explode(',', $line_id);
    $sub_area_list = array();
    foreach ($line_id as $line) {
        $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping where map_id = $line ");
        if ($lineQry->rowCount() > 0) {
            $row_sub = $lineQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
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
    'cr.autogen_cus_id',
    'cp.cus_name',
    'alc.area_name',
    'salc.sub_area_name',
    'cp.id',
    'alm.line_name',
    'cp.mobile1',
    'cp.id'
);

if ($userid == 1) {
    $query = "SELECT  cp.cus_id AS cp_cus_id, cr.autogen_cus_id,cp.cus_name,alc.area_name,salc.sub_area_name,alm.line_name AS area_line,cp.mobile1,ii.cus_id AS ii_cus_id,
ii.req_id
FROM in_issue AS ii
INNER JOIN acknowlegement_customer_profile AS cp ON ii.cus_id = cp.cus_id
INNER JOIN customer_register AS cr ON cr.cus_id = cp.cus_id
INNER JOIN customer_status AS cs ON cs.req_id = cp.req_id
INNER JOIN area_list_creation AS alc ON alc.area_id = cp.area_confirm_area
INNER JOIN sub_area_list_creation AS salc ON salc.sub_area_id = cp.area_confirm_subarea
INNER JOIN area_line_mapping AS alm ON FIND_IN_SET(salc.sub_area_id, alm.sub_area_id)
WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17"; // Only Issued and all lines not relying on sub area// 14 and 17 means collection entries, 17 removed from issue list

} else {

    if ($role != '2') {
        //show only issued customers within the same lines of user. // 14 and 17 means collection entries, 17 removed from issue list
        $query = "SELECT cp.cus_id AS cp_cus_id,cr.autogen_cus_id,cp.cus_name,alc.area_name,salc.sub_area_name,alm.line_name AS area_line,cp.mobile1,ii.cus_id AS ii_cus_id,ii.req_id
FROM in_issue AS ii
INNER JOIN acknowlegement_customer_profile AS cp ON ii.cus_id = cp.cus_id
INNER JOIN customer_register AS cr ON cr.cus_id = cp.cus_id
INNER JOIN customer_status AS cs ON cs.req_id = cp.req_id
INNER JOIN area_list_creation AS alc ON alc.area_id = cp.area_confirm_area
INNER JOIN sub_area_list_creation AS salc ON salc.sub_area_id = cp.area_confirm_subarea
INNER JOIN area_line_mapping AS alm ON FIND_IN_SET(salc.sub_area_id, alm.sub_area_id)
WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17 AND cp.area_confirm_subarea IN ($sub_area_list) ";
    } else { // if agent then check the possibilities
        $query = "SELECT cp.cus_id AS cp_cus_id,cr.autogen_cus_id,cp.cus_name,alc.area_name,salc.sub_area_name,alm.line_name AS area_line,cp.mobile1,ii.cus_id AS ii_cus_id,
ii.req_id
FROM in_issue AS ii
INNER JOIN acknowlegement_customer_profile AS cp ON ii.cus_id = cp.cus_id
INNER JOIN customer_register AS cr ON cr.cus_id = cp.cus_id
INNER JOIN request_creation AS rc ON rc.req_id = ii.req_id
INNER JOIN customer_status AS cs ON cs.req_id = cp.req_id
INNER JOIN area_list_creation AS alc ON alc.area_id = cp.area_confirm_area
INNER JOIN sub_area_list_creation AS salc ON salc.sub_area_id = cp.area_confirm_subarea
INNER JOIN area_line_mapping AS alm ON FIND_IN_SET(salc.sub_area_id, alm.sub_area_id)
WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17 AND cp.area_confirm_subarea IN ($sub_area_list) AND (
         rc.user_type = 'Agent'
         OR (rc.agent_id IS NOT NULL AND rc.agent_id != '')
         OR rc.insert_login_id = '$userid'
    )
    AND rc.agent_id = $ag_id
"; // 14 and 17 means collection entries, 17 removed from issue list

    }
}

if ($_POST["CustomerStatus"]!='') {
    $cus_sts = $_POST["CustomerStatus"];
    $query .= " AND cs.sub_status ='$cus_sts' ";
}

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " AND (cp.cus_id LIKE '" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' 
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%' 
            OR alc.area_name LIKE '%" . $_POST['search'] . "%' 
            OR salc.sub_area_name LIKE '%" . $_POST['search'] . "%' 
            OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
            OR cp.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

if ($userid == 1 || $role != '2') {
    $query .= " GROUP BY ii.cus_id ";
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
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
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];

    $line_name = $row['area_line'];
    $qry = $connect->query("SELECT b.branch_name FROM branch_creation b JOIN area_line_mapping l ON l.branch_id = b.branch_id where l.line_name = '" . $line_name . "' ");
    $row1 = $qry->fetch();
    $sub_array[] = $row1['branch_name'];

    $sub_array[] = $row['area_line'];
    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cp_cus_id'];
    $id     = $row['req_id'];
if($_POST["CustomerStatus"]!=''){
    $action = "<a href='collection&upd=$id&cusidupd=$cus_id&duestatus=due_nill' title='Edit details' ><button class='btn btn-success' style='background-color:#009688;'>View</button></a>";
}else{
 $action = "<a href='collection&upd=$id&cusidupd=$cus_id' title='Edit details' ><button class='btn btn-success' style='background-color:#009688;'>View</button></a>";
}

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = "SELECT cp.cus_id AS cp_cus_id FROM 
    acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id
    where ii.status = 0 and (ii.cus_status >= 14 and ii.cus_status <= 17) GROUP BY ii.cus_id ";
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