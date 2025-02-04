<?php 
session_start();
include('../ajaxconfig.php');

if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
}
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_status'])){
    $cus_status = $_POST['cus_status'];
}

    $qry = $connect->query("UPDATE request_creation set cus_status = $cus_status, update_login_id = $userid WHERE  req_id = '".$req_id."' ") or die('Error on Request Table');
    $qry = $connect->query("UPDATE customer_register set cus_status = $cus_status WHERE req_ref_id = '".$req_id."' ")or die('Error on Customer Table');
    $qry = $connect->query("UPDATE in_verification set cus_status = $cus_status, update_login_id = $userid WHERE req_id = '".$req_id."' ")or die('Error on inVerification Table');
    $qry = $connect->query("UPDATE `in_approval` SET `cus_status`= $cus_status,`update_login_id`= $userid WHERE  req_id = '".$req_id."' ") or die('Error on in_approval Table');
    $qry = $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= $cus_status,`update_login_id`= $userid and updated_date=now() WHERE  req_id = '".$req_id."' ") or die('Error on in_acknowledgement Table');
    $qry = $connect->query("UPDATE `in_issue` SET `cus_status`= $cus_status, `update_login_id` = $userid where req_id = '".$req_id."' ") or die('Error on in_issue Table');


    if($qry){
        $response = 'Success';
    }else{
        $response = 'Error';
    }
    echo json_encode($response);

// Close the database connection
$connect = null;
?>