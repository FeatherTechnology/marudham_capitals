<?php 
include('../ajaxconfig.php');

$cus_id = $_POST['cus_id'];
$result = $connect->query("SELECT famname, relation_aadhar FROM `verification_family_info` where cus_id='$cus_id' AND relation_aadhar !=''");
$famList_arr = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($famList_arr);

// Close the database connection
$connect = null;
?>