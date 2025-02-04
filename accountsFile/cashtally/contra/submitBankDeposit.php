<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');


$to_bank_bdep = $_POST['to_bank_bdep'];
$location_bdep = $_POST['location_bdep'];
$remark_bdep = $_POST['remark_bdep'];
$amt_bdep = $_POST['amt_bdep'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));

$qry = $connect->query("INSERT INTO `ct_db_bank_deposit`( `to_bank_id`, `location`, `remark`, `amount`, `insert_login_id`, `created_date`) 
    VALUES ('".$to_bank_bdep."','".$location_bdep."','".$remark_bdep."','".$amt_bdep."','".$user_id."','$op_date' )");

if($qry){
    $response = 'Submitted Successfully';
}else{
    $response = 'Error';
}

echo $response;

// Close the database connection
$connect = null;
?>