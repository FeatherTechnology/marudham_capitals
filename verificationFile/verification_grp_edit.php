<?php
require '../ajaxconfig.php';

$id = $_POST['id'];

$grpEditRes = array();
$grpInfo = $connect->query("SELECT * FROM `verification_group_info` WHERE id='$id' ");
$group = $grpInfo->fetch();

$grpEditRes['id'] = $group['id'];
$grpEditRes['gname'] = $group['group_name'];
$grpEditRes['age'] = $group['group_age'];
$grpEditRes['gaadhar'] = $group['group_aadhar'];
$grpEditRes['gmobile'] = $group['group_mobile'];
$grpEditRes['gGen'] = $group['group_gender'];
$grpEditRes['dgsn'] = $group['group_designation'];

echo json_encode($grpEditRes);

// Close the database connection
$connect = null;