<?php
session_start();
$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');

if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}
if(isset($_POST['status'])){
    $status = $_POST['status'];
    $int_status = $status=='Interested' ? '0':'1';
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
if(isset($_POST['orgin_table'])){
    $originName = ['renewal' => 1, 're_active' => 4, 'new_promotion' => 2, 'repromotion' => 3]; //1=renewal, 2=New, 3=Repromotion ,4= re-active
    $orgin_table = $originName[$_POST['orgin_table']];
}

    $sql = $connect->query("UPDATE new_cus_promo SET int_status = '$int_status' WHERE cus_id = '$cus_id'");
    $sql1 = $connect->query("INSERT INTO new_promotion(cus_id, status, label, remark, follow_date, orgin_table, insert_login_id, created_date) 
        VALUES('$cus_id', '$status', '$label', '$remark', '$follow_date', '$orgin_table', '$userid', now())");
    
    if($sql && $sql1){
        $response = 'Inserted Successfully';
    }else{
        $response = 'Error While Inserting';
    }

echo $response;

// Close the database connection
$connect = null;
?>