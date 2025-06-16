<?php
//Also using in signed doc info
include '../../ajaxconfig.php';

$type = $_POST['type'];
$reqId = $_POST['reqId'];
$holder_name = array();

if ($type == '0') {
   $cus_profile = $connect->query("select `cus_name` from customer_profile where req_id = '$reqId' ");
   $cus = $cus_profile->fetch();
   $name = $cus['cus_name'];

   $holder_name  = array("name" => $name);
}

if ($type == '1') {
   $cus_profile = $connect->query("select `guarentor_name` from customer_profile where req_id = '$reqId' ");
   $cus = $cus_profile->fetch();
   $guarentor_name = $cus['guarentor_name'];

   $result = $connect->query("SELECT famname,relationship FROM `verification_family_info` where id='$guarentor_name'");
   $row = $result->fetch();

   $famname = $row['famname'];
   $relationship = $row['relationship'];

   $holder_name  = array("name" => $famname, "relationship" => $relationship);
}

echo json_encode($holder_name);

// Close the database connection
$connect = null;
