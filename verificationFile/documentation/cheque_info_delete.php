<?php
include '../../ajaxconfig.php';

$id = $_POST['chequeid'];
    
$filePath = "../../uploads/verification/cheque_upd/";

// Get all files for this cheque_upd
$stmt = $connect->query("SELECT upload_cheque_name FROM cheque_upd WHERE cheque_table_id = '$id'");
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Delete files from folder
foreach ($files as $fileinfo) {
	$file = $filePath . $fileinfo['upload_cheque_name'];

	if (!empty($fileinfo['upload_cheque_name']) && file_exists($file)) {
		unlink($file);
	}
}

$delct = $connect->query("DELETE FROM `cheque_info` WHERE id = '$id' ");

$connect->query("DELETE FROM `cheque_upd` WHERE `cheque_table_id`='$id'");
$connect->query("DELETE FROM `cheque_no_list` WHERE `cheque_table_id`='$id'");

if ($delct) {
	$message = " cheque Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;