<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_id'])) {
    $cus_id =  preg_replace('/\s+/', '', $_POST['cus_id']);
}
if (isset($_POST['loan_amt_cal'])) {
    $loan_amt_cal = $_POST['loan_amt_cal'];
}
if (isset($_POST['net_cash'])) {
    $net_cash_cal = $_POST['net_cash'];
}
if (isset($_POST['issue_to'])) {
    $issue_to =  $_POST['issue_to'];
}
if (isset($_POST['issued_mode'])) {
    $issued_mode = $_POST['issued_mode'];
}
if (isset($_POST['payment_type'])) {
    $payment_type = $_POST['payment_type'];
}
if (isset($_POST['cash'])) {
    $cash = $_POST['cash'];
}
if (isset($_POST['bank_id'])) {
    $bank_id = $_POST['bank_id'];
}
if (isset($_POST['chequeno'])) {
    $chequeno = $_POST['chequeno'];
}
if (isset($_POST['chequeValue'])) {
    $chequeValue = $_POST['chequeValue'];
}
if (isset($_POST['chequeRemark'])) {
    $chequeRemark = $_POST['chequeRemark'];
}
if (isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
}
if (isset($_POST['transaction_value'])) {
    $transaction_value = $_POST['transaction_value'];
}
if (isset($_POST['transaction_remark'])) {
    $transaction_remark = $_POST['transaction_remark'];
}
if (isset($_POST['balance'])) {
    $balance = $_POST['balance'];
}
if (isset($_POST['bank_id'])) {
    $bank_id = $_POST['bank_id'];
}
$qry = $connect->query("
    INSERT INTO loan_issue 
    (req_id, cus_id, issued_to, issued_mode, payment_type, bank_id, cheque_no, cheque_value, cheque_remark, transaction_id, transaction_value, transaction_remark, balance_amount, loan_amt, net_cash, status, insert_login_id, created_date)
    VALUES (
        '$req_id',
        '$cus_id',
        '$issue_to',
        '$issued_mode',
        '$payment_type',
        '$bank_id',
        '$chequeno',
        '$chequeValue',
        '$chequeRemark',
        '$transaction_id',
        '$transaction_value',
        '$transaction_remark',
        '$balance',
        '$loan_amt_cal',
        '$net_cash_cal',
        '0',
        '$userid',
        NOW()
    )
");

$current_date = date('Y-m-d');

//////////////////////////////////////////////////////////////////////////
if (isset($_POST['balance']) && $_POST['balance'] == '0') {
    try {
        // Begin transaction
        $connect->beginTransaction();

        // Update various tables for the completed loan issue
        $connect->query("UPDATE request_creation SET cus_status = 14, updated_date = NOW(), update_login_id = $userid WHERE req_id = '$req_id'") or die('Error on Request Table');
        $connect->query("UPDATE customer_register SET cus_status = 14 WHERE req_ref_id = '$req_id'") or die('Error on Customer Table');
        $connect->query("UPDATE in_verification SET cus_status = 14, update_login_id = $userid WHERE req_id = '$req_id'") or die('Error on inVerification Table');
        $connect->query("UPDATE in_approval SET cus_status = 14, update_login_id = $userid WHERE req_id = '$req_id'") or die('Error on in_approval Table');
        $connect->query("UPDATE in_acknowledgement SET cus_status = 14, update_login_id = $userid, updated_date = CURRENT_DATE WHERE req_id = '$req_id'") or die('Error on in_acknowledgement Table');
        $connect->query("UPDATE in_issue SET cus_status = 14, updated_date = NOW(), update_login_id = $userid WHERE req_id = '$req_id'") or die('Error on in_issue Table');

        $qry = $connect->query("SELECT cus_id_loan, loan_amt_cal, net_cash_cal, tot_amt_cal, due_amt_cal, due_start_from from acknowlegement_loan_calculation where req_id = $req_id ");
        $row = $qry->fetch();
        $tot_amt_cal = $row['tot_amt_cal'];
        $due_amt_cal = $row['due_amt_cal'];
        $cus_id = $row['cus_id_loan'];
        $dueStartDate = $row['due_start_from'];

        // Calculate the payable amount
        if (strtotime($dueStartDate) > strtotime($current_date)) {
            $cus_payable = '0';
        } else {
            $cus_payable = $due_amt_cal;
        }

        // Insert into customer_status
        $connect->query("INSERT INTO customer_status (req_id, cus_id, sub_status, payable_amnt, bal_amnt, insert_login_id, created_date) VALUES ('$req_id', '$cus_id', 'Current', '$cus_payable', '$tot_amt_cal', '$userid', '$current_date')");

        // Commit the transaction
        $connect->commit();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $connect->rollBack();
        $response = "Error: " . $e->getMessage();
        exit;
    }
}

$response = 'Loan Issue Completed';

// Return the response
echo json_encode(["response" => $response]);

// Close the database connection
$connect = null;
