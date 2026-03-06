<?php
session_start();
$user_id = $_SESSION["userid"];
include '../ajaxconfig.php';

if (isset($_POST['con_dep_name'])) {
    $con_dep_name = $_POST['con_dep_name'];
}
if (isset($_POST['con_dep_name_id'])) {
    $con_dep_name_id = $_POST['con_dep_name_id'];
}

if($con_dep_name_id!=''){
	$updateDepName=$connect->query("UPDATE `concern_dept_name` SET `dep_name`='$con_dep_name',`update_login_id`='$user_id',`updated_date`=now() WHERE `id` = $con_dep_name_id");
	if($updateDepName == true){
		$message='Department Name Updated Succesfully';
	}
}
else{
	$insertDepName=$connect->query("INSERT INTO `concern_dept_name`( `dep_name`, `insert_login_id`, `created_date`) VALUES ('$con_dep_name','$user_id',now())");
	if($insertDepName == true){
		$message='Department Added Succesfully';
	}
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>