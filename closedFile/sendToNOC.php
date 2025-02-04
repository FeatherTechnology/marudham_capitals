<?php 
session_start();
include('../ajaxconfig.php');
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

//Closed  Completed And Move to NOC = 21.

    $qry = $connect->query("UPDATE request_creation set cus_status = 21,updated_date = now(), update_login_id = $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ") or die('Error on Request Table');
    $qry = $connect->query("UPDATE customer_register set cus_status = 21 WHERE cus_id = '".$cus_id."' and req_ref_id = '".$req_id."' ")or die('Error on Customer Table');
    $qry = $connect->query("UPDATE in_verification set cus_status = 21, update_login_id = $userid WHERE cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ")or die('Error on inVerification Table');
    $qry = $connect->query("UPDATE `in_approval` SET `cus_status`= 21,`update_login_id`= $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ") or die('Error on in_approval Table');
    $qry = $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 21,`update_login_id`= $userid and updated_date=now() WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ") or die('Error on in_acknowledgement Table');
    $qry = $connect->query("UPDATE `in_issue` SET `cus_status`= 21,`update_login_id` = $userid where cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ") or die('Error on in_issue Table');
    $qry = $connect->query("UPDATE `closed_status` SET `cus_sts`='21',`update_login_id`=$userid,`updated_date`= now() WHERE `cus_sts`='20' and req_id = '".$req_id."' && `cus_id`='".$cus_id."' ") or die('Error on closed_status Table');

    $qry = $connect->query("INSERT INTO `document_track`(`req_id`, `cus_id`, `track_status`, `insert_login_id`, `created_date`) 
    VALUES('".strip_tags($req_id)."','".strip_tags($cus_id)."','3','$userid', now()) ");

    if($qry){
        $response = 'Customer Moved to NOC';
    }else{
        $response = 'Error While Moving to NOC';
    }
    echo json_encode($response);

// Close the database connection
$connect = null;
?>