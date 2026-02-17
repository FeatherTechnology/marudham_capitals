<?php
session_start();
$insert_login_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$user_id = $_POST['user_id_rec'];
$user_name = $_POST['user_name_rec'];
$branch_id = $_POST['branch_id_rec'];
$line_id = $_POST['line_id_rec'];
$pre_bal = preg_replace('/[,\s]+/', '', $_POST['pre_waiver_rec']);
$waiver_amt = preg_replace('/[,\s]+/', '', $_POST['waiver_amt_rec']);
$rec_amt = preg_replace('/[,\s]+/', '', $_POST['tot_waiver_rec']);
$op_date = date('Y-m-d',strtotime($_POST['op_date']));

$qry = $connect->query("INSERT INTO `ct_hand_waiver`(`user_id`, `user_name`, `branch_id`, `line_id`, `pre_bal`, `waiver_amt`, `rec_amt`, `insert_login_id`, `created_date`) VALUES ('$user_id', '$user_name', '$branch_id', '$line_id', '$pre_bal', '$waiver_amt', '$rec_amt', '$insert_login_id', '$op_date')");

$username = $_SESSION['fullname'];
if($_SESSION['role'] == '1'){ $usertype = 'Director'; }elseif($_SESSION['role'] == '3'){ $usertype = 'Staff'; }

$qry1 = $connect->query("SELECT MAX(vou_id) AS vou_id FROM ct_db_hexpense WHERE 1");
$info = $qry1->fetch();
$voucher_id = (!$info['vou_id']) ? 101 : intval($info['vou_id']) + 1;

$qry2 = $connect->query("INSERT INTO `ct_db_hexpense`(`username`, `usertype`, `cat`, `part`, `vou_id`, `rec_per`, `remark`, `amt`, `insert_login_id`, `created_date`) VALUES ('$username','$usertype','16','Waiver expenses','$voucher_id','Tally team','tally','$rec_amt','$insert_login_id','$op_date')");

if($qry && $qry2){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submit";
}

echo $response;

// Close the database connection
$connect = null;
?>