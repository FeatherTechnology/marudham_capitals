<?php
require '../ajaxconfig.php';

$req_id       = $_POST['reqId'];
$cus_id       = $_POST['cus_id'];
$proofof       = $_POST['proofof'];
$fam_mem       = $_POST['fam_mem'];
$proof_type    = $_POST['proof_type'];
$proof_number  = $_POST['proof_number'];
$kycID         = $_POST['kycID'];
$uniqueFileName = '';

if(!empty($_FILES['upload']['name'])){
    $upload    = $_FILES['upload']['name'];

    $path = "../uploads/verification/kycUploads/";
    $fileName = $_FILES['upload']['name'];
    $filePath = $_FILES['upload']['tmp_name'];

    $fileExtension = pathinfo($path . $fileName, PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '.' . $fileExtension;

    while (file_exists($path . $uniqueFileName)) {
        $uniqueFileName = uniqid() . '.' . $fileExtension;
    }

    if (move_uploaded_file($filePath, $path . $uniqueFileName)) {
        echo "The file " . $fileName . " has been uploaded";
    } else {
        echo "There was an error uploading the file, please try again!";
        $uniqueFileName = '';
    }
}



if ($kycID == '') {

    $qry = $connect->query("INSERT INTO `verification_kyc_info`(`cus_id`, `req_id`, `proofOf`,`fam_mem`, `proof_type`, `proof_no`, `upload`) VALUES ('$cus_id','$req_id','$proofof','$fam_mem','$proof_type','$proof_number','$uniqueFileName')");
} else {

    if (!empty($_FILES['upload']['name'])) {
        $kyc_upload = $uniqueFileName;
        // we need to unlink old files
        $qry = $connect->query("SELECT upload FROM `verification_kyc_info` where id='" . strip_tags($kycID) . "' ");
        $old_pic = $qry->fetch()['upload'];
        unlink("../uploads/verification/kycUploads/" . $old_pic);
    } else {
        $kyc_upload = $_POST['kyc_upload'];
    }

    $qry = $connect->query("UPDATE `verification_kyc_info` SET `cus_id`='$cus_id',`req_id`='$req_id',`proofOf`='$proofof',`fam_mem`='$fam_mem',`proof_type`='$proof_type',`proof_no`='$proof_number',`upload`='$kyc_upload' WHERE `id`='$kycID'");
}

if ($qry) {
    $result = "KYC Info Inserted Successfully.";
} elseif ($update) {
    $result = "KYC Info Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
