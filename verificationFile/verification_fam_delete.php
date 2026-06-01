<?php
include '../ajaxconfig.php';

$id = $_POST['famid'];

$Qry = $connect->query("SELECT guarentor_name FROM customer_profile WHERE guarentor_name = '$id'");

if($Qry->rowCount() > 0){
	$message = "Family member used as Guarantor.";

}else{
	$delct = $connect->query("DELETE FROM `verification_family_info` WHERE id = '$id' ");
	
	if ($delct) {
		$message = " Family Info Deleted Successfully";
	}
}

echo json_encode($message);

// Close the database connection
$connect = null;