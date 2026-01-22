<?php 
include "../../ajaxConfig.php";

$cr_dr_type = $_POST['crdrType'] ?? '';
$bank_id = $_POST['bankId'] ?? '';
$trans_id = $_POST['transId'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);

$qry = $connect->prepare("SELECT id, bank_id, DATE(trans_date) AS trans_date, credit, debit, transaction_amount FROM bank_stmt WHERE bank_id = ? AND trans_id = ? AND $cr_dr_type !='' AND clr_status = '0' ");
$qry->execute([$bank_id, $trans_id]);
$row = $qry->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $trans_amnt = floatval(($row['transaction_amount'] > 0) ? $row['transaction_amount'] : 0); 
    $row['alert_status'] = false;
    if($amount > $trans_amnt){
        $row['alert_status'] = true;
        $row['alert'] = "Entered amount is more than transaction amount. Kindly enter a lesser amount.";
    }

    echo json_encode([
        'status' => true,
        'data' => $row
    ]);
} else {
    echo json_encode([
        'status' => false,
        'message' => 'Transaction not found'
    ]);
}

// Close the database connection
$connect = null;
?>