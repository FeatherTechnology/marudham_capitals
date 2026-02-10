<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$trans_id  = $_POST['trans_id'];
$from_bank = $_POST['from_bank'];
$cheque    = $_POST['cheque'] ?? '';
$remark    = $_POST['remark'] ?? '';
$sts       = $_POST['sts'];
$amt       = floatval(str_replace(",", "", $_POST['amt']));
$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

    $chkStmt = $connect->prepare(" SELECT transaction_amount, id  FROM bank_stmt  WHERE bank_id = :from_bank AND trans_id = :trans_id  AND $sts > 0  LIMIT 1");

    $chkStmt->execute([
        ':from_bank' => $from_bank,
        ':trans_id'  => $trans_id
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

    // GENERATE REF CODE
    $myStr = "WD";

    $selectStmt = $connect->query(" SELECT ref_code FROM ct_db_cash_withdraw WHERE ref_code != '' ORDER BY id DESC LIMIT 1 ");

    if ($selectStmt->rowCount() > 0) {

        $row = $selectStmt->fetch(PDO::FETCH_ASSOC);
        $ac2 = $row["ref_code"];

        $appno2 = ltrim(strstr($ac2, '-'), '-');
        $appno2 = $appno2 + 1;

        $ref_code = $myStr . "-" . $appno2;

    } else {

        $ref_code = $myStr . "-100001";
    }

    ////////////////////////

    /* ✅ INSERT CASH WITHDRAW */
    $insStmt = $connect->prepare(" INSERT INTO ct_db_cash_withdraw (from_bank_id, ref_code, trans_id, cheque_no, remark, amt, insert_login_id, created_date)
        VALUES (:from_bank, :ref_code, :trans_id, :cheque, :remark, :amt, :user_id, :created_date) ");

    $insStmt->execute([
        ':from_bank'   => $from_bank,
        ':ref_code'    => $ref_code,
        ':trans_id'    => $trans_id,
        ':cheque'      => $cheque,
        ':remark'      => $remark,
        ':amt'         => $amt,
        ':user_id'     => $user_id,
        ':created_date'=> $trans_date
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
        WHERE bank_id = :from_bank 
        AND trans_id = :trans_id
    ");

    $upStmt->execute([
        ':new_amount' => $new_amount,
        ':from_bank'  => $from_bank,
        ':trans_id'   => $trans_id
    ]);
       /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_amount, type, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :amt, 2, 'Bank Cash Withdrawal', :user_id, NOW()) ");

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
