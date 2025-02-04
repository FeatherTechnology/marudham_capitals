<?php
include('../ajaxconfig.php');

$Membername_arr = array();

$cus_id = $_POST['cus_id'];
$result = $connect->query("SELECT famname,relationship FROM `verification_family_info` where cus_id = '$cus_id' ");

while ($row = $result->fetch()) {
    $Membername_arr[] = $row['famname'] . ' - ' . $row['relationship'];
}

$result = $connect->query("SELECT customer_name FROM `customer_register` where cus_id = '$cus_id' ");

$row = $result->fetch();
$Membername_arr[] = $row['customer_name'] . ' - Customer';

echo json_encode($Membername_arr);

// Close the database connection
$connect = null;
