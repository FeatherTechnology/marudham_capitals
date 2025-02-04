<?php
require '../ajaxconfig.php';

$req_id            = $_POST['reqId'];
$cust_id            = $_POST['cust_id'];
$userId            = $_POST['userId'];
$collDate          = date('Y-m-d',strtotime($_POST['collDate']));
$collPurpose       = $_POST['collPurpose'];
$collAmnt          = $_POST['collAmnt'];



// if($bankID == ''){

$insert_qry = $connect ->query("INSERT INTO `collection_charges`( `req_id`, `cus_id`, `coll_date`, `coll_purpose`, `coll_charge`, `status`, `insert_login_id`, `created_date`) VALUES ('$req_id','$cust_id','$collDate','$collPurpose','$collAmnt','0','$userId',now())");

// }
// else{
    
//  $update = $connect->query("INSERT INTO `collection_charges`(`id`, `req_id`, `cus_id`, `coll_date`, `coll_purpose`, `coll_charge`, `status`, `insert_login_id`, `update_login_id`, `created_date`, `updated_date`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]')");

// }

if($insert_qry){
    $result = "Fine Inserted Successfully.";
}
// elseif($update){
//     $result = "Bank Info Updated Successfully.";
// }

echo json_encode($result);
