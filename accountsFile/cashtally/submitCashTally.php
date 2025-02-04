<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$op_date = date('Y-m-d',strtotime($_POST['op_date']));
$cl_date = date('Y-m-d',strtotime($_POST['op_date']));
$opening_bal = $_POST['opening_bal'];
$hand_op = $_POST['hand_op'];
$bank_op = $_POST['bank_op'];
$agent_op = $_POST['agent_op'];
$closing_bal = $_POST['closing_bal'];
$hand_cl = $_POST['hand_cl'];
$bank_cl = $_POST['bank_cl'];

$bank_untrkd = rtrim($_POST['bank_untrkd'],',');
$bank_untrkd = str_replace('(','',$bank_untrkd);
$bank_untrkd = str_replace(')','',$bank_untrkd);

$agent_cl = $_POST['agent_cl'];


$qry = $connect->query("INSERT INTO `cash_tally`(`op_date`,`op_hand`, `op_bank`, `op_agent`, `opening_bal`,`cl_date`, `cl_hand`, `cl_bank`, `bank_untrkd`, `cl_agent`, `closing_bal`, `insert_login_id`,`created_date` ) 
VALUES ('$op_date','$hand_op','$bank_op','$agent_op','$opening_bal','$cl_date','$hand_cl','$bank_cl','$bank_untrkd','$agent_cl','$closing_bal','$user_id',now() )");

    if($qry){
        $response = "Submitted Successfully";
    }else{
        $response = "Error While Submit";
    }

echo $response;

// Close the database connection
$connect = null;
?>