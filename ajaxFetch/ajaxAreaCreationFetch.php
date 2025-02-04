<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'ac.area_creation_id',
    'alc.area_name',
    'ac.sub_area',
    'ac.taluk',
    'ac.district',
    'ac.state',
    'ac.pincode',
    'ac.status'
);

$query = "SELECT ac.*,alc.area_name FROM area_creation ac 
JOIN area_list_creation alc ON alc.area_id = ac.area_name_id WHERE 1 ";

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "and (alc.area_name LIKE '%" . $_POST['search'] . "%' 
            OR ac.sub_area LIKE '%" . $_POST['search'] . "%' 
            OR ac.taluk LIKE '%" . $_POST['search'] . "%' 
            OR ac.district LIKE '%" . $_POST['search'] . "%' 
            OR ac.state LIKE '%" . $_POST['search'] . "%' 
            OR ac.pincode LIKE '%" . $_POST['search'] . "%') ";
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
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
    $sub_array[] = $row["area_name"];

    $sub_area_id = explode(',', $row['sub_area']);
    $sub_area_name = '';
    foreach ($sub_area_id as $id) {
        $getsubareaQry = "SELECT * from sub_area_list_creation where sub_area_id = '" . $id . "' and status = 0 ";
        $res = $connect->query($getsubareaQry);
        $row1 = $res->fetch();
        $sub_area_name .= $row1["sub_area_name"] . ', ';
    }
    $sub_area_name = rtrim($sub_area_name, ' , '); // will remove the last comma from string

    $sub_array[] = $sub_area_name;
    $sub_array[] = $row['taluk'];
    $sub_array[] = $row['district'];
    $sub_array[] = $row['state'];
    $sub_array[] = $row['pincode'];
    $status      = $row['status'];
    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id   = $row['area_creation_id'];

    $action = "<a href='area_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
	<a href='area_creation&del=$id' title='Delete details' class='delete_area'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM area_creation";
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
