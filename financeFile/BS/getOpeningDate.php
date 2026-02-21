<?php
include('../../ajaxconfig.php');
include './getBSOPCLBalanceClass.php';

$CBObj = new ClosingBalanceClass($connect); 

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';
$bank_detail = $_POST['bankDetail'] ?? '';

$records = array();

if ($type == 'today') {

    $closing_date = date('Y-m-d');
    $op_date = date('Y-m-d');

} else if ($type == 'day') {

    $op_date = $_POST['from_date'];
    $closing_date = $_POST['to_date'];

} else if ($type == 'month') {
    
    $selectedMonth = $_POST['month'];
    // Previous month
    $prevDate = date('Y-m', strtotime("$selectedMonth-01 -1 month"));

    $month = date('m', strtotime($prevDate ));
    $year = date('Y', strtotime($prevDate));

    $closing_date = date('Y-m-t', strtotime("$selectedMonth"));
    $op_date = date('Y-m-01', strtotime("$selectedMonth"));

}


$records = $CBObj->getDetails( $op_date,  $bank_detail , $user_id);

$getClosingBalForBS = $CBObj->getClosingBalance($closing_date, $bank_detail, $user_id);

$getUnclearedForBS = $CBObj->getUncleared( $op_date,$closing_date); 


echo json_encode(array($getClosingBalForBS, $records,$getUnclearedForBS));

// Close the database connection
$connect = null;
