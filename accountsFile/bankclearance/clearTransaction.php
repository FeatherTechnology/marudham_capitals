<?php
include("../../ajaxconfig.php");

$bank_stmt_id = $_POST['bank_stmt_id'];
$response = '';

$qry = $connect->query("UPDATE bank_stmt set clr_status = 1 where id='$bank_stmt_id' "); // 1 means cleared

if ($qry) {
    $response = 0;
} else {
    $response = 1;
}

echo $response;

// Close the database connection
$connect = null;
