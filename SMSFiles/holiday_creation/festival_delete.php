<?php
include '../../ajaxconfig.php';

$festivalID =$_POST['festivalId'];

$delct = $connect->query("DELETE FROM `holiday_creation` WHERE `holiday_id`= '$festivalID' ");
if($delct){
    $message=" Festival Info Deleted Successfully";
}
echo json_encode($message);
?>