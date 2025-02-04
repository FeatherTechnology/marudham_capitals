<?php 
session_start();
include('../ajaxconfig.php');

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
if(isset($_POST['feedbackDate'])){
    $feedback_date = $_POST['feedbackDate'];
}
if(isset($_POST['ratingVal'])){
    $rating_value = $_POST['ratingVal'];
}

//Concern Completed.

$updConcern = $connect->query("UPDATE `concern_creation` SET  `status`= 2 , `feedback_date`='".strip_tags($feedback_date)."',`feedback_rating`='".strip_tags($rating_value)."',`update_user_id`='".strip_tags($userid)."',`updated_date`= now() WHERE `id` = '".strip_tags($id)."' ") or die('Error on Concern Creation Table');

if($updConcern){
    $response = 'Concern Completed';
}
    echo json_encode($response);

// Close the database connection
$connect = null;
?>