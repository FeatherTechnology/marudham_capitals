<?php
require '../ajaxconfig.php';

$req_id                = $_POST['reqId'];
$cus_id                = $_POST['cusidupd'];
$feedback_label        = $_POST['feedback_label'];
$cus_feedback              = $_POST['cus_feedback'];
$feedback_remark              = $_POST['feedback_remark'];
$feedbackID              = $_POST['feedbackID'];


if($feedbackID == ''){

$insert_qry = $connect ->query("INSERT INTO `loan_summary_feedback`( `req_id`,`cus_id`, `feedback_label`, `cus_feedback`,`feedback_remark`) VALUES ('$req_id','$cus_id','$feedback_label','$cus_feedback','$feedback_remark')");

}
else{
    
 $update = $connect->query("UPDATE `loan_summary_feedback` SET `req_id`='$req_id',`cus_id`='$cus_id',`feedback_label`='$feedback_label',`cus_feedback`='$cus_feedback',`feedback_remark`='$feedback_remark' WHERE `id`='$feedbackID' ");

}

if($insert_qry){
    $result = "Loan Summary Inserted Successfully.";
}
elseif($update){
    $result = "Loan Summary Updated Successfully.";
}

echo json_encode($result);
?>