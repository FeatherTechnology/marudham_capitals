<?php

session_start();
$user_id= $_SESSION['userid'];

include('../../../ajaxconfig.php');

$ag_id = $_POST['ag_id'];
$remark = $_POST['remark'];
$amt = $_POST['amt'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


$qry = $connect->query("INSERT INTO `ct_cr_hag`(`ag_id`,`remark`, `amt`, `insert_login_id`, `created_date`) 
VALUES ('$ag_id','$remark','$amt','$user_id','$op_date')");

if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submitting";
}

echo $response;

// Close the database connection
$connect = null;
?>