<?php
include '../ajaxconfig.php';

$id = $_POST['id'];

$delct = $connect->query("DELETE FROM `cus_feedback_name` WHERE id = '$id' ");

if ($delct) {
	$message = " Feedback Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;