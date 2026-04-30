<?php
include "../ajaxconfig.php";
session_start();

$req_id = $_POST['reqId'] ?? '';
$user_id = $_SESSION['userid'] ?? '';

$res = $connect->prepare("SELECT receive_status, receive_by FROM noc WHERE req_id = ? AND cus_status = 23");
$res->execute([$req_id]);
if($res->rowCount() > 0){
    $rec = $res->fetch();
    
    $receive_status  = $rec['receive_status'];   // 0 or 1
    $receive_by      = $rec['receive_by'];       // user_id of the person who received
    
    $response = ($receive_by == $user_id) ? 0 : 1; //0-same user and login, 1-Different user and login.
} else{
    $response = 1;
}

echo json_encode($response);

//close connection
$connect = null;
?>