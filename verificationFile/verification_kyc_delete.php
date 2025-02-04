<?php
include '../ajaxconfig.php';

$id = $_POST['kycid'];

$delct = $connect->query("DELETE FROM `verification_kyc_info` WHERE id = '$id' ");

if ($delct) {
	$message = " KYC Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;