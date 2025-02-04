<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$li_id = $_POST['li_id'];
$li_user_id = $_POST['li_user_id'];
$li_bank_id = $_POST['li_bank_id'];
$ref_code = $_POST['ref_code'];
$username = $_POST['username'];
$usertype = $_POST['usertype'];
$cheque_no = $_POST['cheque_no'];
$trans_id = $_POST['trans_id'];
$netcash = str_replace(',','',$_POST['netcash']);
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


$qry = $connect->query("INSERT INTO `ct_db_bissued`(`ref_code`, `li_id`, `li_user_id`,`li_bank_id`, `username`, `usertype`, `cheque_no`, `trans_id`, `netcash`, `insert_login_id`, `created_date`) 
VALUES ('$ref_code','$li_id','$li_user_id','$li_bank_id','$username','$usertype','$cheque_no','$trans_id','$netcash','$user_id','$op_date')");

if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submitting";
}

echo $response;

// Close the database connection
$connect = null;
?>