<?php
require '../ajaxconfig.php';
session_start();

$userid  = isset($_SESSION['userid']) ? $_SESSION['userid'] : "";

$area_id             = $_POST['area_id'];
$taluks           = $_POST['taluks'];
$states   = $_POST['states'];
$districts        = $_POST['districts'];
$pincodes             = $_POST['pincodes'];

$update_area_list = $connect ->query("UPDATE `area_list_creation` SET `taluk`='$taluks' WHERE `area_id`='$area_id'");
$insert_area_creation = $connect ->query("UPDATE `area_creation` SET `taluk`='$taluks',`district`='$districts',`state`='$states',`pincode`='$pincodes',`update_login_id`='$userid ',`updated_date`= now() WHERE `area_name_id` = '$area_id' ");


if($update_area_list && $insert_area_creation){
    $result = "Area Updated";
}
elseif($update){
    $result = "Area Not Updated";
}

echo json_encode($result);
