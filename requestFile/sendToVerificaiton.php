<?php
session_start();
include('../ajaxconfig.php');
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

$selectIC = $connect->query("SELECT cus_reg_id FROM customer_register WHERE cus_id = '" . $cus_id . "' ") or die('Error on Select Customer Table');
$row = $selectIC->fetch();
$cus_reg_id = $row['cus_reg_id'];

$selectIC = $connect->query("UPDATE request_creation set cus_reg_id=$cus_reg_id, cus_status = 1,updated_date = now(), update_login_id = $userid WHERE req_id = '" . $req_id . "' ") or die('Error on Request Table');
$selectIC = $connect->query("UPDATE customer_register set cus_status = 1,updated_date = now() WHERE req_ref_id = '" . $req_id . "' ") or die('Error on Update Customer Table');


$selectIC = $connect->query("INSERT INTO in_verification (`req_id`,`user_type`, `user_name`, `agent_id`, `responsible`, `remarks`, `declaration`,
        `req_code`, `dor`,`cus_reg_id`, `cus_id`, `cus_data`, `cus_name`, `dob`, `age`, `gender`, `state`, `district`, `taluk`, `area`, `sub_area`, `address`,
        `mobile1`, `mobile2`, `father_name`, `mother_name`, `marital`, `spouse_name`, `occupation_type`, `occupation`, `pic`, `loan_category`, 
        `sub_category`, `tot_value`, `ad_amt`, `ad_perc`, `loan_amt`, `poss_type`, `due_amt`, `due_period`, `cus_status`,`prompt_remark`, `status`, `insert_login_id`, 
        `update_login_id`, `delete_login_id`, `created_date`, `updated_date` )
        SELECT * from request_creation where req_id = '" . $req_id . "' ");

$response = 'Moved to Verification';

echo json_encode($response);

// Close the database connection
$connect = null;
