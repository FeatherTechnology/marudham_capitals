<?php

session_start();
$user_id= $_SESSION['userid'];

include('../../../ajaxconfig.php');

$response = '';

$bank_id   = $_POST['bank_id'];
$username  = $_POST['username_exf'];
$usertype  = $_POST['usertype_exf'];
$trans_id  = $_POST['trans_id_exf'];
$remark    = $_POST['remark_exf'];
$sts    = $_POST['sts'];
$amt       = floatval(str_replace(",", "", $_POST['amt_exf']));
$op_date   = date('Y-m-d', strtotime($_POST['op_date']));

/* 🔍 CHECK AVAILABLE TRANSACTION AMOUNT */
$chk = $connect->query("
    SELECT transaction_amount 
    FROM bank_stmt 
    WHERE bank_id = '$bank_id' 
      AND trans_id = '$trans_id'
      AND $sts > 0
")->fetch(PDO::FETCH_ASSOC);

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

/* 🔁 GENERATE REFERENCE CODES */
$ref_code     = refcodes($connect);
$ucl_ref_code = uclrefcode($connect);

/* ✅ INSERT EXCHANGE ENTRY */
$qry = $connect->query("
    INSERT INTO ct_db_exf
    (username, usertype, bank_id, ucl_ref_code, ref_code, trans_id, remark, amt, insert_login_id, created_date)
    VALUES
    ('$username', '$usertype', '$bank_id', '$ucl_ref_code', '$ref_code', '$trans_id', '$remark', '$amt', '$user_id', '$op_date')
");

/* ✅ UPDATE BANK STATEMENT */
$upqry = $connect->query("
    UPDATE bank_stmt 
    SET 
        transaction_amount = $available_amt - '$amt',
        clr_status = CASE 
                        WHEN ROUND($available_amt - '$amt', 2) = 0 THEN 1 
                        ELSE clr_status 
                     END,
        update_login_id = '$user_id',
        updated_date = NOW()
    WHERE bank_id = '$bank_id'
      AND trans_id = '$trans_id'
");

if ($qry && $upqry) {
    echo "Submitted Successfully";
} else {
    echo "Error While Submitting";
}

$connect = null;


/* ================= REFERENCE CODE FUNCTIONS ================= */

function refcodes($connect){
    //////////////////////// To get Exchange reference Code once again /////////////////////////
    $myStr = "EXS";
    $selectIC = $connect->query("SELECT ref_code FROM ct_db_exf WHERE ref_code != ''");

    if ($selectIC->rowCount() > 0) {
        $row = $connect->query("
            SELECT ref_code 
            FROM ct_db_exf 
            WHERE ref_code != '' 
            ORDER BY id DESC 
            LIMIT 1
        ")->fetch();

        $lastNo = ltrim(strstr($row['ref_code'], '-'), '-');
        $ref_code = $myStr . "-" . ($lastNo + 1);
    } else {
        $ref_code = $myStr . "-100001";
    }
    return $ref_code;
}

function uclrefcode($connect){
    $myStr = "UCL";
    $selectIC = $connect->query("SELECT ref_code FROM ct_db_exf WHERE ref_code != ''");

    if ($selectIC->rowCount() > 0) {
        $row = $connect->query("
            SELECT ref_code 
            FROM ct_db_exf 
            WHERE ref_code != '' 
            ORDER BY id DESC 
            LIMIT 1
        ")->fetch();

        $lastNo = ltrim(strstr($row['ref_code'], '-'), '-');
        $ucl_ref_code = $myStr . "-" . ($lastNo + 1);
    } else {
        $ucl_ref_code = $myStr . "-100001";
    }
    return $ucl_ref_code;
}
?>
