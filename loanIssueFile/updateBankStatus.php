<?php 
include('../ajaxconfig.php');

$id = $_POST['id'];
$issue_status = $_POST['issue_status'];
$cusid = $_POST['cusid'];

$delct = $connect->query("UPDATE verification_bank_info SET  issue_status = '1' where cus_id = '$cusid' AND id !='$id' "); //to remove previous bank info issue status.
$delct = $connect->query("UPDATE verification_bank_info SET  issue_status = '$issue_status' where id ='$id' ");

if ($delct) {
	$message = "Bank Selected Successfully";
}

echo json_encode($message);

// Close the database connection
$connect = null;

