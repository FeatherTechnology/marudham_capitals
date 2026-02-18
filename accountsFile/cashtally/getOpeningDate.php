<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$records = array();

// Get last cash tally entry
$qry = $connect->query("SELECT DATE(cl_date) AS last_date FROM cash_tally WHERE insert_login_id = '$user_id' ORDER BY cl_date DESC  LIMIT 1 ");

if ($qry->rowCount() > 0) {

    $row = $qry->fetch();
    $last_date = $row['last_date'];
    $today = date('Y-m-d');

    if ($last_date == $today) {
        // If last tally is today → send tomorrow
        $records['opening_date'] = date('d-m-Y', strtotime('+1 day'));
    } else {
        // If not today → send today
        $records['opening_date'] = date('d-m-Y');
    }

} else {
    // No entries at all → send today
    $records['opening_date'] = date('d-m-Y');
}

echo json_encode($records);

$connect = null;
?>
