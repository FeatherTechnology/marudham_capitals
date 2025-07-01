<?php 
session_start(); 
$user_id = $_SESSION['userid'] ?? die('Session Expired'); 
include '../../ajaxconfig.php'; 
include './closingBalanceClass.php'; 

$CBObj = new ClosingBalanceClass($connect); 
$op_date = date('Y-m-d', strtotime($_POST['op_date'])); 
$closing_date = $op_date; 
$bank_detail = $_POST['bank_detail']; 
$records = array(); 
$old_hand = 0; 
$old_agent = 0; 
$old_bank = array(); 
$closing_balance = 0; 

//this wil get the current date's content 
$records = $CBObj->getClosingBalance($closing_date, $bank_detail, ''); 

//if below while loop gets true, then the function will load the old closing balance.. so store latest closing balance 
$closing_balance = $records[0]['closing_balance']; 
$old_hand = $records[0]['hand_closing']; 
$old_agent = $records[0]['agent_closing']; 

foreach ($records as $key => $value) {
    $old_bank[] = $value['bank_closing'];
}

// this will get the last data occurance 
// $closing_date = date('Y-m-d', strtotime($closing_date . '-1 day')); 
// $records = $CBObj->getClosingBalance($closing_date, $bank_detail, $user_id); 

// $closing_balance += $records[0]['closing_balance']; 

// if the last data occurance is empty then loop until we get the data. so the closing values 
// if ($op_date != date('Y-m-d')) { 
    // while ($records[0]['hand_closing'] == 0 || $records[0]['agent_closing'] == 0 || count(array_filter(array_column($records, 'bank_closing'))) == 0) { 
    //     $old_hand += $records[0]['hand_closing']; 
    //     $old_agent += $records[0]['agent_closing']; 

    //     foreach ($records as $key => $value) {
    //         $old_bank[$key] += $value['bank_closing'];
    //     }

    //     $closing_date = date('Y-m-d', strtotime($closing_date . '-1 day')); 
    //     $records = $CBObj->getClosingBalance($closing_date, $bank_detail, $user_id); 

    //     if ($records[0]['hand_closing'] == 0 && $records[0]['agent_closing'] == 0 && count(array_filter(array_column($records, 'bank_closing'))) == 0) { 
    //         break; 
    //     } 
    // } 
// } 

// now reassign latest closing date to returing variable. 
$records[0]['closing_balance'] = $closing_balance; 
$records[0]['hand_closing'] =  $old_hand; 
$records[0]['agent_closing'] =  $old_agent; 

foreach ($records as $key => $value) {
    $records[$key]['bank_closing'] = $old_bank[$key];
}

echo json_encode($records); 
// Close the database connection 
$connect = null; 
?>
