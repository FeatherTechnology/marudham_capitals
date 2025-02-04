<?php
include('../../ajaxconfig.php');

$opt_for = $_POST['opt_for'];

$i = 0;
$records = array();

$qry = $connect->query("SELECT * from name_detail_creation WHERE opt_for = '$opt_for'");
while ($row = $qry->fetch()) {
    $records[$i]['name_id'] = $row['name_id'];
    $records[$i]['name'] = $row['name'];
    $records[$i]['area'] = $row['area'];
    $records[$i]['ident'] = $row['ident'];
    $i++;
}


echo json_encode($records);

// Close the database connection
$connect = null;
