<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$hex_id = $_POST['hex_id'];
$from_user_id = $_POST['from_user_id'];
$to_user_id = $_POST['to_user_id'];
$remark = $_POST['remark'];
$amt = str_replace(",","",$_POST['amt']);
$op_date = date('Y-m-d',strtotime($_POST['op_date']));


$response = '';

//set bedit table as amount credited/ received
$qry = $connect->query("UPDATE ct_db_hexchange set received = 0 where id = '$hex_id' ");

if($qry->rowCount() > 0){
    $qry = $connect->query("INSERT INTO `ct_cr_hexchange`(`db_ref_id`,`to_user_id`,`from_user_id`, `remark`, `amt`, `insert_login_id`, `created_date`) 
        VALUES ('$hex_id','$to_user_id','$from_user_id','$remark','$amt','$user_id','$op_date')");

    if($qry){
        $response = "Submitted Successfully";
    }else{
        $response = "Error";
    }
}else{
    $response = "Debited amount has been Deleted by Sender";
}

echo $response;

// Close the database connection
$connect = null;
?>