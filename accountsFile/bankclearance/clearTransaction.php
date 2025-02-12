<?php
include("../../ajaxconfig.php");
session_start();
$user_id = $_SESSION['userid'];

$bank_stmt_id = $_POST['bank_stmt_id'];
$response = '';

$qry = $connect->query("UPDATE bank_stmt set clr_status = 1, update_login_id = $user_id, updated_date = now() where id='$bank_stmt_id' "); // 1 means cleared

if ($qry) {
    $response = 0;
} else {
    $response = 1;
}

echo $response;

// Close the database connection
$connect = null;
