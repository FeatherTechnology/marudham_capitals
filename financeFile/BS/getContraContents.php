<?php


include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';


if ($type == 'today') {

    $where = " DATE(created_date) = CURRENT_DATE ";
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based

    getDetails($connect, $where);
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(created_date) >= DATE('" . $from_date . "') && DATE(created_date) <= DATE('" . $to_date . "')) ";
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based

    getDetails($connect, $where);
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " MONTH(created_date) = '" . $month . "' && YEAR(created_date) = '" . $year . "' ";
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based

    getDetails($connect, $where);
}

function getDetails($connect, $where)
{

    // Bank Withdrawal
    $qry = $connect->query("SELECT SUM(amt) as bank_withdrawal FROM ct_cr_bank_withdraw WHERE $where ");

    $row = $qry->fetch();
    $bank_withdrawal = $row['bank_withdrawal'] ?? 0;


    // Cash Deposit
    $qry = $connect->query("SELECT SUM(amt) as cash_deposit FROM ct_cr_cash_deposit WHERE $where ");

    $row = $qry->fetch();
    $cash_deposit = $row['cash_deposit'] ?? 0;


    // Bank Deposit
    $qry = $connect->query("SELECT SUM(amount) as amt FROM ct_db_bank_deposit WHERE $where ");

    $row = $qry->fetch();
    $bank_deposit = $row['amt'] ?? 0;


    // Cash Withdrawal
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_cash_withdraw WHERE $where ");

    $row = $qry->fetch();
    $cash_withdrawal = $row['amt'] ?? 0;

    $response['credit_contra'] = intVal($bank_withdrawal) + intVal($cash_deposit);
    $response['debit_contra'] = +intVal($bank_deposit) + intVal($cash_withdrawal);

    $response['credit_contra'] = number_format($response['credit_contra'], 0, '', ',');
    $response['debit_contra'] = number_format($response['debit_contra'], 0, '', ',');

    echo json_encode($response);
}

// Close the database connection
$connect = null;
