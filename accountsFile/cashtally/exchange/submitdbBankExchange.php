<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$from_acc_id = $_POST['from_acc_id_bex'];
$from_acc    = $_POST['from_acc_bex']; // (kept as is, not used in query)
$to_bank_id  = $_POST['to_bank_bex'];
$to_user_id  = $_POST['user_id_bex'];
$trans_id    = $_POST['trans_id_bex'];
$remark      = $_POST['remark_bex'] ?? '';
$sts         = $_POST['sts'];
$amt         = floatval($_POST['amt_bex']);
$trans_date  = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

    $chkStmt = $connect->prepare(" SELECT transaction_amount, id FROM bank_stmt WHERE bank_id = :bank_id AND trans_id = :trans_id AND $sts > 0 LIMIT 1");

    $chkStmt->execute([
        ':bank_id' => $from_acc_id,
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

    /* ❌ ENTERED AMOUNT IS MORE */
    if ($amt > $available_amt) {
        $connect->rollBack();
        exit("Transaction Amount Mismatched");
    }

    //////////////////////// GENERATE EXCHANGE REFERENCE CODE ////////////////////////

    $myStr = "EXD";

    $selectIC = $connect->prepare("
        SELECT ref_code 
        FROM ct_db_bexchange 
        WHERE ref_code != '' 
        ORDER BY id DESC 
        LIMIT 1
    ");

    $selectIC->execute();
    $lastRow = $selectIC->fetch(PDO::FETCH_ASSOC);

    if ($lastRow) {

        $ac2 = $lastRow["ref_code"];
        $appno2 = ltrim(strstr($ac2, '-'), '-');
        $appno2 = $appno2 + 1;
        $ref_code = $myStr . "-" . $appno2;

    } else {

        $ref_code = $myStr . "-100001";
    }

    /////////////////////////////////////////////////////////////////////////////////

    /* ✅ INSERT EXCHANGE ENTRY */
    $insertStmt = $connect->prepare("
        INSERT INTO ct_db_bexchange 
        (ref_code, from_acc_id, to_bank_id, to_user_id, trans_id, remark, amt, insert_login_id, created_date)
        VALUES
        (:ref_code, :from_acc_id, :to_bank_id, :to_user_id, :trans_id, :remark, :amt, :user_id, :created_date)
    ");

    $insertStmt->execute([
        ':ref_code' => $ref_code,
        ':from_acc_id' => $from_acc_id,
        ':to_bank_id' => $to_bank_id,
        ':to_user_id' => $to_user_id,
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
                         END,
            update_login_id = :user_id,
            updated_date = NOW()
        WHERE bank_id = :bank_id 
        AND trans_id = :trans_id
    ");

    $updateStmt->execute([
        ':new_amount' => $new_amount,
        ':user_id' => $user_id,
        ':bank_id' => $from_acc_id,
        ':trans_id' => $trans_id
    ]);

    /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_balance, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :transaction_balance, 'Bank Exchange DB', :user_id, NOW()) ");

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
