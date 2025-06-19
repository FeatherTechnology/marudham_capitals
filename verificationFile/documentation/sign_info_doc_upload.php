<?php
require '../../ajaxconfig.php';

$req_id                = $_POST['doc_req_id'];
$cus_id                = $_POST['doc_cus_id'];
$sign_type             = $_POST['sign_type'];
$signType_relationship = $_POST['signType_relationship'];
$doc_Count             = $_POST['doc_Count'];
$cus_profile_id        = $_POST['doc_cus_profile_id'];
$signedID              = $_POST['signedID'];
$filesArr3             = $_FILES['signdoc_upd'];

if ($sign_type == '1') {
    $qry = $connect->query("SELECT fam.id from verification_family_info fam JOIN customer_profile cp on cp.guarentor_name = fam.id where cp.req_id = $req_id");
    $signType_relationship = $qry->fetch()['id'];
}

if ($signedID == '') {

    $update = $connect->query("INSERT INTO `signed_doc_info`(`cus_id`,`doc_name`, `sign_type`, `signType_relationship`, `doc_Count`, `req_id`, `cus_profile_id`) VALUES ('$cus_id','0','$sign_type','$signType_relationship','$doc_Count','$req_id','$cus_profile_id')");
    $signedID = $connect->lastInsertId();
    
} else {
    $update = $connect->query("UPDATE `signed_doc_info` SET `cus_id`='$cus_id',`doc_name`='0',`sign_type`='$sign_type',`signType_relationship`='$signType_relationship',`doc_Count`='$doc_Count' WHERE `id`='$signedID' ");
}


$connect->query("DELETE FROM `signed_doc` WHERE `signed_doc_id` ='$signedID'");

foreach ($filesArr3['name'] as $key => $val) {
    $fileName = basename($filesArr3['name'][$key]);
    $targetFilePath = "../../uploads/verification/signed_doc/" . $fileName;

    $fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $uniqueFileName = uniqid() . '.' . $fileExtension;
    while (file_exists("../../uploads/verification/signed_doc/" . $uniqueFileName)) {
        $uniqueFileName = uniqid() . '.' . $fileExtension;
    }

    if (move_uploaded_file($filesArr3["tmp_name"][$key], "../../uploads/verification/signed_doc/" . $uniqueFileName)) {
        $update =  $connect->query("INSERT INTO `signed_doc`(`cus_id`,`req_id`,`signed_doc_id`, `upload_doc_name`) VALUES ('$cus_id','$req_id','$signedID','$uniqueFileName')");
    }
}


if ($update) {
    $result = "Signed Doc Info Uploaded Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;