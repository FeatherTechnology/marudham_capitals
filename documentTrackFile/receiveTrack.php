<?php
session_start();
$userid = $_SESSION['userid'];

include '../ajaxconfig.php';

$id = $_POST['id'];//table id
$result ='';

$qry = $connect->query("UPDATE document_track set track_status = '3', update_login_id = $userid, updated_date = now() where id='".$id."'  "); //Received by doc rec access user. after received directly removed from list.

if($qry){
	$result = "Successfully Marked as Received!";
}else{
	$result = "Error While Submitting";
}


echo $result;

// Close the database connection
$connect = null;
?>