<?php
session_start();
include('../ajaxconfig.php');

if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}

$userid = $_SESSION['userid'];

// Fetch ALL req_id + receive_status for this customer
$qry = "SELECT req_id, receive_status
        FROM noc 
        WHERE cus_id = '$cus_id' AND cus_status = 23";

$res = $connect->query($qry);
$reqRows = $res->fetchAll(PDO::FETCH_ASSOC);

$response = '';

if (!empty($reqRows)) {

    // Check if ANY row is already received
    foreach($reqRows as $row){
        if ($row['receive_status'] == 1) {
            echo json_encode("Already Received");
            $connect = null;
            exit;
        }
    }

    // If not received → Update all rows
    foreach($reqRows as $row){
        $req_id = $row['req_id'];

        $update = $connect->query("
            UPDATE noc 
            SET receive_status = 1,
                receive_by = '$userid',
                update_login_id = '$userid',
                updated_date = now()
            WHERE req_id = '$req_id'
        ");
    }

    $response = "Successfully Received";

} else {
    $response = "No matching NOC found";
}

echo json_encode($response);
$connect = null;
?>
