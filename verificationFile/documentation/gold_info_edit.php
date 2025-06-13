<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
		
//in verification doc insert is just information, acknowledgement is final and they add newly so verification & approval have seperate table. changes happen after deployment.
if(isset($_POST['verification_doc']) && $_POST['verification_doc'] == '1'){
	$tablename = 'verification_gold_info';
	
}else{
	$tablename = 'gold_info';
	
}

$gold = array();

$goldInfo = $connect->query("SELECT * FROM $tablename WHERE id = '$id' ");
$goldDetails = $goldInfo->fetch();

$gold['id'] = $goldDetails['id'];
$gold['gold_sts'] = $goldDetails['gold_sts'];
$gold['gold_type'] = $goldDetails['gold_type'];
$gold['Purity'] = $goldDetails['Purity'];
$gold['gold_Count'] = $goldDetails['gold_Count'];
$gold['gold_Weight'] = $goldDetails['gold_Weight'];
$gold['gold_Value'] = $goldDetails['gold_Value'];
$gold['gold_upload'] = $goldDetails['gold_upload'];

echo json_encode($gold);

// Close the database connection
$connect = null;