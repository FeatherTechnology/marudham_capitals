<?php
require '../ajaxconfig.php';

$req_id           = $_POST['req_id'];
$cus_id           = $_POST['cus_id'];
$group_name           = $_POST['group_name'];
$group_age            = $_POST['group_age'];
$group_aadhar         =  preg_replace('/\s+/', '', $_POST['group_aadhar']);
$group_mobile         = $_POST['group_mobile'];
$group_gender         = $_POST['group_gender'];
$group_designation    = $_POST['group_designation'];
$grpTableId           = $_POST['grpTableId'];


if ($grpTableId == '') {

    $insert_qry = $connect->query("INSERT INTO `verification_group_info`( `cus_id`,`req_id`, `group_name`, `group_age`, `group_aadhar`, `group_mobile`, `group_gender`, `group_designation`) VALUES ('$cus_id','$req_id','$group_name','$group_age','$group_aadhar','$group_mobile','$group_gender','$group_designation')");
} else {
    $update = $connect->query("UPDATE `verification_group_info` SET `cus_id`='$cus_id',`req_id`='$req_id',`group_name`='$group_name',`group_age`='$group_age',`group_aadhar`='$group_aadhar',`group_mobile`='$group_mobile',`group_gender`='$group_gender',`group_designation`='$group_designation' WHERE `id`='$grpTableId' ");
}

if ($insert_qry) {
    $result = "Group Info Inserted Successfully.";
} elseif ($update) {
    $result = "Group Info Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
