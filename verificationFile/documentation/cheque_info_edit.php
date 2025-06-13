<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$ChequeDoc = array();

$chequeInfo = $connect->query("SELECT * FROM cheque_info WHERE id='$id' ");
$cheque_details = $chequeInfo->fetch();

$ChequeDoc['id'] = $cheque_details['id'];
$ChequeDoc['holder_type'] = $cheque_details['holder_type'];
$ChequeDoc['holder_name'] = $cheque_details['holder_name'];
$ChequeDoc['holder_relationship_name'] = $cheque_details['holder_relationship_name'];
$ChequeDoc['cheque_relation'] = $cheque_details['cheque_relation'];
$ChequeDoc['chequebank_name'] = $cheque_details['chequebank_name'];
$ChequeDoc['cheque_count'] = $cheque_details['cheque_count'];

$chequeInfo = $connect->query("SELECT cheque_no FROM cheque_no_list WHERE cheque_table_id ='$id' ");
while ($cheque_details = $chequeInfo->fetch()) {

    $ChequeDoc['cheque_no'][] = $cheque_details['cheque_no'];
}

echo json_encode($ChequeDoc);

// Close the database connection
$connect = null;