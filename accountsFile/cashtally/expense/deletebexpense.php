<?php
include('../../../ajaxconfig.php');

$bexp_id = $_POST['bexp_id'];

/* 🔹 Get expense details */
$expQry = $connect->query(" SELECT amt, bank_id, trans_id, upload FROM ct_db_bexpense WHERE id = '$bexp_id' ");

$expData = $expQry->fetch();

if (!$expData) {
    echo "Expense record not found";
    exit;
}

$amt       = floatval($expData['amt']);
$bank_id   = $expData['bank_id'];
$trans_id  = $expData['trans_id'];
$upload    = $expData['upload'];

/* 🔹 Delete uploaded file */
if ($upload != '') {
    $filePath = '../../../uploads/expenseBill/' . $upload;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/* 🔹 Get current bank transaction value */
$bankQry = $connect->query("  SELECT transaction_amount 
    FROM bank_stmt WHERE bank_id = '$bank_id' AND trans_id = '$trans_id'");

$bankData = $bankQry->fetch();

if (!$bankData) {
    echo "Bank transaction not found";
    exit;
}

$currentValue = floatval($bankData['transaction_amount']);

/* 🔹 Add back expense amount */
$newValue = $currentValue + $amt;

/* 🔹 Determine clear status */
$clr_sts = ($newValue == 0) ? 1 : 0;

/* 🔹 Update bank statement */
$updateBank = $connect->query(" UPDATE bank_stmt SET transaction_amount = '$newValue', clr_status = '$clr_sts' WHERE bank_id = '$bank_id' AND trans_id = '$trans_id' ");

/* 🔹 Delete expense record */
$deleteExp = $connect->query("  DELETE FROM ct_db_bexpense   WHERE id = '$bexp_id'");

if ($updateBank && $deleteExp) {
    echo "Deleted Successfully";
} else {
    echo "Error While Deleting";
}

$connect = null;
?>