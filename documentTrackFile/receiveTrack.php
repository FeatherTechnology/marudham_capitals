<?php
session_start();
$userid = $_SESSION['userid'];

include '../ajaxconfig.php';

$id = $_POST['id'];//table id
$result ='';

$qry = $connect->query("UPDATE document_track set track_status = '2', update_login_id = $userid, updated_date = now() where id='".$id."'  ");

if($qry){
	$result = "Successfully Marked as Received!";
}else{
	$result = "Error While Submitting";
}


echo $result;

// Close the database connection
$connect = null;
?>