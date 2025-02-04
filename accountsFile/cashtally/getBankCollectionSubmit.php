<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$bank_id = explode(',',$_POST['bank_id']);

$op_date = date('Y-m-d',strtotime($_POST['op_date']));
$response = '';

foreach($bank_id as $val){

    $qry=$connect->query("SELECT created_date from ct_bank_collection where bank_id = '$val' and date(created_date) = '$op_date' ");
    // check whether today's date has been already entered
    if($qry->rowCount() != 0 && $response != "Today's Collection Not Submitted"){
        $response = "Today's Collection Already Submitted";
    
    }else{
        $response = "Today's Collection Not Submitted";
    }
}

echo $response;

// Close the database connection
$connect = null;
?>