<?php
include('../ajaxconfig.php');

$feedbacklable = array();

$result = $connect->query("SELECT id,feedback_name FROM `cus_feedback_name` where 1");

while ($row = $result->fetch()) {
    $feedback_name = $row['feedback_name'];
    $id = $row['id'];
    $feedbacklable[] = array("id" => $id, "feedback_name" => $feedback_name);
}

echo json_encode($feedbacklable);

// Close the database connection
$connect = null;