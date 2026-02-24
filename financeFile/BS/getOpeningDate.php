<?php
include('../../ajaxconfig.php');
include './getBSOPCLBalanceClass.php';
require_once(__DIR__ . '/../../accountsFile/HandCashBS/getCircularAmount.php');
$CROBJ = new CircularAmountClass($connect); 

$CBObj = new ClosingBalanceClass($connect); 

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';
$branch_id = ($_POST['branch_id'] != '') ? $_POST['branch_id'] : '1,2,3,4,5,6,7';
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

$circular_amount = $CROBJ->getCircularAmount( $op_date, $closing_date, $branch_id, $user_id);


echo json_encode(array($getClosingBalForBS, $records, $getUnclearedForBS, $circular_amount));

// Close the database connection
$connect = null;
