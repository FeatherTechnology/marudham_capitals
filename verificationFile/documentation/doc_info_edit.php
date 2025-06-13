<?php
require '../../ajaxconfig.php';

$id = $_POST['id'];
		
//in verification doc insert is just information, acknowledgement is final and they add newly so verification & approval have seperate table. changes happen after deployment.
if(isset($_POST['verification_doc']) && $_POST['verification_doc'] == '1'){
	$tablename = 'verification_document_info';
	
}else{
	$tablename = 'document_info';
	
}

$response = array();

$qry = $connect->query("SELECT * FROM $tablename WHERE id='$id' ");
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