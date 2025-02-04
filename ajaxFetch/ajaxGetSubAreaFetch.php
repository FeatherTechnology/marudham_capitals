<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'slc.sub_area_id',
    'slc.sub_area_name',
    'alc.area_name',
    'alc.area_enable'
);

$query = "SELECT slc.*,alc.area_enable,alc.area_name FROM sub_area_list_creation slc
JOIN area_list_creation alc ON alc.area_id = slc.area_id_ref
WHERE slc.status = 0 ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= "and
            (slc.sub_area_name LIKE '%" . $_POST['search'] . "%' 
            OR alc.area_name LIKE '%" . $_POST['search'] . "%' ) ";
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
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['area_name'];
    $area_check = $row['area_enable'];

    $sub_area_enable = $row['sub_area_enable'];
    $id = $row['sub_area_id'];

    if ($area_check == 0) {
        $action = "<button onclick='enable($id)' " . ($sub_area_enable == 0 ? "disabled" : "") . " title='Edit details' class='btn btn-success'>Enable</button>&nbsp;&nbsp;
        <button onclick='disable($id)' " . ($sub_area_enable == 1 ? "disabled" : "") . " title='Edit details' class='btn btn-danger'>Disable</button>";
    } elseif ($area_check == 1) {
        $action = "<button onclick='disable($id)' disabled title='Edit details' class='btn btn-danger'>Disable</button>";
    }

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM  sub_area_list_creation";
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
