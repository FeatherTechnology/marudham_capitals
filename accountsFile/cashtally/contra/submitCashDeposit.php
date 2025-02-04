<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bdep_id = $_POST['bdep_id'];
$bank_id = $_POST['bank_id_cd'];
$to_bank = $_POST['to_bank_cd'];
$acc_no = $_POST['acc_no_cd'];
$location = $_POST['location_cd'];
$amt = str_replace(",","",$_POST['amt_cd']);
$ref_code = $_POST['ref_code_cd'];
$trans_id = $_POST['trans_id_cd'];
$remark = $_POST['remark_cd'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


$qry=$connect->query("SELECT created_date from ct_cr_cash_deposit where db_ref_id = '$bdep_id' ");
// check whether this entry has been already entered
if($qry->rowCount() == 0){
    
    // $qry=$connect->query("UPDATE ct_db_bank_deposit set received = 0 where id = '$bdep_id' ");

    $qry = $connect->query("INSERT INTO `ct_cr_cash_deposit`( `db_ref_id`, `to_bank_id`,`location`,`amt`,`ref_code`, `trans_id`, `remark`, `insert_login_id`, `created_date`) 
        VALUES ('$bdep_id','$bank_id','$location','$amt','$ref_code','$trans_id','$remark','$user_id','$op_date' )");

    if($qry){
        $response = "Submitted Successfully";
    }else{
        $response = "Error";
    }

}else{
    $response = "Already Submitted";
}
echo $response;

// Close the database connection
$connect = null;
?>