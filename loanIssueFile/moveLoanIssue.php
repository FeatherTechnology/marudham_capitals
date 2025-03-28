<?php
@session_start();
require '../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];

    $qry = $connect->query("UPDATE in_verification SET cus_status = 13, issue_by = 1, issue_mode = null, payment_type = null, bank_id = null, update_login_id = $userid WHERE req_id = '$req_id'");

    // Send response back to the client
    if ($qry) {
        echo json_encode(['status' => 'success', 'message' => 'Loan issued moved successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to move loan.']);
    }

    // Close the database connection
    $connect = null;
}
