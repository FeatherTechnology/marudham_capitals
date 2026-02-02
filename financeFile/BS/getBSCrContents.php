<?php
include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

if ($type == 'today') {

    $where = " DATE(created_date) = CURRENT_DATE ";

} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(created_date) >= DATE('" . $from_date . "') && DATE(created_date) <= DATE('" . $to_date . "')) ";

} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " MONTH(created_date) = '" . $month . "' && YEAR(created_date) = '" . $year . "' ";

}

if ($user_id != '') {
    $where .= " && insert_login_id = '" . $user_id . "' ";
} //for user based

getDetails($connect, $where);

function getDetails($connect, $where)
{
    // other income
    $qry = $connect->query("SELECT SUM(amt) as other_income FROM (
        SELECT amt FROM ct_cr_hoti WHERE $where
        UNION ALL
        SELECT amt FROM ct_cr_boti WHERE $where
    ) AS combined_table");

    $row = $qry->fetch();
    $other_income = $row['other_income'] ?? 0;

    $response['other_income'] = (float)$other_income;

    $response['other_income'] = moneyFormatIndia($response['other_income']);

    echo json_encode($response);
}

//Format number in Indian Format
function moneyFormatIndia($num)
{
    $isNegative = ($num < 0);
    $num = abs((string)$num);

    // Split integer & decimal part
    $decimal = '';
    if (strpos($num, '.') !== false) {
        [$num, $decimal] = explode('.', $num, 2);
        $decimal = '.' . substr($decimal, 0, 2);
    }

    if (strlen($num) > 3) {
        $lastthree = substr($num, -3);
        $restunits = substr($num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? '0' . $restunits : $restunits;

        $expunit = str_split($restunits, 2);
        $explrestunits = '';

        foreach ($expunit as $i => $value) {
            $explrestunits .= ($i == 0 ? (int)$value : $value) . ',';
        }

        $formatted = $explrestunits . $lastthree;
    } else {
        $formatted = $num;
    }

    return ($isNegative ? '-' : '') . $formatted . $decimal;
}

// Close the database connection
$connect = null;
