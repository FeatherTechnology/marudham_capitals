<?php
include '../ajaxconfig.php';

$id = $_POST['famid'];

$delct = $connect->query("DELETE FROM `verification_family_info` WHERE id = '$id' ");

if ($delct) {
	$message = " Family Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;