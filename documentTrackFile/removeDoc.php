<?php
session_start();
$userid = $_SESSION['userid'];

include '../ajaxconfig.php';

$req_id = $_POST['req_id'];//table id
$result ='';

$qry = $connect->query("UPDATE noc set noc_replace_status = '0', update_login_id = $userid, updated_date = now() where req_id='".$req_id."'  "); //return track.

if($qry){
	$result = 0;
}else{
	$result = 1;
}

echo $result;

// Close the database connection
$connect = null;
?>