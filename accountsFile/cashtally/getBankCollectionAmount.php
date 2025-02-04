<?php
session_start();
$user_id = $_SESSION['userid'];

include("../../ajaxconfig.php");

$bank_id = $_POST['bank_id']; 
$op_date = date('Y-m-d',strtotime($_POST['op_date']));
$response = '';

$qry = $connect->query("SELECT sum(total_paid_track) as total_paid from `collection` where coll_mode != 1 and date(coll_date) = date('$op_date') and bank_id = '$bank_id' ");
$response = $qry->fetch()['total_paid'];


echo $response;

// Close the database connection
$connect = null;
?>