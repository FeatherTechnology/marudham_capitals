<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id = $_POST['bank_id'];
$ref_code = $_POST['ref_code'];
$cat_info = $_POST['cat_info'];
$trans_id = $_POST['trans_id'];
$remark = $_POST['remark'];
$amt = $_POST['amt'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


$qry = $connect->query("INSERT INTO `ct_cr_boti`(`ref_code`,`to_bank_id`,`category`,`trans_id`, `remark`, `amt`, `insert_login_id`, `created_date`) 
VALUES ('$ref_code','$bank_id','$cat_info','$trans_id','$remark','$amt','$user_id','$op_date' )");

if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submitting";
}

echo $response;

// Close the database connection
$connect = null;
?>