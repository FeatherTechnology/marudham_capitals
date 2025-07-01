<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');
include './openingDateClass.php';

$CBObj = new OpeningDateClass($connect);
$records = array();

$qry1 = $connect->query("SELECT created_date, closing_bal FROM cash_tally WHERE insert_login_id = '$user_id' AND DATE(cl_date) = CURRENT_DATE()");

if ($qry1->rowCount() == 0) {
    // Today's cash tally is not done
    $qry = $connect->query("SELECT DATE(cl_date) AS closing_date, closing_bal FROM cash_tally WHERE insert_login_id = '$user_id' AND DATE(cl_date) < CURRENT_DATE() ORDER BY cl_date DESC LIMIT 1");

    if ($qry->rowCount() != 0) {
        $row = $qry->fetch();
        $records['opening_date'] = date('d-m-Y', strtotime($row['closing_date'] . ' +1 day'));
        $records['closing_bal'] = $row['closing_bal'];
    } else {
        // No cash tally entries at all - check latest transaction
        $latestTxnDate = $CBObj->getOpeningDate($user_id);
        if (!empty($latestTxnDate)) {
            $records['opening_date'] = date('d-m-Y', strtotime($latestTxnDate));
            $records['closing_bal'] = 0;
        } else {
            $records['opening_date'] = date('d-m-Y');
            $records['closing_bal'] = 0;
        }
    }
} else {
    // Today's tally is done
    $row1 = $qry1->fetch();
    $records['opening_date'] = date('d-m-Y', strtotime(' +1 day'));
    $records['closing_bal'] = $row1['closing_bal'];
}

echo json_encode($records);

// Close DB connection
$connect = null;
