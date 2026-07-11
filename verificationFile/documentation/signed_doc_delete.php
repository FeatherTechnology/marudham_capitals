<?php
include '../../ajaxconfig.php';

$id = $_POST['signid'];

$filePath = "../uploads/verification/signed_doc/";

// Get all files for this signed_doc_id
$stmt = $connect->query("SELECT upload_doc_name FROM signed_doc WHERE signed_doc_id = '$id'");
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Delete files from folder
foreach ($files as $fileinfo) {
	$file = $filePath . $fileinfo['upload_doc_name'];

	if (!empty($fileinfo['upload_doc_name']) && file_exists($file)) {
		unlink($file);
	}
}

$connect->query("DELETE FROM `signed_doc` WHERE `signed_doc_id` ='$id'");


$delct = $connect->query("DELETE FROM `signed_doc_info` WHERE id = '$id' ");

if ($delct) {
	$message = " signed Doc Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;
