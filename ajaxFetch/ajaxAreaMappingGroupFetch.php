<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'agm.map_id',
    'agm.group_name',
    'c.company_name',
    'b.branch_name',
    'agm.map_id',
    'agm.map_id',
    'agm.status',
    'agm.status'
);

$query = "SELECT agm.*, c.company_name, b.branch_name,
        (SELECT GROUP_CONCAT(alc.area_name SEPARATOR ', ')
        FROM area_list_creation alc
        WHERE FIND_IN_SET(alc.area_id, agm.area_id) AND alc.status = 0) AS area_names,
        (SELECT GROUP_CONCAT(salc.sub_area_name SEPARATOR ', ')
        FROM sub_area_list_creation salc
        WHERE FIND_IN_SET(salc.sub_area_id, agm.sub_area_id) AND salc.status = 0) AS sub_area_names
        FROM area_group_mapping agm
        JOIN company_creation c ON c.company_id = agm.company_id
        JOIN branch_creation b ON b.branch_id = agm.branch_id
        WHERE 1 ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= "AND (agm.group_name LIKE '%" . $search . "%'
            OR c.company_name LIKE '%" . $search . "%'
            OR b.branch_name LIKE '%" . $search . "%'
            OR (SELECT GROUP_CONCAT(alc.area_name SEPARATOR ', ')
                FROM area_list_creation alc
                WHERE FIND_IN_SET(alc.area_id, agm.area_id) AND alc.status = 0) LIKE '%" . $search . "%'
            OR (SELECT GROUP_CONCAT(salc.sub_area_name SEPARATOR ', ')
                FROM sub_area_list_creation salc
                WHERE FIND_IN_SET(salc.sub_area_id, agm.sub_area_id) AND salc.status = 0) LIKE '%" . $search . "%') ";
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

    $sub_array[] = $row['group_name'];

    $sub_array[] = $row["company_name"];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row["area_names"];
    $sub_array[] = $row["sub_area_names"];

    $status      = $row['status'];
    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }

    $id   = $row['map_id'];
    $action = "<a href='area_mapping&upd=$id&type=group' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
	<a href='area_mapping&del=$id&type=group' title='Delete details' class='delete_area_mapping'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM area_group_mapping";
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
