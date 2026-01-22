<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$from_acc_id = $_POST['from_acc_id_bex'];
$from_acc    = $_POST['from_acc_bex'];
$to_bank_id  = $_POST['to_bank_bex'];
$to_user_id  = $_POST['user_id_bex'];
$trans_id    = $_POST['trans_id_bex'];
$remark      = $_POST['remark_bex'];
$sts      = $_POST['sts'];
$amt         = floatval($_POST['amt_bex']);
$op_date     = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
$chk = $connect->query(" SELECT transaction_amount FROM bank_stmt WHERE bank_id = '$from_acc_id' AND trans_id = '$trans_id' AND $sts > 0 ")->fetch(PDO::FETCH_ASSOC);

if (!$chk) {
    echo "Invalid Transaction Id";
    exit;
}

$available_amt = floatval($chk['transaction_amount']);

/* ❌ ENTERED AMOUNT IS MORE */
if ($amt > $available_amt) {
    echo "Transaction Amount Mismatched";
    exit;
}

//////////////////////// GENERATE EXCHANGE REFERENCE CODE ////////////////////////
$myStr = "EXD";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bexchange WHERE ref_code != ''");

if ($selectIC->rowCount() > 0) {
    $codeAvailable = $connect->query(" SELECT ref_code  FROM ct_db_bexchange WHERE ref_code != '' ORDER BY id DESC LIMIT 1 ");
    $row = $codeAvailable->fetch();
    $ac2 = $row["ref_code"];
    $appno2 = ltrim(strstr($ac2, '-'), '-');
    $appno2 = $appno2 + 1;
    $ref_code = $myStr . "-" . $appno2;
} else {
    $ref_code = $myStr . "-100001";
}
//////////////////////////////////////////////////////////////////////////////////

/* ✅ INSERT EXCHANGE ENTRY */
$qry = $connect->query("
    INSERT INTO ct_db_bexchange (ref_code, from_acc_id, to_bank_id, to_user_id, trans_id, remark, amt, insert_login_id, created_date)
    VALUES
    ('$ref_code','$from_acc_id','$to_bank_id','$to_user_id','$trans_id','$remark','$amt','$user_id','$op_date') ");

/* ✅ UPDATE BANK STATEMENT (SUBTRACT AMOUNT + CLR STATUS) */
$upqry = $connect->query(" UPDATE bank_stmt SET transaction_amount = $available_amt - '$amt',clr_status = CASE WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 ELSE clr_status END, update_login_id = '$user_id', updated_date = NOW() WHERE bank_id = '$from_acc_id' AND trans_id = '$trans_id'");

if ($qry && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submitting";
}

$connect = null;
?>
