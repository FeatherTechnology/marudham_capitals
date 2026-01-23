<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bdep_id  = $_POST['bdep_id'];
$bank_id  = $_POST['bank_id_cd'];   // From bank
$to_bank  = $_POST['to_bank_cd'];   // (kept if needed later)
$acc_no   = $_POST['acc_no_cd'];
$location = $_POST['location_cd'];
$amt      = floatval(str_replace(",", "", $_POST['amt_cd']));
$ref_code = $_POST['ref_code_cd'];
$trans_id = $_POST['trans_id_cd'];
$remark   = $_POST['remark_cd'];
$sts   = $_POST['sts'];
$op_date  = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
$chk = $connect->query("SELECT transaction_amount  FROM bank_stmt WHERE bank_id = '$bank_id' AND trans_id = '$trans_id' AND $sts > 0 ")->fetch(PDO::FETCH_ASSOC);

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

/* 🔁 CHECK ALREADY SUBMITTED */
$checkEntry = $connect->query(" SELECT created_date FROM ct_cr_cash_deposit WHERE db_ref_id = '$bdep_id' ");

if ($checkEntry->rowCount() > 0) {
    echo "Already Submitted";
    exit;
}

/* ✅ INSERT CASH DEPOSIT */
$qry = $connect->query("INSERT INTO ct_cr_cash_deposit
    (db_ref_id, to_bank_id, location, amt, ref_code, trans_id, remark, insert_login_id, created_date)
    VALUES
    ('$bdep_id', '$bank_id', '$location', '$amt', '$ref_code', '$trans_id', '$remark', '$user_id', '$op_date')");

/* ✅ UPDATE BANK STATEMENT */
$upqry = $connect->query(" UPDATE bank_stmt  SET transaction_amount = $available_amt - '$amt', clr_status = CASE WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 ELSE clr_status END,update_login_id = '$user_id',
        updated_date = NOW() WHERE bank_id = '$bank_id' AND trans_id = '$trans_id' ");

if ($qry && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submit";
}

$connect = null;
?>
