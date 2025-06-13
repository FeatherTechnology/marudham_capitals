<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];

$response = array();

$qry = $connect->query("SELECT * FROM document_info WHERE id='$id' ");
$row = $qry->fetch();

$response['doc_id'] = $row['id'];
$response['req_id'] = $row['req_id'];
$response['doc_name'] = $row['doc_name'];
$response['doc_details'] = $row['doc_detail'];
$response['doc_type'] = $row['doc_type'];
$response['doc_holder'] = $row['doc_holder'];
$response['holder_name'] = $row['holder_name'];
$response['relation_name'] = $row['relation_name'];
$response['relation'] = $row['relation'];

echo json_encode($response);

// Close the database connection
$connect = null;