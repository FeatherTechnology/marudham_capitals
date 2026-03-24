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
    // foreach($reqRows as $row){
    //     if ($row['receive_status'] == 1) {
    //         echo json_encode("Already Received");
    //         $connect = null;
    //         exit;
    //     }
    // }

    // If not received → Update all rows
    foreach($reqRows as $row){
        $req_id = $row['req_id'];

        // Update noc
        $stmt = $connect->prepare(
            "UPDATE noc 
            SET receive_status = ?,
                receive_by = ?,
                update_login_id = ?,
                updated_date = NOW()
            WHERE req_id = ?"
        );
        $stmt->execute([1, $userid, $userid, $req_id]);

        // Insert into noc_receive_user
        $stmt = $connect->prepare("INSERT INTO noc_receive_user(req_id, cus_id, received_user, created_date) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$req_id, $cus_id, $userid]);
    }

    $response = "Successfully Received";

} else {
    $response = "No matching NOC found";
}

echo json_encode($response);
$connect = null;
?>
