<?php
include '../../ajaxconfig.php';

$id = $_POST['chequeid'];

$delct = $connect->query("DELETE FROM gold_info WHERE id = '$id' ");

if ($delct) {
	$message = " Gold Info Deleted Successfully";
}


echo json_encode($message);

// Close the database connection
$connect = null;