<?php
require '../ajaxconfig.php';

$req_id            = $_POST['reqId'];
$cust_id            = $_POST['cust_id'];
$userId            = $_POST['userId'];
$collDate          = date('Y-m-d',strtotime($_POST['collDate']));
$collPurpose       = $_POST['collPurpose'];
$collAmnt          = $_POST['collAmnt'];

$insert_qry = $connect ->query("INSERT INTO `collection_charges`( `req_id`, `cus_id`, `coll_date`, `coll_purpose`, `coll_charge`, `status`, `insert_login_id`, `created_date`) VALUES ('$req_id','$cust_id','$collDate','$collPurpose','$collAmnt','0','$userId',now())");

if($insert_qry){
    $result = "Fine Inserted Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;