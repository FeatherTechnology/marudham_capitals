<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'adm.map_id',
    'adm.duefollowup_name',
    'c.company_name',
    'bc.branch_name',
    'adm.map_id',
    'adm.status',
    'adm.status'
);

$query = "SELECT adm.*, c.company_name, bc.branch_name,
        (SELECT GROUP_CONCAT(alc.area_name SEPARATOR ', ')
        FROM area_list_creation alc
        WHERE FIND_IN_SET(alc.area_id, adm.area_id) AND alc.status = 0) AS area_names
        FROM area_duefollowup_mapping adm
        JOIN company_creation c ON c.company_id = adm.company_id
        JOIN branch_creation bc ON adm.branch_id = bc.branch_id
        WHERE 1 ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= "AND (adm.duefollowup_name LIKE '%" . $search . "%'
            OR c.company_name LIKE '%" . $search . "%'
            OR bc.branch_name LIKE '%" . $search . "%'
            OR (SELECT GROUP_CONCAT(alc.area_name SEPARATOR ', ')
                FROM area_list_creation alc
                WHERE FIND_IN_SET(alc.area_id, adm.area_id) AND alc.status = 0) LIKE '%" . $search . "%') ";
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

    if ($sno != "") {
        $sub_array[] = $sno;
    }

    $sub_array[] = $row['duefollowup_name'];

    $sub_array[] = $row["company_name"];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row["area_names"];
    // $sub_array[] = $row["sub_area_names"];

    $status      = $row['status'];
    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }

    $id   = $row['map_id'];
    $action = "<a href='area_mapping&upd=$id&type=duefollowup' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
	<a href='area_mapping&del=$id&type=duefollowup' title='Delete details' class='delete_area_mapping'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM area_duefollowup_mapping";
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
