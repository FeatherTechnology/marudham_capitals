<?php
include '../../ajaxconfig.php';

$id = $_POST['fam_id'];

$result = $connect->query("SELECT relationship FROM `verification_family_info` where id='$id'");
$row = $result->fetch();

$relationship = $row['relationship'];

echo json_encode($relationship);

// Close the database connection
$connect = null;