<?php
include "../../ajaxconfig.php";
session_start();
$userid = $_SESSION['userid'];

$cus_id = preg_replace('/\D/', '',$_POST['cus_id']) ?? '';
$cus_data = $_POST['cus_data'] ?? '';
$cus_name = $_POST['cus_name'] ?? '';
$cus_mob = $_POST['cus_mob'] ?? '';
$area = $_POST['area'] ?? '';
$sub_area = $_POST['sub_area'] ?? '';
$enquiry_loan_amt = $_POST['enquiry_loan_amt'] ?? '';
$remarks = $_POST['remarks'] ?? '';


$sql = $connect->prepare("SELECT COUNT(*) FROM enquiry WHERE cus_id = ?");
$sql->execute([$cus_id]);
$row_count = $sql->fetchColumn();

if($row_count == 0){
    $sql = $connect->prepare("INSERT INTO enquiry(cus_id, cus_data, cus_name, mobile, area, sub_area, loan_amount,remarks, insert_login_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->execute([$cus_id, $cus_data, $cus_name, $cus_mob, $area, $sub_area, $enquiry_loan_amt,$remarks, $userid]);
    //insert customer details if customer id is not present in the table

    $response = ($sql) ? 'Enquiry Inserted Successfully' : 'Error While Inserting';

}else{
    $response = "Error! Customer Exists";
}


echo $response;

// Close the database connection
$connect = null;
?>