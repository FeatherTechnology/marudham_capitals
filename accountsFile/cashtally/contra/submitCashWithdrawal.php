<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$trans_id  = $_POST['trans_id'];
$from_bank = $_POST['from_bank'];
$cheque    = $_POST['cheque'];
$remark    = $_POST['remark'];
$sts    = $_POST['sts'];
$amt       = floatval(str_replace(",", "", $_POST['amt']));
$op_date   = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
$chk = $connect->query(" SELECT transaction_amount FROM bank_stmt  WHERE bank_id = '$from_bank' AND trans_id = '$trans_id'  AND $sts > 0")->fetch(PDO::FETCH_ASSOC);

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

//////////// GENERATE WITHDRAW REFERENCE CODE ////////////
$myStr = "WD";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_cash_withdraw WHERE ref_code != ''");

if ($selectIC->rowCount() > 0) {
    $codeAvailable = $connect->query("
        SELECT ref_code 
        FROM ct_db_cash_withdraw 
        WHERE ref_code != '' 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $row = $codeAvailable->fetch();
    $ac2 = $row["ref_code"];
    $appno2 = ltrim(strstr($ac2, '-'), '-');
    $appno2 = $appno2 + 1;
    $ref_code = $myStr . "-" . $appno2;
} else {
    $ref_code = $myStr . "-100001";
}
////////////////////////////////////////////////////////

/* ✅ INSERT CASH WITHDRAW */
$qry = $connect->query(" INSERT INTO ct_db_cash_withdraw  (from_bank_id, ref_code, trans_id, cheque_no, remark, amt, insert_login_id, created_date)
    VALUES
    ('$from_bank','$ref_code','$trans_id','$cheque','$remark','$amt','$user_id','$op_date') ");

/* ✅ UPDATE BANK STATEMENT */
$upqry = $connect->query(" UPDATE bank_stmt SET transaction_amount = $available_amt - '$amt',clr_status = CASE WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 ELSE clr_status END, update_login_id = '$user_id', updated_date = NOW() WHERE bank_id = '$from_bank' AND trans_id = '$trans_id'");

if ($qry && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submitting";
}

$connect = null;
?>
