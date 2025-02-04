<?php
require '../ajaxconfig.php';

$id = $_POST['id'];

$bank = array();

$BANKInfo = $connect->query("SELECT * FROM `verification_bank_info` WHERE id='$id' ");
$bank_details = $BANKInfo->fetch();

$bank['id'] = $bank_details['id'];
$bank['bankName'] = $bank_details['bank_name'];
$bank['branch'] = $bank_details['branch_name'];
$bank['accHolderName'] = $bank_details['acc_holder_name'];
$bank['acc_no'] = $bank_details['acc_no'];
$bank['ifsc'] = $bank_details['ifsc_code'];

echo json_encode($bank);

// Close the database connection
$connect = null;