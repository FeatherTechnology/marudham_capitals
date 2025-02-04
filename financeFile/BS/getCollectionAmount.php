<?php

include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $where = " and insert_login_id = '" . $_POST['user_id'] . "' " : $where = ''; //for user based

if ($type == 'today') {
    $where = " DATE(coll_date) = CURRENT_DATE $where";
    $response = getCollectionRecord($connect, $where);
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $where = " (DATE(coll_date) >= '$from_date' && DATE(coll_date) <= '$to_date' ) $where ";
    $response = getCollectionRecord($connect, $where);
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));
    $where = " (MONTH(coll_date) = '$month' and YEAR(coll_date) = $year) $where";
    $response = getCollectionRecord($connect, $where);
}

$response = array_map(function ($num) {
    return number_format(intVal($num), 0, '', ',');
}, $response);

echo json_encode($response);


function getCollectionRecord($connect, $where)
{

    $response = array();

    $qry = $connect->query("SELECT SUM(due_amt_track) as due_amt_track,SUM(princ_amt_track) as princ_amt_track,SUM(int_amt_track) as int_amt_track,
    SUM(penalty_track) as penalty_track,SUM(coll_charge_track) as coll_charge_track 
    FROM collection where $where ");
    // $qry = $connect->query("SELECT SUM(amt) as total_collection from ( SELECT rec_amt as amt from ct_hand_collection where $where UNION ALL SELECT credited_amt as amt from ct_bank_collection where $where) as collection_amt ");


    if ($qry->rowCount() > 0) {
        $row = $qry->fetch();
        $response['collection'] = intVal($row['due_amt_track'] ?? 0)  + intVal($row['princ_amt_track'] ?? 0)  + intVal($row['int_amt_track'] ?? 0) + intVal($row['penalty_track'] ?? 0) + intVal($row['coll_charge_track'] ?? 0);
        // $response['collection'] = $row['total_collection'] ?? 0;
        $response['due_collection'] = $row['due_amt_track'] ?? 0;
        $response['princ_collection'] = $row['princ_amt_track'] ?? 0;
        $response['int_collection'] = $row['int_amt_track'] ?? 0;
        $response['penalty'] = $row['penalty_track'] ?? 0;
        $response['fine'] = $row['coll_charge_track'] ?? 0;
    }

    return $response;
}

// Close the database connection
$connect = null;
