<?php 
include('../ajaxconfig.php');

$con_sub = array();

$result=$connect->query("SELECT * FROM concern_subject where 1 and status=0");
while( $row = $result->fetch()){
    $concern_sub_id = $row['concern_sub_id'];
    $concern_subject = $row['concern_subject'];
    $con_sub[] = array("concern_sub_id" => $concern_sub_id, "concern_subject" => $concern_subject);
}

echo json_encode($con_sub);

// Close the database connection
$connect = null;
?>