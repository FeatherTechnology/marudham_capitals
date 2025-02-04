<?php
include '../ajaxconfig.php';

$id = $_POST['bankid'];


$delct = $connect->query("DELETE FROM `verification_bank_info` WHERE id = '$id' ");

if ($delct) {
	$message = " Bank Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;