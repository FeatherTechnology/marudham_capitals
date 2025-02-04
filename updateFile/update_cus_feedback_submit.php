<?php
require '../ajaxconfig.php';

$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$feedback_label        = $_POST['feedback_label'];
$cus_feedback              = $_POST['cus_feedback'];
$feedback_remark              = $_POST['feedback_remark'];
$feedbackID              = $_POST['feedbackID'];


if($feedbackID == ''){

$insert_qry = $connect ->query("INSERT INTO `verification_cus_feedback`( `cus_id`, `feedback_label`, `cus_feedback`,`feedback_remark`) VALUES ('$cus_id','$feedback_label','$cus_feedback','$feedback_remark')");

}
else{
$update = $connect->query("UPDATE `verification_cus_feedback` SET `cus_id`='$cus_id',`feedback_label`='$feedback_label',`cus_feedback`='$cus_feedback',`feedback_remark`='$feedback_remark' WHERE `id`='$feedbackID' ");

}

if($insert_qry){
    $result = "Feedback Inserted Successfully.";
}
elseif($update){
    $result = "Feedback Updated Successfully.";
}

echo json_encode($result);
?>