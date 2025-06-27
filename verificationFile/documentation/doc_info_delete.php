<?php
include '../../ajaxconfig.php';

$id = $_POST['id'];

$delct = $connect->query("DELETE FROM document_info WHERE id = '$id' ");

if ($delct) {
	$message = " Document Info Deleted Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;