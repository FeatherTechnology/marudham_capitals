<?php
include('../ajaxconfig.php');

$famList_arr = array();

$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$result = $connect->query("SELECT id,famname,relationship FROM `verification_family_info` where cus_id='$cus_id'");

while ($row = $result->fetch()) {
    $fam_name = $row['famname'];
    $fam_id = $row['id'];
    $relationship = $row['relationship'];
    $famList_arr[] = array("fam_id" => $fam_id, "fam_name" => $fam_name, "relationship" => $relationship);
}

$result = $connect->query("SELECT customer_name from customer_register where cus_id = '$cus_id' ");
$cus_name = $result->fetch()['customer_name'];
$famList_arr[] = array("fam_id" => $cus_id, "fam_name" => $cus_name, "relationship" => 'customer');

echo json_encode($famList_arr);

// Close the database connection
$connect = null;