<?php
require '../ajaxconfig.php';

$id = $_POST['id'];

$kyc = array();

$BANKInfo = $connect->query("SELECT * FROM `verification_kyc_info` WHERE id='$id' ");
$bank_details = $BANKInfo->fetch();

$kyc['id'] = $bank_details['id'];
$kyc['proofOf'] = $bank_details['proofOf'];
$kyc['fam_mem'] = $bank_details['fam_mem'];
$kyc['proofType'] = $bank_details['proof_type'];
$kyc['proofNo'] = $bank_details['proof_no'];
$kyc['upload'] = $bank_details['upload'];

echo json_encode($kyc);

// Close the database connection
$connect = null;