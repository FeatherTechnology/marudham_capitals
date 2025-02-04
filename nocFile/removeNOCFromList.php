<?php
session_start();
include('../ajaxconfig.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
} 
if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

//NOC may completed, now remove = 22.

    $selectIC = $connect->query("UPDATE request_creation set cus_status = 22, update_login_id = $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' ") or die('Error on Request Table');
    $selectIC = $connect->query("UPDATE customer_register set cus_status = 22 WHERE cus_id = '".$cus_id."' and req_ref_id = '".$req_id."' ")or die('Error on Customer Table');
    $selectIC = $connect->query("UPDATE in_verification set cus_status = 22, update_login_id = $userid WHERE cus_id = '".$cus_id."' and req_id = '".$req_id."' ")or die('Error on inVerification Table');
    $selectIC = $connect->query("UPDATE `in_approval` SET `cus_status`= 22,`update_login_id`= $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' ") or die('Error on in_approval Table');
    $selectIC = $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 22,`update_login_id`= $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' and updated_date=now() ") or die('Error on in_acknowledgement Table');
    $selectIC = $connect->query("UPDATE `in_issue` SET `cus_status`= 22,`update_login_id` = $userid where cus_id = '".$cus_id."' and req_id = '".$req_id."' ") or die('Error on in_issue Table');
    $selectIC = $connect->query("UPDATE `closed_status` SET `cus_sts` = 22,`update_login_id`=$userid,`updated_date`= now() WHERE req_id = '".$req_id."' && `cus_id`='".$cus_id."' ") or die('Error on closed_status Table');
    $selectIC = $connect->query("UPDATE `noc` SET `cus_status` = 22,`update_login_id`=$userid,`updated_date`= now() WHERE req_id = '".$req_id."' && `cus_id`='".$cus_id."' ") or die('Error on NOC Table');

    if($selectIC){
        $response = 'Successfully Removed';
    }else{
        $response = 'Error While Removing from NOC';
    }

    echo json_encode($response);

    // Close the database connection
$connect = null;
?>