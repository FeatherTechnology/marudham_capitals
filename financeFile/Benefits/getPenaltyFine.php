<?php
include('../../ajaxconfig.php');


$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';



if ($type == 'today') {
    $where = " DATE(created_date) = CURRENT_DATE ";

    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based

    getDetials($connect, $where);
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(created_date) >= DATE('$from_date') && DATE(created_date) <= DATE('$to_date')) ";
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based

    getDetials($connect, $where);
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " (MONTH(created_date) = '$month' && YEAR(created_date) = '$year') ";
    if ($user_id != '') {
        $where .= " && insert_login_id = '" . $user_id . "' ";
    } //for user based

    getDetials($connect, $where);
}


function getDetials($connect, $where)
{


    // to get overall penalty paid till now to show pending penalty amount //,SUM(penalty_waiver) as penalty_waiver
    $result = $connect->query("SELECT SUM(penalty_track) as penalty FROM `collection` WHERE $where ");
    $row = $result->fetch();
    if ($row['penalty'] == null) {
        $row['penalty'] = 0;
    }
    $response['penalty'] = $row['penalty']; // - $row['penalty_waiver'];


    //To get the collection charges //,SUM(coll_charge_waiver) as coll_charge_waiver
    $result = $connect->query("SELECT SUM(coll_charge_track) as coll_charge_track FROM `collection` WHERE $where ");
    $row = $result->fetch();
    $coll_charge_track = $row['coll_charge_track'] ?? 0;
    $response['fine'] = $coll_charge_track; // - $coll_charge_waiver;



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
