<?php
require '../ajaxconfig.php';

$cus_id = $_POST['cusid'] ?? '';
$cus_how_know = $_POST['cus_how_know'] ?? '';
$cus_loan_count = $_POST['cus_loan_count'] ?? '';
$cus_frst_loanDate = $_POST['cus_frst_loanDate'] ?? '';
$cus_travel_cmpy = $_POST['cus_travel_cmpy'] ?? '';
$cus_monthly_income = str_replace([',', ' '], '', $_POST['cus_monthly_income'] ?? '');
$cus_other_income = str_replace([',', ' '], '', $_POST['cus_other_income'] ?? '');
$cus_support_income = str_replace([',', ' '], '', $_POST['cus_support_income'] ?? '');
$cus_Commitment = str_replace([',', ' '], '', $_POST['cus_Commitment'] ?? '');
$cus_monDue_capacity = str_replace([',', ' '], '', $_POST['cus_monDue_capacity'] ?? '');
$cus_loan_limit = str_replace([',', ' '], '', $_POST['cus_loan_limit'] ?? '');
$about_cus = $_POST['about_cus'] ?? '';

$qry = $connect->prepare("UPDATE `customer_register` SET `how_to_know` = ?, `loan_count` = ?, `first_loan_date` = ?, `travel_with_company` = ?, `monthly_income` = ?, `other_income` = ?, `support_income` = ?, `commitment` = ?, `monthly_due_capacity` = ?, `loan_limit` = ?, `about_customer` = ? WHERE `cus_id` = ? ");

if($qry->execute([$cus_how_know, $cus_loan_count, $cus_frst_loanDate, $cus_travel_cmpy, $cus_monthly_income, $cus_other_income, $cus_support_income, $cus_Commitment, $cus_monDue_capacity, $cus_loan_limit, $about_cus, $cus_id])){
    $result = "Customer Summary Updated Successfully.";
} else{
    $result = "Customer Summary Update Failed.";
}

echo json_encode($result);

?>