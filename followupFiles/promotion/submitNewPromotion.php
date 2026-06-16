<?php
session_start();
$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');

if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}
if(isset($_POST['promo_type'])){
    $promo_type = $_POST['promo_type'];
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

$followupType = $_POST['followupType'] ?? '0';

if(isset($_POST['orgin_table'])){
    $originName = ['renewal' => 1, 're_active' => 4, 'new_promotion' => 2, 'repromotion' => 3]; //1=renewal, 2=New, 3=Repromotion ,4= re-active
    $orgin_table = $originName[$_POST['orgin_table']];
}

$sql = $connect->query("UPDATE new_cus_promo SET int_status = '$int_status' WHERE cus_id = '$cus_id'");
$sql1 = $connect->prepare("INSERT INTO new_promotion (cus_id, promo_type, status, label, remark, follow_date, followup_type, orgin_table, insert_login_id, created_date) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$sql1->execute([
    $cus_id,
    $promo_type,
    $status,
    $label,
    $remark,
    $follow_date,
    $followupType,
    $orgin_table,
    $userid
]);

$response = ($sql && $sql1) ? 'Inserted Successfully' : 'Error While Inserting';

echo $response;

// Close the database connection
$connect = null;
?>