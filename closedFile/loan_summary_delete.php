<?php
include '../ajaxconfig.php';

$id = $_POST['id'];

 
$delct = $connect->query("DELETE FROM `loan_summary_feedback` WHERE id = '$id' ");

	if($delct){
		$message=" Feedback Deleted Successfully";
	}


echo json_encode($message);
?>