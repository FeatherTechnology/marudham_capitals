<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id   = $_POST['bank_id'];
$ref_code  = $_POST['ref_code'];
$cat_info  = $_POST['cat_info'];
$trans_id  = $_POST['trans_id'];
$remark    = $_POST['remark'] ?? '';
$amt       = floatval($_POST['amt']);
$sts       = $_POST['sts'];
$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

    /* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
    $chkStmt = $connect->prepare(" SELECT transaction_amount, id FROM bank_stmt WHERE bank_id = :bank_id AND trans_id = :trans_id AND $sts > 0 LIMIT 1 ");

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

    /* ❌ ENTERED AMOUNT IS MORE */
    if ($amt > $available_amt) {
        $connect->rollBack();
        exit("Transation Amount Mismatched");
    }

    /* ✅ INSERT INTO ct_cr_boti */
    $insertStmt = $connect->prepare(" INSERT INTO ct_cr_boti 
        (ref_code, to_bank_id, category, trans_id, remark, amt, insert_login_id, created_date)
        VALUES
        (:ref_code, :bank_id, :category, :trans_id, :remark, :amt, :user_id, :created_date)
    ");

    $insertStmt->execute([
        ':ref_code' => $ref_code,
        ':bank_id' => $bank_id,
        ':category' => $cat_info,
        ':trans_id' => $trans_id,
        ':remark' => $remark,
        ':amt' => $amt,
        ':user_id' => $user_id,
        ':created_date' => $trans_date
    ]);

    /* ✅ UPDATE BANK STATEMENT */
    $new_amount = $available_amt - $amt;

    $updateStmt = $connect->prepare("
        UPDATE bank_stmt 
        SET 
            transaction_amount = :new_amount,
            clr_status = CASE 
                            WHEN ROUND(:new_amount, 2) = 0 
                            THEN 1 
                            ELSE clr_status 
                         END
        WHERE bank_id = :bank_id 
        AND trans_id = :trans_id
    ");

    $updateStmt->execute([
        ':new_amount' => $new_amount,
        ':bank_id' => $bank_id,
        ':trans_id' => $trans_id
    ]);

         /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_amount, type, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :amt, 1, 'Bank Other Income', :user_id, NOW()) ");

    $historyStmt->execute([
        ':bank_stmt_id' => $bank_stmt_id,
        ':amt' => $amt,
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
