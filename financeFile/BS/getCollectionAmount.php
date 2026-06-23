<?php
include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $where = " and insert_login_id = '" . $_POST['user_id'] . "' " : $where = ''; //for user based

if ($type == 'today') {
    $where = " DATE(coll_date) = CURRENT_DATE $where";
    
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $where = " (DATE(coll_date) >= '$from_date' && DATE(coll_date) <= '$to_date' ) $where ";
   
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));
    $where = " (MONTH(coll_date) = '$month' and YEAR(coll_date) = $year) $where";
    
}

$response = getCollectionRecord($connect, $where);

echo json_encode($response);

function getCollectionRecord($connect, $where)
{
    $response = array();
    $qry = $connect->query("SELECT SUM(due_amt_track) as due_amt_track, SUM(penalty_track) as penalty_track, SUM(coll_charge_track) as coll_charge_track, SUM(pre_close_waiver) as pre_close_waiver FROM collection WHERE $where ");

    if ($qry->rowCount() > 0) {
        $row = $qry->fetch();
        $response['due_collection'] = $row['due_amt_track'] ?? 0;
        $response['penalty'] = $row['penalty_track'] ?? 0;
        $response['fine'] = $row['coll_charge_track'] ?? 0;
        $response['pre_close_waiver'] = $row['pre_close_waiver'] ?? 0;
    }
    return $response;
}

// Close the database connection
$connect = null;
