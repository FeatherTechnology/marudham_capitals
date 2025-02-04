<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');


$bwd_id = $_POST['bwd_id'];
$bank_id = $_POST['bank_id_bwd'];
$from_bank = $_POST['from_bank_bwd'];
$acc_no = $_POST['acc_no_bwd'];
$cheque_no = $_POST['cheque_no_bwd'];
$ref_code = $_POST['ref_code_bwd'];
$trans_id = $_POST['trans_id_bwd'];
$amt = str_replace(",","",$_POST['amt_bwd']);
$remark = $_POST['remark_bwd'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));




$qry = $connect->query("UPDATE ct_db_cash_withdraw set received = 0 where id = '$bwd_id' ");

$qry = $connect->query("INSERT INTO `ct_cr_bank_withdraw`(`db_ref_id`,`ref_code`,`trans_id`,`from_bank_id`,`cheque_no`,`amt`, `remark`, `insert_login_id`, `created_date`) 
VALUES ('$bwd_id','$ref_code','$trans_id','$bank_id','$cheque_no','$amt','$remark','".$user_id."','$op_date')");

if($qry){
    $response = 'Submitted Successfully';
}else{
    $response = 'Error';
}

echo $response;

// Close the database connection
$connect = null;
?>