<?php
require '../ajaxconfig.php';

$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$bank_name             = $_POST['bank_name'];
$branch_name           = $_POST['branch_name'];
$account_holder_name   = $_POST['account_holder_name'];
$account_number        = $_POST['account_number'];
$Ifsc_code             = $_POST['Ifsc_code'];
$bankID                = $_POST['bankID'];


if($bankID == ''){

$insert_qry = $connect ->query("INSERT INTO `verification_bank_info`(`cus_id`, `bank_name`, `branch_name`, `acc_holder_name`, `acc_no`, `ifsc_code`) VALUES ('$cus_id','$bank_name','$branch_name','$account_holder_name','$account_number','$Ifsc_code')");

}
else{
$update = $connect->query("UPDATE `verification_bank_info` SET `cus_id`='$cus_id',`bank_name`='$bank_name',`branch_name`='$branch_name',`acc_holder_name`='$account_holder_name',`acc_no`='$account_number',`ifsc_code`='$Ifsc_code' WHERE `id`='$bankID'");

}

if($insert_qry){
    $result = "Bank Info Inserted Successfully.";
}
elseif($update){
    $result = "Bank Info Updated Successfully.";
}

echo json_encode($result);
