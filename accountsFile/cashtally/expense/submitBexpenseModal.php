<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$response = '';

$username = $_POST['username'];
$usertype = $_POST['usertype'];
$bank_id  = $_POST['bank_id'];
$cat      = $_POST['cat'];
$part     = $_POST['part'];
$vou_id   = $_POST['vou_id'];
$trans_id = $_POST['trans_id'];
$rec_per  = $_POST['rec_per'];
$remark   = $_POST['remark'];
$sts   = $_POST['sts'];
$amt      = floatval(str_replace(",", "", $_POST['amt']));
$op_date  = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
$chk = $connect->query(" SELECT transaction_amount FROM bank_stmt WHERE bank_id = '$bank_id' AND trans_id = '$trans_id' AND $sts > 0")->fetch(PDO::FETCH_ASSOC);

if (!$chk) {
    echo "Invalid Transaction Id";
    exit;
}

$available_amt = floatval($chk['transaction_amount']);

/* ❌ AMOUNT VALIDATION */
if ($amt > $available_amt) {
    echo "Transaction Amount Mismatched";
    exit;
}

/* 📎 FILE UPLOAD (UNCHANGED LOGIC) */
if (isset($_FILES['upd'])) {
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
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bexpense WHERE ref_code != ''");

if ($selectIC->rowCount() > 0) {
    $codeAvailable = $connect->query(" SELECT ref_code FROM ct_db_bexpense  WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
    $row = $codeAvailable->fetch();
    $ac2 = $row["ref_code"];
    $appno2 = ltrim(strstr($ac2, '-'), '-');
    $ref_code = $myStr . "-" . ($appno2 + 1);
} else {
    $ref_code = $myStr . "-100001";
}

/* ✅ INSERT BANK EXPENSE */
$qry = $connect->query(" INSERT INTO ct_db_bexpense
    (username, usertype, ref_code, bank_id, cat, part, vou_id, trans_id, rec_per, remark, amt, upload, insert_login_id, created_date)
    VALUES
    ('$username','$usertype','$ref_code','$bank_id','$cat','$part','$vou_id','$trans_id','$rec_per','$remark','$amt','$upd','$user_id','$op_date')");

/* ✅ UPDATE BANK STATEMENT */
$upqry = $connect->query(" UPDATE bank_stmt SET transaction_amount = $available_amt - '$amt',clr_status = CASE WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 ELSE clr_status END, update_login_id = '$user_id', updated_date = NOW() WHERE bank_id = '$bank_id' AND trans_id = '$trans_id'");

if ($qry && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submit";
}

$connect = null;
?>
