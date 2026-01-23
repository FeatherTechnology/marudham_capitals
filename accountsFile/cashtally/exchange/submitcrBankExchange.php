<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bex_id        = $_POST['bex_id'];
$from_bank_id  = $_POST['from_acc_id'];
$to_bank_id    = $_POST['to_bank_id'];
$to_user_id    = $_POST['to_user_id'];
$from_user_id  = $_POST['from_user_id'];
$ref_code      = $_POST['ref_code'];
$trans_id      = $_POST['trans_id'];
$remark        = $_POST['remark'];
$sts        = $_POST['sts'];
$amt           = floatval(str_replace(",", "", $_POST['amt']));
$op_date       = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
// echo " SELECT transaction_amount FROM bank_stmt  WHERE bank_id = '$from_bank_id'  AND trans_id = '$trans_id' AND $sts > 0";die;
$chk = $connect->query(" SELECT transaction_amount FROM bank_stmt  WHERE bank_id = '$to_bank_id'  AND trans_id = '$trans_id' AND $sts > 0")->fetch(PDO::FETCH_ASSOC);

if (!$chk) {
    echo "Invalid Transaction Id";
    exit;
}

$available_amt = floatval($chk['transaction_amount']);

/* ❌ AMOUNT VALIDATION */
if ($amt > $available_amt) {
    echo "Transaction Amount Mismatched";
    exit;
}

/* ✅ UPDATE DEBIT EXCHANGE (MARK AS RECEIVED) */
$updateDebit = $connect->query(" UPDATE ct_db_bexchange SET received = 0 WHERE id = '$bex_id' ");

if (!$updateDebit) {
    echo "Error While Updating Debit Entry";
    exit;
}

/* ✅ INSERT CREDIT EXCHANGE ENTRY */
$insertCredit = $connect->query("
    INSERT INTO ct_cr_bexchange (db_ref_id, from_bank_id, to_bank_id, from_user_id, to_user_id, ref_code, trans_id, remark, amt, insert_login_id, created_date)
    VALUES
    ('$bex_id','$from_bank_id','$to_bank_id','$from_user_id','$to_user_id','$ref_code','$trans_id','$remark','$amt','$user_id','$op_date')");

/* ✅ UPDATE BANK STATEMENT (SUBTRACT + CLEAR STATUS) */
$upqry = $connect->query(" UPDATE bank_stmt SET transaction_amount = $available_amt - '$amt',clr_status = CASE WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 ELSE clr_status END, update_login_id = '$user_id', updated_date = NOW() WHERE bank_id = '$to_bank_id' AND trans_id = '$trans_id'");

if ($insertCredit && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submitting";
}

$connect = null;
?>