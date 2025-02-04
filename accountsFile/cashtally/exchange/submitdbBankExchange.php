<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$ref_code = $_POST['ref_code'];
$from_acc_id = $_POST['from_acc_id_bex'];
$from_acc = $_POST['from_acc_bex'];
$to_bank_id = $_POST['to_bank_bex'];
$to_user_id = $_POST['user_id_bex'];
$trans_id = $_POST['trans_id_bex'];
$remark = $_POST['remark_bex'];
$amt = $_POST['amt_bex'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


//////////////////////// To get Exchange reference Code once again /////////////////////////
$myStr = "EXD";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bexchange WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_db_bexchange WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["ref_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $ref_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-100001";
    $ref_code = $initialapp;
}
///////////////////////////////////////////////////////////////////////////////////////////

$qry = $connect->query("INSERT INTO `ct_db_bexchange`(`ref_code`, `from_acc_id`, `to_bank_id`, `to_user_id`, `trans_id`, `remark`, `amt`, `insert_login_id`, `created_date`) 
VALUES ('$ref_code','$from_acc_id','$to_bank_id','$to_user_id','$trans_id','$remark','$amt','$user_id','$op_date')");

if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error";
}

echo $response;

// Close the database connection
$connect = null;
?>