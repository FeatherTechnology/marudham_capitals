<?php 
session_start();
include('../ajaxconfig.php');
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

//Issue  Completed then can be removed and cus status = 17.

    $qry = $connect->query("UPDATE request_creation set cus_status = 17, update_login_id = $userid WHERE  req_id = '".$req_id."' ") or die('Error on Request Table');
    $qry = $connect->query("UPDATE customer_register set cus_status = 17 WHERE req_ref_id = '".$req_id."' ")or die('Error on Customer Table');
    $qry = $connect->query("UPDATE in_verification set cus_status = 17, update_login_id = $userid WHERE req_id = '".$req_id."' ")or die('Error on inVerification Table');
    $qry = $connect->query("UPDATE `in_approval` SET `cus_status`= 17,`update_login_id`= $userid WHERE  req_id = '".$req_id."' ") or die('Error on in_approval Table');
    $qry = $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 17,`update_login_id`= $userid and updated_date=now() WHERE  req_id = '".$req_id."'") or die('Error on in_acknowledgement Table');
    $qry = $connect->query("UPDATE `in_issue` SET `cus_status`= 17,`update_login_id` = $userid where req_id = '".$req_id."' ") or die('Error on in_issue Table');

    if($qry){
        $response = 'Loan Issue Removed';
    }else{
        $response = 'Error';
    }
    echo json_encode($response);

// Close the database connection
$connect = null;
?>