<?php
session_start();
$user_id = $_SESSION["userid"];
require '../ajaxconfig.php';

$req_id                = $_POST['reqId'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$feedback_label        = $_POST['feedback_label'];
$cus_feedback              = $_POST['cus_feedback'];
$feedback_remark              = $_POST['feedback_remark'];
$feedbackID              = $_POST['feedbackID'];


if ($feedbackID == '') {
    $insert_qry = $connect->query("INSERT INTO `verification_cus_feedback`( `cus_id`,`req_id`, `feedback_label`, `cus_feedback`,`feedback_remark`,`insert_login_id`,`inserted_date`) VALUES ('$cus_id','$req_id','$feedback_label','$cus_feedback','$feedback_remark','$user_id',now())");
} else {
    $update = $connect->query("UPDATE `verification_cus_feedback` SET `cus_id`='$cus_id',`req_id`='$req_id',`feedback_label`='$feedback_label',`cus_feedback`='$cus_feedback',`feedback_remark`='$feedback_remark' WHERE `id`='$feedbackID' ");
}

if ($insert_qry) {
    $result = "Feedback Inserted Successfully.";
} elseif ($update) {
    $result = "Feedback Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;