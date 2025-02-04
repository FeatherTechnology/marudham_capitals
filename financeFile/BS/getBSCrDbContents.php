<?php


include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

if ($type == 'today') {

    $where = 'DATE(created_date) = CURRENT_DATE ';
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based
    getDetails($connect, $where); //passing where clause as arg

} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = '(DATE(created_date) >= DATE("' . $from_date . '") && DATE(created_date) <= DATE("' . $to_date . '"))  ';
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based
    getDetails($connect, $where); //passing where clause as arg


} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = 'MONTH(created_date) = "' . $month . '" && YEAR(created_date) = "' . $year . '"  ';
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based
    getDetails($connect, $where); //passing where clause as arg
}




function getDetails($connect, $where)
{

    // Investment Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_cr_binvest WHERE $where 
        UNION ALL
        SELECT amt FROM ct_cr_hinvest WHERE $where 
    ) AS combined_table");

    $row = $qry->fetch();
    $investment = $row['amt'] ?? 0;

    $response['cr_investment'] = intval($investment);

    // Investment Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_db_binvest WHERE $where
        UNION ALL
        SELECT amt FROM ct_db_hinvest WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $investment = $row['amt'] ?? 0;

    $response['db_investment'] = intval($investment);


    // Deposit Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_cr_bdeposit WHERE $where
        UNION ALL
        SELECT amt FROM ct_cr_hdeposit WHERE $where
        ) AS combined_table");

    $row = $qry->fetch();
    $deposit = $row['amt'] ?? 0;

    $response['cr_deposit'] = intval($deposit);

    // Deposit Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_db_bdeposit WHERE $where 
        UNION ALL
        SELECT amt FROM ct_db_hdeposit WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $deposit = $row['amt'] ?? 0;

    $response['db_deposit'] = intval($deposit);

    // EL Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_cr_bel WHERE $where
        UNION ALL
        SELECT amt FROM ct_cr_hel WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $el = $row['amt'] ?? 0;

    $response['cr_el'] = intval($el);

    // EL Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_db_bel WHERE $where
        UNION ALL
        SELECT amt FROM ct_db_hel WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $el = $row['amt'] ?? 0;

    $response['db_el'] = intval($el);

    // Exchange Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_cr_bexchange WHERE $where
        UNION ALL
        SELECT amt FROM ct_cr_hexchange WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $exchange = $row['amt'] ?? 0;

    $response['cr_exchange'] = intval($exchange);

    // Exchange Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_db_bexchange WHERE $where
        UNION ALL
        SELECT amt FROM ct_db_hexchange WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $exchange = $row['amt'] ?? 0;

    $response['db_exchange'] = intval($exchange);

    // Agent Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_cr_bag WHERE $where
        UNION ALL
        SELECT amt FROM ct_cr_hag WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $agent = $row['amt'] ?? 0;

    $response['cr_agent'] = intval($agent);

    // Agent Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM (
        SELECT amt FROM ct_db_bag WHERE $where
        UNION ALL
        SELECT amt FROM ct_db_hag WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $agent = $row['amt'] ?? 0;

    $response['db_agent'] = intval($agent);



    $response['cr_investment'] = moneyFormatIndia($response['cr_investment']);
    $response['db_investment'] = moneyFormatIndia($response['db_investment']);
    $response['cr_deposit'] = moneyFormatIndia($response['cr_deposit']);
    $response['db_deposit'] = moneyFormatIndia($response['db_deposit']);
    $response['cr_el'] = moneyFormatIndia($response['cr_el']);
    $response['db_el'] = moneyFormatIndia($response['db_el']);
    $response['cr_exchange'] = moneyFormatIndia($response['cr_exchange']);
    $response['db_exchange'] = moneyFormatIndia($response['db_exchange']);
    $response['cr_agent'] = moneyFormatIndia($response['cr_agent']);
    $response['db_agent'] = moneyFormatIndia($response['db_agent']);

    echo json_encode($response);
}

//Format number in Indian Format
function moneyFormatIndia($num)
{
    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $explrestunits = "";
    if (strlen((string)$num) > 3) {
        $lastthree = substr((string)$num, -3);
        $restunits = substr((string)$num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $index => $value) {
            if ($index == 0) {
                $explrestunits .= (int)$value . ",";
            } else {
                $explrestunits .= $value . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    return $isNegative ? "-" . $thecash : $thecash;
}

// Close the database connection
$connect = null;