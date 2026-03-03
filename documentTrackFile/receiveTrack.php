<?php
session_start();
$userid = $_SESSION['userid'];

include '../ajaxconfig.php';

$id = $_POST['id'];//table id
$cus_id = $_POST['cus_id'];//cus_id
$replace_status = $_POST['replace_status'];
$result ='';

if($replace_status =='0'){ //if replace status 0-YES means need to combine the replace doc into current doc. so need to show in list, after combine action done then asusual remove from list.
	$trackstatus = '4';
}else{
	$trackstatus = '3';
	$connect->query("UPDATE noc SET noc_replace_status = 2 WHERE cus_id = '$cus_id' AND noc_replace_status = 1 "); //update noc table for replace noc. 
}

$qry = $connect->query("UPDATE document_track SET track_status = $trackstatus, update_login_id = $userid, updated_date = NOW() WHERE id='$id'"); //Received by doc rec access user. after received directly removed from list.

if($qry){
	$result = "Successfully Marked as Received!";
}else{
	$result = "Error While Submitting";
}

echo $result;

// Close the database connection
$connect = null;
?>