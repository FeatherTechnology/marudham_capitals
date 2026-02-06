<?php
include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

if ($type == 'today') {

    $where = " DATE(created_date) = CURRENT_DATE  ";

} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(created_date) >= DATE('" . $from_date . "') && DATE(created_date) <= DATE('" . $to_date . "')) ";

} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " MONTH(created_date) = '" . $month . "' && YEAR(created_date) = '" . $year . "'  ";

}

if ($user_id != '') {
    $where .= " && insert_login_id = '" . $user_id . "' ";
} //for user based

getDetails($connect, $where);

function getDetails($connect, $where)
{
    // Issued
  $qry = $connect->query("SELECT SUM(amt) AS amt FROM (
        SELECT netcash AS amt FROM ct_db_hissued WHERE $where
        UNION ALL
        SELECT COALESCE(cheque_value, 0) + COALESCE(transaction_value, 0) AS amt FROM loan_issue WHERE $where
    ) AS combined_table
");


    $row = $qry->fetch();
    $issued = $row['amt'] ?? 0;

    $qry = $connect->query("SELECT COALESCE(SUM(COALESCE(cash, 0) + COALESCE(cheque_value, 0) + COALESCE(transaction_value, 0)), 0) AS amt FROM loan_issue WHERE $where and (agent_id !='' or agent_id != null)");

    $row = $qry->fetch();
    $ag_issued = $row['amt'] ?? 0;

    $response['issued'] = (float)$issued + (float)$ag_issued;

    // Expense
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_db_hexpense WHERE $where
        UNION ALL
        SELECT amt FROM ct_db_bexpense WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $expense = $row['amt'] ?? 0;

    $response['expense'] = (float)$expense;

    $response['issued'] = moneyFormatIndia($response['issued']);
    $response['expense'] = moneyFormatIndia($response['expense']);

    echo json_encode($response);
}

// Close the database connection
$connect = null;
