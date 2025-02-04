<?php
include '../../ajaxconfig.php';

$id = $_POST['signid'];

$delct = $connect->query("DELETE FROM `signed_doc_info` WHERE id = '$id' ");

if ($delct) {
	$message = " signed Doc Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;
