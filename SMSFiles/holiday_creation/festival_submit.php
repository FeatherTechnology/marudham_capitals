<?php
require '../../ajaxconfig.php';
@session_start();
if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
}

$holiday_date                   = $_POST['holiday_date'];
$holiday                 = $_POST['holiday'];
$holiday_comment            = $_POST['holiday_comment'];
$festivalID              = $_POST['festivalID'];

if($festivalID == ''){

$insert_qry = $connect ->query("INSERT INTO `holiday_creation`(`holiday_name`, `holiday_date`, `comments`, `insert_login_id`) VALUES ('$holiday','$holiday_date','$holiday_comment','$userid')");

}else{
$update = $connect->query("UPDATE `holiday_creation` SET `holiday_name`='$holiday',`holiday_date`='$holiday_date',`comments`='$holiday_comment',`status`='0',`update_login_id`='$userid',`updated_date`= now() WHERE `holiday_id`= '$festivalID' ");

}

if($insert_qry){
    $result = "Festival Info Inserted Successfully.";
}
elseif($update){
    $result = "Festival Info Updated Successfully.";
}

echo json_encode($result);

?>