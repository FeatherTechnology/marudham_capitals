<?php
require '../ajaxconfig.php';

$id = $_POST['id'];

$feedback = array();

$feedbackDetail = $connect->query("SELECT * FROM `cus_feedback_name` WHERE id='$id' ");
$cus_feedback = $feedbackDetail->fetch();

$feedback['id'] = $cus_feedback['id'];
$feedback['feedback_name'] = $cus_feedback['feedback_name'];

echo json_encode($feedback);

// Close the database connection
$connect = null;