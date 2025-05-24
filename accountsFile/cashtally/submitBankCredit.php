<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$bank_id = $_POST['bank_id'];
$credited_amt = $_POST['credited_amt'];
$op_date = date('Y-m-d', strtotime($_POST['op_date']));

// Get the sum of already credited amount for the selected bank and date
$qry = $connect->query("SELECT SUM(credited_amt) AS bank_amount FROM ct_bank_collection WHERE bank_id = '$bank_id' AND DATE(created_date) = '$op_date'");
$row = $qry->fetch();
$received_amount = isset($row['bank_amount']) ? (float)$row['bank_amount'] : 0;

// If no collection exists (NULL or 0), insert the full amount
if ($row['bank_amount'] === NULL || $received_amount == 0) {
    $insert = $connect->query("INSERT INTO ct_bank_collection (bank_id, credited_amt, insert_login_id, created_date) VALUES ('$bank_id', '$credited_amt', '$user_id', '$op_date')");
    
    if ($insert) {
        $response = "Submitted Successfully";
    } else {
        $response = "Error While Submitting";
    }
} else {
    // Record already exists, calculate remaining amount
    $remaining_amt = $credited_amt - $received_amount;
    
    if ($remaining_amt > 0) {
        $insert = $connect->query("INSERT INTO ct_bank_collection (bank_id, credited_amt, insert_login_id, created_date) VALUES ('$bank_id', '$remaining_amt', '$user_id', '$op_date')");
        
        if ($insert) {
            $response = "Remaining Amount Submitted Successfully";
        } else {
            $response = "Error While Submitting Remaining Amount";
        }
    } else {
        $response = "Today's Collection Already Submitted";
    }
}

echo $response;

// Close the database connection
$connect = null;
?>
