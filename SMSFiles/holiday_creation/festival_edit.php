<?php
require '../../ajaxconfig.php';

$festivalID =$_POST['id'];

$festivalEditRes = array();
$festivalInfo = $connect -> query("SELECT * FROM `holiday_creation` WHERE `holiday_id`= '$festivalID' ");
$festival = $festivalInfo->fetch();

$festivalEditRes['holiday_id'] = $festival['holiday_id'];
$festivalEditRes['holiday_name'] = $festival['holiday_name'];
$festivalEditRes['holiday_date'] = $festival['holiday_date'];
$festivalEditRes['comments'] = $festival['comments'];

echo json_encode($festivalEditRes);
?>


