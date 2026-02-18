<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id  = $_POST['bank_id'];
$ag_id    = $_POST['ag_id'];
$ref_code = $_POST['ref_code'] ?? '';
$trans_id = $_POST['trans_id'];
$remark   = $_POST['remark'] ?? '';
$sts      = $_POST['sts'] ?? 0;

$amt = floatval(str_replace(",", "", $_POST['amt']));
$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();
    
    $chkStmt = $connect->prepare("SELECT transaction_amount, id  FROM bank_stmt  WHERE bank_id = :bank_id  AND trans_id = :trans_id  AND $sts > 0 LIMIT 1 ");
    
    $chkStmt->execute([
        ':bank_id' => $bank_id,
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

    if ($amt > $available_amt) {
        $connect->rollBack();
        exit("Transaction Amount Mismatched");
    }

    /* ✅ INSERT AGENT CREDIT */
    $insStmt = $connect->prepare(" INSERT INTO ct_cr_bag 
        (ag_id, bank_id, ref_code, trans_id, remark, amt, insert_login_id, created_date)
        VALUES
        (:ag_id, :bank_id, :ref_code, :trans_id, :remark, :amt, :user_id, :created_date)
    ");

    $insStmt->execute([
        ':ag_id' => $ag_id,
        ':bank_id' => $bank_id,
        ':ref_code' => $ref_code,
        ':trans_id' => $trans_id,
        ':remark' => $remark,
        ':amt' => $amt,
        ':user_id' => $user_id,
        ':created_date' => $trans_date
    ]);

    /* ✅ UPDATE BANK STATEMENT */
    $upStmt = $connect->prepare("
        UPDATE bank_stmt 
        SET 
            transaction_amount = transaction_amount - :amt,
            clr_status = CASE 
                            WHEN ROUND(transaction_amount - :amt, 2) = 0 
                            THEN 1 
                            ELSE clr_status 
                         END,
            update_login_id = :user_id,
            updated_date = NOW()
        WHERE bank_id = :bank_id 
        AND trans_id = :trans_id
    ");

    $upStmt->execute([
        ':amt' => $amt,
        ':user_id' => $user_id,
        ':bank_id' => $bank_id,
        ':trans_id' => $trans_id
    ]);

    /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_balance, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :transaction_balance, 'Bank Agent CR', :user_id, NOW()) ");

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
