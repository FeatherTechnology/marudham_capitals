<?php
// session_start();
// $user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

$records = array();


if ($type == 'today') {

    $where = " date(ct1.cl_date) <= CURRENT_DATE() ";
    $where2 = " date(ct2.cl_date) <= CURRENT_DATE() ";

    if ($user_id != '') {
        $where .= " and ct1.insert_login_id = $user_id ";
    } //for user based

    getDetails($connect, $where, $where2);
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " date(ct1.cl_date) < DATE('$from_date') ";
    $where2 = " date(ct2.cl_date) <= DATE('$from_date') ";

    if ($user_id != '') {
        $where .= " and ct1.insert_login_id = $user_id ";
    } //for user based

    getDetails($connect, $where, $where2);
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " (month(ct1.cl_date) = $month && YEAR(ct1.cl_date) = '$year' ) ";
    $where2 = " (month(ct2.cl_date) = $month && YEAR(ct2.cl_date) = '$year' ) ";
    if ($user_id != '') {
        $where .= " and ct1.insert_login_id = $user_id ";
    } //for user based

    getDetails($connect, $where, $where2);
}

function getDetails($connect, $where, $where2)
{

    $records['closing_bal'] = 0;
//ct1.insert_login_id, ct1.cl_date AS last_entered_date, 
    $qry = $connect->query("SELECT ct1.closing_bal
    FROM cash_tally ct1
    WHERE $where and NOT EXISTS (
        SELECT 1
        FROM cash_tally ct2
        WHERE ct1.insert_login_id = ct2.insert_login_id 
    AND ct1.cl_date < ct2.cl_date and $where2 ) "); // then fetch the last updated date

    if ($qry->rowCount() != 0) {

        while ($row = $qry->fetch()) {

            $records['closing_bal'] += intVal($row['closing_bal']);
        }
    } else {
        $records['closing_bal'] = 0;
    }

    $records['closing_bal'] = moneyFormatIndia($records['closing_bal']);

    echo json_encode($records);
}


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
