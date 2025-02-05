<?php
session_start();
include '../ajaxconfig.php';

if(isset($_POST["cus_id"])){
    $cus_id = $_POST["cus_id"];
}

$records = array();
$how_to_know_obj = [
    '0' => 'Customer Reference',
    '1' => 'Advertisement',
    '2' => 'Promotion Activity',
    '3' => 'Agent Reference',
    '4' => 'Staff Reference',
    '5' => 'Other Reference',
    '6' => 'Renewal'
];

$qry = $connect->query("SELECT * FROM customer_register where cus_id = $cus_id");
if($qry->rowCount() > 0){
    $row = $qry->fetch();
    $records['how_to_know'] = $how_to_know_obj[$row['how_to_know']];
    $records['monthly_income'] = $row['monthly_income'];
    $records['other_income'] = $row['other_income'];
    $records['support_income'] = $row['support_income'];
    $records['commitment'] = $row['commitment'];
    $records['monthly_due_capacity'] = $row['monthly_due_capacity'];
    $records['loan_limit'] = $row['loan_limit'];
    $records['about_customer'] = $row['about_customer'];
}


echo json_encode($records);

// Close the database connection
$connect = null;
?>