<?php
session_start();
$userid = $_SESSION['userid'];

include '../ajaxconfig.php';

$id = $_POST['id'];//table id
$result ='';

$qry = $connect->query("UPDATE document_track set track_status = '1', update_login_id = $userid, updated_date = now() where id='".$id."'  "); //return track.

if($qry){
	$result = "Successfully Removed!";
}else{
	$result = "Error While Removing";
}

echo $result;

// Close the database connection
$connect = null;
?>