<?php
session_start();
$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');


$req_id = $_POST['req_id'];
$cus_id = $_POST['cus_id'];

$ftype = $_POST['ftype']; //direct or mobile
$fstatus = $_POST['fstatus']; //commitment or unavailable

$person_type = $_POST['person_type'];
if ($person_type == 3) {
    $person_name = $_POST['person_name1'];
} else {
    $person_name = $_POST['person_name'];
}
$relationship = $_POST['relationship'];
$remark = $_POST['remark'];
$date = $_POST['date']; //commitement date
$hint = $_POST['hint'];
$err = $_POST['err'] ?? '';


$sql = $connect->query("INSERT INTO `commitment`(`req_id`,`cus_id`, `ftype`, `fstatus`, `person_type`, `person_name`, `relationship`, `remark`, `comm_date`, `hint`, `comm_err`, `insert_login_id`,`created_date`) VALUES ('$req_id','$cus_id','$ftype','$fstatus','$person_type','$person_name','$relationship','$remark','$date','$hint','$err','$userid',NOW())");

if ($sql) {
    $response = 'Inserted Successfully';
} else {
    $response = 'Error While Inserting';
}

echo $response;

// Close the database connection
$connect = null;