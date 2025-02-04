<?php
include '../ajaxconfig.php';

if(isset($_POST["id"])){
	$id  = $_POST["id"];
}

	$delct=$connect->query("UPDATE concern_subject SET status = 1 WHERE concern_sub_id = '".$id."' ");
	if($delct){
		$message="Loan Category Inactivated Successfully";
	}

echo json_encode($message);

// Close the database connection
$connect = null;
?>