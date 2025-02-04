<?php
session_start();
include('../ajaxconfig.php');
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}


$selectIC = $connect->query("UPDATE request_creation set cus_status = 2,updated_date = now(), update_login_id = $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on Request Table');
$selectIC = $connect->query("UPDATE customer_register set cus_status = 2,updated_date = now() WHERE req_ref_id = '" . $req_id . "' ") or die('Error on Customer Table');
$selectIC = $connect->query("UPDATE in_verification set cus_status = 2,updated_date = now(), update_login_id = $userid WHERE req_id = '" . $req_id . "' ") or die('Error on inVerification Table');
$selectIC = $connect->query("INSERT INTO in_approval (`req_id`, `cus_id`, `cus_status`, `status`,`insert_login_id` )
        SELECT req_id,cus_id,cus_status,status,update_login_id from in_verification where req_id = '" . $req_id . "' ");

$response = 'Moved to Approval';
echo json_encode($response);

// Close the database connection
$connect = null;