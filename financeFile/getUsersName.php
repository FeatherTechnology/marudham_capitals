<?php
session_start();
$userid = $_SESSION['userid'];

include('../ajaxconfig.php');

$response = array();
$i = 0;
$qry = $connect->query("SELECT * FROM user where user_id != '1' and cash_tally = '0' ");
while ($row = $qry->fetch()) {
    $response[$i]['user_id'] = $row['user_id'];
    $response[$i]['username'] = $row['fullname'];
    $i++;
}

echo json_encode($response);

// Close the database connection
$connect = null;
