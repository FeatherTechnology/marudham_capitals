<?php
include('../../ajaxconfig.php');
include './getBSOPCLBalanceClass.php';

$CBObj = new ClosingBalanceClass($connect); 

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';
$bank_detail = $_POST['bankDetail'] ?? '';

$records = array();

if ($type == 'today') {

    $where = " date(ct1.cl_date) <= CURRENT_DATE() ";
    $where2 = " date(ct2.cl_date) <= CURRENT_DATE() ";
    $closing_date = date('Y-m-d');
    $op_date = date('Y-m-d');

} else if ($type == 'day') {

    $op_date = $_POST['from_date'];

    $where = " date(ct1.cl_date) < DATE('$op_date') ";
    $where2 = " date(ct2.cl_date) < DATE('$op_date') ";
    $closing_date = $_POST['to_date'];

} else if ($type == 'month') {
    
    $selectedMonth = $_POST['month'];
    // Previous month
    $prevDate = date('Y-m', strtotime("$selectedMonth-01 -1 month"));

    $month = date('m', strtotime($prevDate ));
    $year = date('Y', strtotime($prevDate));

    $where = " (month(ct1.cl_date) <= $month && YEAR(ct1.cl_date) = '$year' ) ";
    $where2 = " (month(ct2.cl_date) <= $month && YEAR(ct2.cl_date) = '$year' ) ";
    $closing_date = date('Y-m-t', strtotime("$selectedMonth"));
    $op_date = date('Y-m-01', strtotime("$selectedMonth"));

}

if ($user_id != '') {
    $where .= " and ct1.insert_login_id = $user_id ";
} //for user based

$records = $CBObj->getDetails($where, $where2, $op_date, $user_id);

$getClosingBalForBS = $CBObj->getClosingBalance($closing_date, $bank_detail, $user_id); 


echo json_encode(array($getClosingBalForBS, $records));

// Close the database connection
$connect = null;
