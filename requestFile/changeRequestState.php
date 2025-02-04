<?php
session_start();
include '../ajaxconfig.php';

$currentDate = date('Y-m-d');
$user_id  = $_SESSION['userid'];
$req_id = $_POST['req_id'];

$remark = $_POST['remark'];

$screen = $_POST['screen'];

if ($screen == 'request') {
    if (isset($_POST['remark']) && $_POST['remark'] != '' && $_POST['remark'] != null) {

        //set the status based on which screen
        $cus_status = $_POST['state'] == 'cancel' ? '4' : '8';

        $qry1 = $connect->query("UPDATE request_creation set cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id'  ");
        $qry2 = $connect->query("UPDATE customer_register SET cus_status = $cus_status, updated_date = '$currentDate' WHERE req_ref_id = '$req_id' ");

        if ($qry1 && $qry2) {
            $response = 'Success';
        } else {
            $response = 'Error';
        }
    } else {
        $response = 'Error';
    }
} else if ($screen == 'verification') {
    if (isset($_POST['remark']) && $_POST['remark'] != '' && $_POST['remark'] != null) {

        //set the status based on which screen
        $cus_status = $_POST['state'] == 'cancel' ? '5' : '9';

        $qry1 = $connect->query("UPDATE request_creation set cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id'  ");
        $qry2 = $connect->query("UPDATE in_verification SET cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id' ");

        if ($qry1 && $qry2) {
            $response = 'Success';
        } else {
            $response = 'Error';
        }
    } else {
        $response = 'Error';
    }
} else if ($screen == 'approval') {

    if (isset($_POST['remark']) && $_POST['remark'] != '' && $_POST['remark'] != null) {

        //set the status based on which screen
        $cus_status = $_POST['state'] == 'cancel' ? '6' : '';

        if ($cus_status != '') {
            $qry1 = $connect->query("UPDATE request_creation set cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id'  ");
            $qry2 = $connect->query("UPDATE in_verification SET cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id' ");
        }
        if ($qry1 && $qry2) {
            $response = 'Success';
        } else {
            $response = 'Error';
        }
    } else {
        $response = 'Error';
    }
} else if ($screen == 'ack') {

    if (isset($_POST['remark']) && $_POST['remark'] != '' && $_POST['remark'] != null) {

        //set the status based on which screen
        $cus_status = $_POST['state'] == 'cancel' ? '7' : '';

        if ($cus_status != '') {
            $qry1 = $connect->query("UPDATE request_creation set cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id'  ");
            $qry2 = $connect->query("UPDATE in_verification SET cus_status = $cus_status, prompt_remark = '$remark', updated_date='$currentDate', update_login_id= $user_id where req_id = '$req_id' ");
            $qry3 = $connect->query("UPDATE in_approval set cus_status = $cus_status, update_login_id = $user_id where req_id = $req_id");
            $qry4 = $connect->query("UPDATE in_acknowledgement set cus_status = $cus_status, updated_date='$currentDate' , update_login_id = $user_id where req_id = $req_id");
        }
        if ($qry1 && $qry2 && $qry3 && $qry4) {
            $response = 'Success';
        } else {
            $response = 'Error';
        }
    } else {
        $response = 'Error';
    }
}

echo $response;

// Close the database connection
$connect = null;
