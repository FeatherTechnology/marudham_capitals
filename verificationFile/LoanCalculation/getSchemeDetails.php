<?php 
include('../../ajaxconfig.php');

if(isset($_POST['scheme_id'])){
    $scheme_id = $_POST['scheme_id'];
}
$detailrecords = array();


$result=$connect->query("SELECT * FROM loan_scheme where scheme_id = '".strip_tags($scheme_id)."' ");
$i=0;
while($row = $result->fetch()){
    $detailrecords['due_period'] = $row['due_period'];
    $detailrecords['intreset_type'] = $row['intreset_type'];
    $detailrecords['intreset_min'] = $row['intreset_min'];
    $detailrecords['intreset_max'] = $row['intreset_max'];
    $detailrecords['profit_method'] = $row['profit_method'];
    $detailrecords['doc_charge_type'] = $row['doc_charge_type'];
    $detailrecords['doc_charge_min'] = $row['doc_charge_min'];
    $detailrecords['doc_charge_max'] = $row['doc_charge_max'];
    $detailrecords['proc_fee_type'] = $row['proc_fee_type'];
    $detailrecords['proc_fee_min'] = $row['proc_fee_min'];
    $detailrecords['proc_fee_max'] = $row['proc_fee_max'];
    $i++;
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;
?>