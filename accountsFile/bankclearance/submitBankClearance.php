<?php
session_start();
$user_id = $_SESSION['userid'];

include("../../ajaxconfig.php");

/* =============================== INPUTS ================================ */
$bank_id    = $_POST['bank_name'] ?? '';
$bank_name  = $_POST['bank_short_name'] ?? ''; // must send from frontend
$trans_date = $_POST['trans_date'] ?? '';
$trans_time = $_POST['trans_time'] ?? '';
$narration  = trim($_POST['narration'] ?? '');
$crdb       = $_POST['crdb'] ?? '';
$amt        = floatval($_POST['amt'] ?? 0);
$excel_balance = floatval($_POST['bal'] ?? 0);

$credit = 0;
$debit  = 0;

if ($crdb == 1) $credit = $amt;
if ($crdb == 2) $debit  = $amt;

if ($credit <= 0 && $debit <= 0) {
    echo json_encode(['status'=>'error','message'=>'Invalid amount']);
    exit;
}

/* =============================== DATE & TIME HANDLING ================================ */
$dt = new DateTime($trans_date, new DateTimeZone('Asia/Kolkata'));

$trans_date_only    = $dt->format('Y-m-d');
$trans_date_for_id  = $dt->format('dmY');

/* ===============================  GET LAST RUNNING BALANCE ================================ */
$lastBalQry = $connect->query(" SELECT balance  FROM bank_stmt  WHERE bank_id = '$bank_id' ORDER BY id DESC LIMIT 1");

$running_balance = 0;
if ($row = $lastBalQry->fetch(PDO::FETCH_ASSOC)) {
    $running_balance = floatval($row['balance']);
}

/* =============================== BALANCE VALIDATION ================================ */
$expected_balance = $running_balance + $credit - $debit;

if (round($expected_balance, 2) != round($excel_balance, 2)) {
    echo json_encode([
        'status' => 'balance_mismatch',
        'message' => "Balance mismatch. Expected $expected_balance but got $excel_balance"
    ]);
    exit;
}

/* =============================== TRANSACTION TYPE & AUTO ID ================================ */
$type = ($credit > 0) ? 'CR' : 'DB';

$runQry = $connect->query(" SELECT MAX(CAST(SUBSTRING_INDEX(trans_id, '-', -1) AS UNSIGNED)) AS last_no
    FROM bank_stmt
    WHERE bank_id = '$bank_id' AND DATE(trans_date) = '$trans_date_only' AND trans_id LIKE '{$bank_name}{$type}-%' ");

$last_no = $runQry->fetch(PDO::FETCH_ASSOC)['last_no'] ?? 0;
$run_no  = str_pad($last_no + 1, 3, '0', STR_PAD_LEFT);

$auto_trans_id = $bank_name.$type.'-'.$trans_date_for_id.'-'.$run_no;
$transaction_amount = ($credit > 0) ? $credit : $debit;
$trans_datetime = $trans_date_only.' '.$trans_time;

/* =============================== INSERT DATA ================================ */
$insertStmt = $connect->prepare("
    INSERT INTO bank_stmt ( bank_id, trans_date, narration, trans_id, credit, debit, balance, transaction_amount, insert_login_id, created_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()) ");

    $insertStmt->execute([
        $bank_id,
        $trans_datetime,
        $narration,
        $auto_trans_id,
        $credit,
        $debit,
        $excel_balance,
        $transaction_amount,
        $user_id
    ]);

/* =============================== RESPONSE ================================ */
if ($insertStmt) {
    echo json_encode(['status'=>'success','message'=>'Transaction added successfully']);
} else {
    echo json_encode(['status'=>'error','message'=>'Insert failed']);
}

$connect = null;
?>
