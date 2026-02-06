<?php
@session_start();
include('..\ajaxconfig.php');
include('..\user_based_sub_area_Ids.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$sub_area_list = getUserSubAreaList($connect, 'collection');

if ($userid != 1) {
    $stmt = $connect->prepare("SELECT ag_id , role FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);
    $role = $rowuser['role'];
    $ag_id = $rowuser['ag_id'];
}

$column = array(
    'ii.id',
    'cr.cus_id',
    'cr.autogen_cus_id',
    'cr.customer_name',
    'alc.area_name',
    'salc.sub_area_name',
    'ii.id',
    'alm.line_name',
    'cr.mobile1',
    'ii.id'
);

if ($userid == 1) {
    $query = "SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, alc.area_name, salc.sub_area_name, alm.line_name AS area_line, cr.mobile1, ii.req_id , b.branch_name
    FROM in_issue AS ii
    INNER JOIN customer_register AS cr ON cr.cus_id = ii.cus_id
    INNER JOIN customer_status AS cs ON cs.req_id = ii.req_id
    INNER JOIN area_list_creation AS alc ON alc.area_id = cr.area_confirm_area
    INNER JOIN sub_area_list_creation AS salc ON salc.sub_area_id = cr.area_confirm_subarea
    INNER JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = salc.sub_area_id
    INNER JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    INNER JOIN branch_creation b ON b.branch_id = alm.branch_id
    WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17"; // Only Issued and all lines not relying on sub area// 14 and 17 means collection entries, 17 removed from issue list

} else {

    if ($role != '2') {
        //show only issued customers within the same lines of user. // 14 and 17 means collection entries, 17 removed from issue list
        $query = "SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, alc.area_name, salc.sub_area_name, alm.line_name AS area_line, cr.mobile1, ii.req_id , b.branch_name
        FROM in_issue AS ii
        INNER JOIN customer_register AS cr ON cr.cus_id = ii.cus_id
        INNER JOIN customer_status AS cs ON cs.req_id = ii.req_id
        INNER JOIN area_list_creation AS alc ON alc.area_id = cr.area_confirm_area
        INNER JOIN sub_area_list_creation AS salc ON salc.sub_area_id = cr.area_confirm_subarea
        INNER JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = salc.sub_area_id
        INNER JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
        INNER JOIN branch_creation b ON b.branch_id = alm.branch_id
        WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17 AND cr.area_confirm_subarea IN ($sub_area_list) ";
    } else { // if agent then check the possibilities
        $query = "SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, alc.area_name, salc.sub_area_name, alm.line_name AS area_line, cr.mobile1, ii.req_id , b.branch_name
        FROM in_issue AS ii
        INNER JOIN customer_register AS cr ON cr.cus_id = ii.cus_id
        INNER JOIN request_creation AS rc ON rc.req_id = ii.req_id
        INNER JOIN customer_status AS cs ON cs.req_id = ii.req_id
        INNER JOIN area_list_creation AS alc ON alc.area_id = cr.area_confirm_area
        INNER JOIN sub_area_list_creation AS salc ON salc.sub_area_id = cr.area_confirm_subarea
        INNER JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = salc.sub_area_id
        INNER JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
        INNER JOIN branch_creation b ON b.branch_id = alm.branch_id
        WHERE ii.status = 0 AND ii.cus_status BETWEEN 14 AND 17 AND cr.area_confirm_subarea IN ($sub_area_list) AND (
        rc.user_type = 'Agent'
        OR (rc.agent_id IS NOT NULL AND rc.agent_id != '')
        OR rc.insert_login_id = '$userid'
        )
        AND rc.agent_id = $ag_id"; // 14 and 17 means collection entries, 17 removed from issue list
    }
}

if ($_POST["CustomerStatus"] != '') {
    $cus_sts = $_POST["CustomerStatus"];
    $query .= " AND cs.sub_status ='$cus_sts' ";
}

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " AND (cr.cus_id LIKE '" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' 
            OR cr.customer_name LIKE '%" . $_POST['search'] . "%' 
            OR alc.area_name LIKE '%" . $_POST['search'] . "%' 
            OR salc.sub_area_name LIKE '%" . $_POST['search'] . "%' 
            OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
            OR cr.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
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
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['customer_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['area_line'];
    $sub_array[] = $row['mobile1'];

    $cus_id = $row['cus_id'];
    $id     = $row['req_id'];

    if ($_POST["CustomerStatus"] != '') {
        $action = "<a href='collection&upd=$id&cusidupd=$cus_id&duestatus=due_nill' title='Edit details' ><button class='btn btn-success' style='background-color:#009688;'>View</button></a>";
    } else {
        $action = "<a href='collection&upd=$id&cusidupd=$cus_id' title='Edit details' ><button class='btn btn-success' style='background-color:#009688;'>View</button></a>";
    }

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = "SELECT ii.cus_id FROM in_issue ii WHERE ii.status = 0 AND (ii.cus_status >= 14 AND ii.cus_status <= 17) GROUP BY ii.cus_id ";
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
