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
$ref_code = $_POST['ref_code_cd'] ?? '';
$trans_id = $_POST['trans_id_cd'];
$remark   = $_POST['remark_cd'] ?? '';
$sts      = $_POST['sts'];
$trans_date  = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

    $chkStmt = $connect->prepare("  SELECT transaction_amount, id FROM bank_stmt WHERE bank_id = :bank_id  AND trans_id = :trans_id  AND $sts > 0 LIMIT 1 ");

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

    /* ❌ AMOUNT VALIDATION */
    if ($amt > $available_amt) {
        $connect->rollBack();
        exit("Transaction Amount Mismatched");
    }

    /* 🔁 CHECK ALREADY SUBMITTED */
    $checkStmt = $connect->prepare("SELECT created_date FROM ct_cr_cash_deposit WHERE db_ref_id = :bdep_id LIMIT 1 ");

    $checkStmt->execute([
        ':bdep_id' => $bdep_id
    ]);

    if ($checkStmt->rowCount() > 0) {
        $connect->rollBack();
        exit("Already Submitted");
    }

    /* ✅ INSERT CASH DEPOSIT */
    $insStmt = $connect->prepare(" INSERT INTO ct_cr_cash_deposit (db_ref_id, to_bank_id, location, amt, ref_code, trans_id, remark, insert_login_id, created_date)
        VALUES (:bdep_id, :bank_id, :location, :amt, :ref_code, :trans_id, :remark, :user_id, :created_date)");

    $insStmt->execute([
        ':bdep_id' => $bdep_id,
        ':bank_id' => $bank_id,
        ':location' => $location,
        ':amt' => $amt,
        ':ref_code' => $ref_code,
        ':trans_id' => $trans_id,
        ':remark' => $remark,
        ':user_id' => $user_id,
        ':created_date' => $trans_date
    ]);

    /* ✅ UPDATE BANK STATEMENT */
    $new_amount = $available_amt - $amt;

    $upStmt = $connect->prepare("
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

    $upStmt->execute([
        ':new_amount' => $new_amount,
        ':bank_id' => $bank_id,
        ':trans_id' => $trans_id
    ]);

        /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history (bank_stmt_id, transaction_amount, type, screens, insert_login_id, created_date)
        VALUES (:bank_stmt_id, :amt, 1,'Bank Cash Deposit', :user_id, NOW()) ");

    $historyStmt->execute([
        ':bank_stmt_id' => $bank_stmt_id,
        ':amt' => $amt,
        ':user_id' => $user_id
    ]);

    $connect->commit();
    echo "Submitted Successfully";

} catch (Exception $e) {

    $connect->rollBack();
    echo "Error While Submit";
}

$connect = null;
?>
