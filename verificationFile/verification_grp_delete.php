<?php
include '../ajaxconfig.php';

$id = $_POST['Groupid'];

$delct = $connect->query("DELETE FROM `verification_group_info` WHERE id = '$id' ");

if ($delct) {
	$message = " Group Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;
