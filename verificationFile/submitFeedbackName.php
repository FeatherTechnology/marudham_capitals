<?php
require '../ajaxconfig.php';
session_start();
$user_id = $_SESSION["userid"];

$feedbackname = $_POST['feedbackname'];
$id = isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : '';

// Check if feedback name already exists (ignore current id when updating)
$check_qry = $connect->query("SELECT id FROM `cus_feedback_name` 
    WHERE feedback_name = '$feedbackname' AND id != '$id'");

if ($check_qry->rowCount() > 0) {
    // Duplicate exists
    echo json_encode("Feedback Name Already Exists!");
    $connect = null;
    exit;
}

// Insert or update
if ($id != '') {
    $update = $connect->query(
        "UPDATE `cus_feedback_name` 
        SET `feedback_name`='$feedbackname', `updated_login_id`='$user_id', `updated_date`=now() 
        WHERE `id` = '$id'"
    );
} else {
    $insert_qry = $connect->query(
        "INSERT INTO `cus_feedback_name` (`feedback_name`, `insert_login_id`, `created_date`) 
        VALUES ('$feedbackname','$user_id',now())"
    );
}

if (isset($insert_qry) && $insert_qry) {
    $result = "Feedback Name Inserted Successfully.";
} elseif (isset($update) && $update) {
    $result = "Feedback Name Updated Successfully.";
} else {
    $result = "Query Failed!";
}

echo json_encode($result);

$connect = null;
?>
