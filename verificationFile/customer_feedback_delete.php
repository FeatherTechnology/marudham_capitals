<?php
include '../ajaxconfig.php';

$id = $_POST['id'];

// we need to unlink old files
$qry = $connect->query("SELECT upload FROM `verification_cus_feedback` where `id`='$id' ");
$old_pic = $qry->fetch()['upload'];
$files = array_filter(array_map('trim', explode(',', $old_pic)));

foreach ($files as $file) {
	$filePath = "../uploads/customer_summary/" . $file;

	if (file_exists($filePath)) {
		unlink($filePath);
	}
}

$delct = $connect->query("DELETE FROM `verification_cus_feedback` WHERE id = '$id' ");

if ($delct) {
	$message = " Feedback Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;