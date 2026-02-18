<?php

session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id   = $_POST['bank_id'];
$username  = $_POST['username_exf'];
$usertype  = $_POST['usertype_exf'];
$trans_id  = $_POST['trans_id_exf'];
$remark    = $_POST['remark_exf'] ?? '';
$sts       = $_POST['sts'];
$amt       = floatval(str_replace(",", "", $_POST['amt_exf']));
$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

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
    $transaction_balance = $available_amt - $amt;

    /* ❌ AMOUNT VALIDATION */
    if ($amt > $available_amt) {
        $connect->rollBack();
        exit("Transaction Amount Mismatched");
    }

    /* 🔁 GENERATE REFERENCE CODES (Same Logic) */
    $ref_code     = refcodes($connect);
    $ucl_ref_code = uclrefcode($connect);

    /* ✅ INSERT EXCHANGE ENTRY */
    $insStmt = $connect->prepare("
        INSERT INTO ct_db_exf
        (username, usertype, bank_id, ucl_ref_code, ref_code, trans_id, remark, amt, insert_login_id, created_date)
        VALUES
        (:username, :usertype, :bank_id, :ucl_ref_code, :ref_code, :trans_id, :remark, :amt, :user_id, :created_date)
    ");

    $insStmt->execute([
        ':username' => $username,
        ':usertype' => $usertype,
        ':bank_id' => $bank_id,
        ':ucl_ref_code' => $ucl_ref_code,
        ':ref_code' => $ref_code,
        ':trans_id' => $trans_id,
        ':remark' => $remark,
        ':amt' => $amt,
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
                         END,
            update_login_id = :user_id,
            updated_date = NOW()
        WHERE bank_id = :bank_id
        AND trans_id = :trans_id
    ");

    $upStmt->execute([
        ':new_amount' => $new_amount,
        ':user_id' => $user_id,
        ':bank_id' => $bank_id,
        ':trans_id' => $trans_id
    ]);

        /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_balance, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :transaction_balance, 'Bank Excess Fund', :user_id, NOW()) ");

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


/* ================= REFERENCE CODE FUNCTIONS ================= */

function refcodes($connect){

    $myStr = "EXS";

    $stmt = $connect->query("
        SELECT ref_code 
        FROM ct_db_exf 
        WHERE ref_code != '' 
        ORDER BY id DESC 
        LIMIT 1
    ");

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastNo = ltrim(strstr($row['ref_code'], '-'), '-');
        return $myStr . "-" . ($lastNo + 1);
    } else {
        return $myStr . "-100001";
    }
}

function uclrefcode($connect){

    $myStr = "UCL";

    $stmt = $connect->query("
        SELECT ref_code 
        FROM ct_db_exf 
        WHERE ref_code != '' 
        ORDER BY id DESC 
        LIMIT 1
    ");

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastNo = ltrim(strstr($row['ref_code'], '-'), '-');
        return $myStr . "-" . ($lastNo + 1);
    } else {
        return $myStr . "-100001";
    }
}
?>
