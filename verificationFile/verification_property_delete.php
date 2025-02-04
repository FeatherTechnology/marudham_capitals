<?php
include '../ajaxconfig.php';

$id = $_POST['prptyid'];

$delct = $connect->query("DELETE FROM `verification_property_info` WHERE id = '$id' ");

if ($delct) {
	$message = " Property Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;
