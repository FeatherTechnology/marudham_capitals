<?php
require '../ajaxconfig.php';


if (isset($_POST['reqId'])) {
    $reqId = $_POST['reqId'];
}
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

if (isset($_POST['proof'])) {
    $proof = $_POST['proof'];
}

if (isset($_POST['fam_name'])) {
    $fam_name = $_POST['fam_name'];
} else {
    $fam_name = '';
}

$KYCProof = array();
$proofs = "SELECT proof_type FROM `verification_kyc_info` WHERE cus_id = '" . $cus_id . "' && proofOf ='" . $proof . "' ";

if ($fam_name != '') {
    $proofs .= " && fam_mem ='" . $fam_name . "' ";
}

$proofs = $connect->query($proofs);
$cnt = $proofs->rowCount();
if ($cnt > 0) {
    while ($kyc_Proof = $proofs->fetch()) {
        $KYCProof[] = $kyc_Proof['proof_type'];
    }
}

echo json_encode($KYCProof);

// Close the database connection
$connect = null;
