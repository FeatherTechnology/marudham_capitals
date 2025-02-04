<?php
session_start();
$user_id = $_SESSION['userid'];

include("../../ajaxconfig.php");

$credit = ''; $debit = '';$response = '';

$bank_id = $_POST['bank_name'];
$acc_no = $_POST['acc_no'];
$trans_date = $_POST['trans_date'];
$trans_id = $_POST['trans_id'];
$narration = $_POST['narration'];
$crdb = $_POST['crdb'];
$amt = $_POST['amt'];
if($crdb == 1){$credit = $amt; }else if($crdb == 2){$debit = $amt; }
$balance = $_POST['bal'];



$qry = $connect->query("SELECT trans_date from bank_stmt where insert_login_id = '$user_id' and bank_id = '$bank_id' and trans_date = '$trans_date' and created_date < now() ");


if($qry->rowCount() > 0){
    // if true then table has data of this transaction date
    $response = 1;
}else{
    // if true then table has no data of this transaction date
    $response = 0;
}

echo $response;

// Close the database connection
$connect = null;
?>
