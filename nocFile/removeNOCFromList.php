<?php
session_start();
include('../ajaxconfig.php');

if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

// Fetch ALL req_id for this customer
$qry = "SELECT req_id
        FROM noc 
        WHERE cus_id = '$cus_id' 
          AND cus_status = 22";

$res = $connect->query($qry);
$reqRows = $res->fetchAll();

$response = '';

if (!empty($reqRows)) {

    foreach ($reqRows as $row) {

        $req_id = $row['req_id'];

        // Update all related tables for this req_id
        $connect->query("
            UPDATE request_creation 
            SET cus_status = 23, update_login_id = $userid, updated_date = now() 
            WHERE cus_id = '$cus_id' AND req_id = '$req_id'
        ");

        $connect->query("
            UPDATE customer_register 
            SET cus_status = 23 
            WHERE cus_id = '$cus_id' AND req_ref_id = '$req_id'
        ");

        $connect->query("
            UPDATE in_verification 
            SET cus_status = 23, update_login_id = $userid 
            WHERE cus_id = '$cus_id' AND req_id = '$req_id'
        ");

        $connect->query("
            UPDATE in_approval 
            SET cus_status = 23, update_login_id = $userid 
            WHERE cus_id = '$cus_id' AND req_id = '$req_id'
        ");

        $connect->query("
            UPDATE in_acknowledgement 
            SET cus_status = 23, update_login_id = $userid 
            WHERE cus_id = '$cus_id' AND req_id = '$req_id'
        ");

        $connect->query("
            UPDATE in_issue 
            SET cus_status = 23, update_login_id = $userid 
            WHERE cus_id = '$cus_id' AND req_id = '$req_id'
        ");

        $connect->query("
            UPDATE closed_status 
            SET cus_sts = 23, update_login_id = $userid, updated_date = now() 
            WHERE req_id = '$req_id' AND cus_id = '$cus_id'
        ");

        $lastUpdate = $connect->query("
            UPDATE noc 
            SET cus_status = 23, update_login_id = $userid, updated_date = now() 
            WHERE req_id = '$req_id' AND cus_id = '$cus_id'
        ");
    }
}

if ($lastUpdate) {
    $response = 'Successfully Removed';
} else {
    $response = 'Error While Removing from NOC';
}

echo json_encode($response);

// Close db connection
$connect = null;
?>
