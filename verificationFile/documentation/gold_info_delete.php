<?php
include '../../ajaxconfig.php';

$id = $_POST['chequeid'];
		
//in verification doc insert is just information, acknowledgement is final and they add newly so verification & approval have seperate table. changes happen after deployment.
if(isset($_POST['verification_doc']) && $_POST['verification_doc'] == '1'){
	$tablename = 'verification_gold_info';
	
}else{
	$tablename = 'gold_info';
	
}

$delct = $connect->query("DELETE FROM $tablename WHERE id = '$id' ");

if ($delct) {
	$message = " Gold Info Deleted Successfully";
}


echo json_encode($message);

// Close the database connection
$connect = null;