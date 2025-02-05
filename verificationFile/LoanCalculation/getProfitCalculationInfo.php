<?php 
session_start();
include('../../ajaxconfig.php');

// if(isset($_SESSION['userid'])){
//     $userid = $_SESSION['userid'];
// }
if(isset($_POST['sub_cat'])){
    $sub_cat = $_POST['sub_cat'];
}
if(isset($_POST['loan_cat'])){
    $loan_cat = $_POST['loan_cat'];
}
$detailrecords = array();


$result=$connect->query("SELECT * FROM loan_calculation where sub_category = '".strip_tags($sub_cat)."' and '".strip_tags($loan_cat)."' ");
$i=0;
while($row = $result->fetch()){
    $detailrecords['due_type'] = $row['due_type'];
    $detailrecords['profit_method'] = $row['profit_method'];
    $detailrecords['calculate_method'] = $row['calculate_method'];
    $detailrecords['intrest_rate_min'] = $row['intrest_rate_min'];
    $detailrecords['intrest_rate_max'] = $row['intrest_rate_max'];
    $detailrecords['due_period_min'] = $row['due_period_min'];
    $detailrecords['due_period_max'] = $row['due_period_max'];
    $detailrecords['doc_charge_type'] = $row['doc_charge_type'];
    $detailrecords['document_charge_min'] = $row['document_charge_min'];
    $detailrecords['document_charge_max'] = $row['document_charge_max'];
    $detailrecords['proc_fee_type'] = $row['proc_fee_type'];
    $detailrecords['processing_fee_min'] = $row['processing_fee_min'];
    $detailrecords['processing_fee_max'] = $row['processing_fee_max'];
    $i++;
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;
?>