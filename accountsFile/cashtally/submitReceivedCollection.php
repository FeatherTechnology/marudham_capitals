<?php
session_start();
$insert_login_id = $_SESSION['userid'];

include('../../ajaxconfig.php');


$user_id = $_POST['user_id_rec'];
$user_name = $_POST['user_name_rec'];
$branch_id = $_POST['branch_id_rec'];
$branch_name = $_POST['branch_name_rec'];
$line_id = $_POST['line_id_rec'];
$line_name = $_POST['line_name_rec'];
$pre_bal = $_POST['pre_bal_rec'];
$collected_amt = $_POST['collected_amt_rec'];
$tot_amt = $_POST['tot_amt_rec'];
$rec_amt = $_POST['rec_amt'];
$op_date = date('Y-m-d',strtotime($_POST['op_date']));

$qry = $connect->query("INSERT INTO `ct_hand_collection`(`user_id`, `user_name`, `branch_id`, `line_id`, `pre_bal`, `coll_amt`, `tot_amt`, `rec_amt`, `insert_login_id`, `created_date`) 
                VALUES ('$user_id','$user_name','$branch_id','$line_id','$pre_bal','$collected_amt','$tot_amt','$rec_amt','$insert_login_id','$op_date')");
if($qry){
    $response = "Submitted Successfully";
}else{
    $response = "Error While Submit";
}

echo $response;

// Close the database connection
$connect = null;
?>