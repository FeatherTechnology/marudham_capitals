<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id = $_POST['bank_id'];
$ref_code = $_POST['ref_code'];
$cat_info = $_POST['cat_info'];
$trans_id = $_POST['trans_id'];
$remark   = $_POST['remark'];
$amt      = floatval($_POST['amt']);
$sts      = $_POST['sts'];
$op_date  = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
$chk = $connect->query("  SELECT transaction_amount FROM bank_stmt WHERE bank_id = '$bank_id' AND trans_id = '$trans_id' AND $sts > 0 ")->fetch(PDO::FETCH_ASSOC);

if (!$chk) {
    echo "Invalid Transaction Id";
    exit;
}

$available_amt = floatval($chk['transaction_amount']);

/* ❌ ENTERED AMOUNT IS MORE */
if ($amt > $available_amt) {
    echo "Transation Amount Mismatched" ;
    exit;
}

/* ✅ INSERT */
$qry = $connect->query("INSERT INTO ct_cr_boti (ref_code, to_bank_id, category, trans_id, remark, amt, insert_login_id, created_date) VALUES('$ref_code','$bank_id','$cat_info','$trans_id','$remark','$amt','$user_id','$op_date')");

$upqry = $connect->query(" UPDATE bank_stmt SET transaction_amount = $available_amt - '$amt',clr_status = CASE WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 ELSE clr_status END, update_login_id = '$user_id', updated_date = NOW() WHERE bank_id = '$bank_id' AND trans_id = '$trans_id'");


if ($qry && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submitting";
}

$connect = null;
?>
