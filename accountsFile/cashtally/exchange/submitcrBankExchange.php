<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bex_id        = $_POST['bex_id'];
$from_bank_id  = $_POST['from_acc_id'];
$to_bank_id    = $_POST['to_bank_id'];
$to_user_id    = $_POST['to_user_id'];
$from_user_id  = $_POST['from_user_id'];
$ref_code      = $_POST['ref_code'] ?? '';
$trans_id      = $_POST['trans_id'];
$remark        = $_POST['remark'] ?? '';
$sts           = $_POST['sts'];
$amt           = floatval(str_replace(",", "", $_POST['amt']));
$trans_date    = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

    /* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
    $chkStmt = $connect->prepare("SELECT transaction_amount,id  FROM bank_stmt  WHERE bank_id = :bank_id  AND trans_id = :trans_id  AND $sts > 0 LIMIT 1");

    $chkStmt->execute([
        ':bank_id' => $to_bank_id,
        ':trans_id' => $trans_id
    ]);

    $chk = $chkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$chk) {
        $connect->rollBack();
        exit("Invalid Transaction Id");
    }

    $available_amt = floatval($chk['transaction_amount']);
    $bank_stmt_id  = $chk['id'];
    $transaction_balance = $available_amt - $amt;

    /* ❌ AMOUNT VALIDATION */
    if ($amt > $available_amt) {
        $connect->rollBack();
        exit("Transaction Amount Mismatched");
    }

    /* ✅ UPDATE DEBIT EXCHANGE (MARK AS RECEIVED) */
    $updateDebitStmt = $connect->prepare("
        UPDATE ct_db_bexchange 
        SET received = 0 
        WHERE id = :bex_id
    ");

    $updateDebitStmt->execute([
        ':bex_id' => $bex_id
    ]);

    /* ✅ INSERT CREDIT EXCHANGE ENTRY */
    $insertCreditStmt = $connect->prepare("
        INSERT INTO ct_cr_bexchange 
        (db_ref_id, from_bank_id, to_bank_id, from_user_id, to_user_id, ref_code, trans_id, remark, amt, insert_login_id, created_date)
        VALUES
        (:bex_id, :from_bank_id, :to_bank_id, :from_user_id, :to_user_id, :ref_code, :trans_id, :remark, :amt, :user_id, :created_date)
    ");

    $insertCreditStmt->execute([
        ':bex_id' => $bex_id,
        ':from_bank_id' => $from_bank_id,
        ':to_bank_id' => $to_bank_id,
        ':from_user_id' => $from_user_id,
        ':to_user_id' => $to_user_id,
        ':ref_code' => $ref_code,
        ':trans_id' => $trans_id,
        ':remark' => $remark,
        ':amt' => $amt,
        ':user_id' => $user_id,
        ':created_date' => $trans_date
    ]);

    /* ✅ UPDATE BANK STATEMENT */
    $new_amount = $available_amt - $amt;

    $updateBankStmt = $connect->prepare("
        UPDATE bank_stmt 
        SET 
            transaction_amount = :new_amount,
            clr_status = CASE 
                            WHEN ROUND(:new_amount, 2) = 0 
                            THEN 1 
                            ELSE clr_status 
                         END,
            update_login_id = :user_id,
            updated_date = NOW()
        WHERE bank_id = :bank_id 
        AND trans_id = :trans_id
    ");

    $updateBankStmt->execute([
        ':new_amount' => $new_amount,
        ':user_id' => $user_id,
        ':bank_id' => $to_bank_id,
        ':trans_id' => $trans_id
    ]);

        /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_balance, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :transaction_balance, 'Bank Exchange CR', :user_id, NOW()) ");

    $historyStmt->execute([
        ':bank_stmt_id' => $bank_stmt_id,
        ':transaction_balance' => $transaction_balance,
        ':user_id' => $user_id
    ]); 

    $connect->commit();
    echo "Submitted Successfully";

} catch (Exception $e) {

    $connect->rollBack();
    echo "Error While Submitting";
}

$connect = null;
?>
