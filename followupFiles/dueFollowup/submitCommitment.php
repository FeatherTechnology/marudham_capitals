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
$date =  !empty($_POST['date']) ? $_POST['date'] : null; //commitement date
$hint = $_POST['hint'];
$err = $_POST['err'] ?? '';

$sql = $connect->prepare("INSERT INTO `commitment` (`req_id`, `cus_id`, `ftype`, `fstatus`, `person_type`, `person_name`, `relationship`, `remark`, `comm_date`, `hint`, `comm_err`, `insert_login_id`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

if ($sql->execute([$req_id, $cus_id, $ftype, $fstatus, $person_type, $person_name, $relationship, $remark, $date, $hint, $err, $userid])) {
    $response = 'Inserted Successfully';
} else {
    $response = 'Error While Inserting';
}

echo $response;

// Close the database connection
$connect = null;