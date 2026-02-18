<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$username = $_POST['username'];
$usertype = $_POST['usertype'];
$bank_id  = $_POST['bank_id'];
$cat      = $_POST['cat'];
$part     = $_POST['part'];
$vou_id   = $_POST['vou_id'];
$trans_id = $_POST['trans_id'];
$rec_per  = $_POST['rec_per'];
$remark   = $_POST['remark'] ?? '';
$sts      = $_POST['sts'];
$amt      = floatval(str_replace(",", "", $_POST['amt']));
$trans_date = date('Y-m-d', strtotime($_POST['trans_date']));

try {

    $connect->beginTransaction();

    /* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
    $chkStmt = $connect->prepare("SELECT transaction_amount, id  FROM bank_stmt WHERE bank_id = :bank_id AND trans_id = :trans_id AND $sts > 0 LIMIT 1");

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

    /* 📎 FILE UPLOAD (UNCHANGED LOGIC) */
    if (isset($_FILES['upd']) && $_FILES['upd']['error'] == 0) {

        $pic_temp = $_FILES['upd']['tmp_name'];
        $fileExtension = pathinfo($_FILES['upd']['name'], PATHINFO_EXTENSION);
        $upd = uniqid() . '.' . $fileExtension;

        while (file_exists("../../../uploads/expenseBill/" . $upd)) {
            $upd = uniqid() . '.' . $fileExtension;
        }

        move_uploaded_file($pic_temp, "../../../uploads/expenseBill/" . $upd);

    } else {
        $upd = '';
    }

    /* 🔁 GENERATE EXPENSE REFERENCE CODE */
    $myStr = "EXP";

    $codeStmt = $connect->prepare("
        SELECT ref_code 
        FROM ct_db_bexpense 
        WHERE ref_code != '' 
        ORDER BY id DESC 
        LIMIT 1
    ");

    $codeStmt->execute();
    $row = $codeStmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $ac2 = $row["ref_code"];
        $appno2 = ltrim(strstr($ac2, '-'), '-');
        $ref_code = $myStr . "-" . ($appno2 + 1);
    } else {
        $ref_code = $myStr . "-100001";
    }

    /* ✅ INSERT BANK EXPENSE */
    $insertStmt = $connect->prepare("
        INSERT INTO ct_db_bexpense
        (username, usertype, ref_code, bank_id, cat, part, vou_id, trans_id, rec_per, remark, amt, upload, insert_login_id, created_date)
        VALUES
        (:username, :usertype, :ref_code, :bank_id, :cat, :part, :vou_id, :trans_id, :rec_per, :remark, :amt, :upload, :user_id, :created_date)
    ");

    $insertStmt->execute([
        ':username' => $username,
        ':usertype' => $usertype,
        ':ref_code' => $ref_code,
        ':bank_id' => $bank_id,
        ':cat' => $cat,
        ':part' => $part,
        ':vou_id' => $vou_id,
        ':trans_id' => $trans_id,
        ':rec_per' => $rec_per,
        ':remark' => $remark,
        ':amt' => $amt,
        ':upload' => $upd,
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
        ':bank_id' => $bank_id,
        ':trans_id' => $trans_id
    ]);

        /* ✅ INSERT CLEARED HISTORY */
    $historyStmt = $connect->prepare("INSERT INTO cleared_bank_stmt_history
        (bank_stmt_id, transaction_balance, screens, insert_login_id, created_date)
        VALUES
        (:bank_stmt_id, :transaction_balance, 'Bank Expense', :user_id, NOW()) ");

    $historyStmt->execute([
        ':bank_stmt_id' => $bank_stmt_id,
        ':transaction_balance' => $transaction_balance,
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
