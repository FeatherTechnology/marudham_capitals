<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bex_id = $_POST['bex_id'];
$from_bank_id = $_POST['from_acc_id'];
$to_bank_id = $_POST['to_bank_id'];
$to_user_id = $_POST['to_user_id'];
$from_user_id = $_POST['from_user_id'];
$ref_code = $_POST['ref_code'];
$trans_id = $_POST['trans_id'];
$remark = $_POST['remark'];
$amt = str_replace(",","",$_POST['amt']);
$op_date = date('Y-m-d',strtotime($_POST['op_date']));

$response = '';

//set dedit table as amount credited/ received
$qry = $connect->query("UPDATE ct_db_bexchange set received = 0 where id = '$bex_id' ");

if($qry){
    $qry = $connect->query("INSERT INTO `ct_cr_bexchange`( `db_ref_id`,`from_bank_id`, `to_bank_id`, `from_user_id`, `to_user_id`, `ref_code`, `trans_id`,`remark`, `amt`, `insert_login_id`, `created_date`) 
        VALUES ('$bex_id','$from_bank_id', '$to_bank_id', '$from_user_id', '$to_user_id','$ref_code','$trans_id','$remark','$amt','$user_id','$op_date' )");
    
    if($qry){
        $response = "Submitted Successfully";
    }else{
        $response = "Error";
    }
}else{
    $response = "error";
}


echo $response;

// Close the database connection
$connect = null;
?>