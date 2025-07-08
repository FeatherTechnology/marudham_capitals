<?php 
session_start(); 
$user_id = $_SESSION['userid'] ?? die('Session Expired'); 
include '../../ajaxconfig.php'; 
include './closingBalanceClass.php'; 

$CBObj = new ClosingBalanceClass($connect); 
$closing_date = date('Y-m-d', strtotime($_POST['op_date'])); 
$bank_detail = $_POST['bank_detail']; 
$records = array(); 

//this wil get the current date's content 
$records = $CBObj->getClosingBalance($closing_date, $bank_detail, ''); 

foreach ($records as $key => $value) {
    $records[$key]['bank_closing'] = $value['bank_closing'];
}

echo json_encode($records); 

// Close the database connection 
$connect = null; 
?>
