<?php
session_start();
$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');


if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}
if(isset($_POST['stage'])){
    $stage = $_POST['stage'];
}
if(isset($_POST['label'])){
    $label = $_POST['label'];
}
if(isset($_POST['remark'])){
    $remark = $_POST['remark'];
}
if(isset($_POST['follow_date'])){
    $follow_date = $_POST['follow_date'];
}

    $sql = $connect->query("INSERT INTO loan_followup(cus_id, stage, label, remark, follow_date, insert_login_id, created_date) 
        VALUES('$cus_id', '$stage', '$label', '$remark', '$follow_date', '$userid', now())");
    
    if($sql){
        $response = 'Inserted Successfully';
    }else{
        $response = 'Error While Inserting';
    }

echo $response;

// Close the database connection
$connect = null;
?>