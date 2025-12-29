<?php
session_start();
$userid = $_SESSION['userid'];

include '../ajaxconfig.php';

$id = $_POST['id'];//table id
$cus_id = $_POST['cus_id'];//cus_id
$result ='';

$qry = $connect->query("UPDATE document_track set track_status = '3', update_login_id = $userid, updated_date = now() where id='".$id."'  "); //Received by doc rec access user. after received directly removed from list.

$qry1 = $connect->query("UPDATE noc SET noc_replace_status = 2 WHERE cus_id = '$cus_id' AND noc_replace_status = 1 "); //update noc table for replace noc. 

if($qry && $qry1){
	$result = "Successfully Marked as Received!";
}else{
	$result = "Error While Submitting";
}


echo $result;

// Close the database connection
$connect = null;
?>