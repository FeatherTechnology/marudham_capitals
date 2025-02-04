<?php
require '../ajaxconfig.php';

$req_id                = $_POST['req_id'];
$cus_id                = $_POST['cus_id'];
$signedID              = $_POST['signedID'];
$fileArray             = $_FILES['signdoc_upd'];

// $connect->query("DELETE FROM `signed_doc` WHERE `signed_doc_id` ='$signedID'");

foreach($fileArray['name'] as $key=>$val) {
    $fileName = basename($fileArray['name'][$key]);  
    $targetFilePath = "../uploads/verification/signed_doc/".$fileName; 
    
    $fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $uniqueFileName = uniqid() . '.' . $fileExtension;
    while(file_exists("../uploads/verification/signed_doc/".$uniqueFileName)){
        $uniqueFileName = uniqid() . '.' . $fileExtension;
    }

    if(move_uploaded_file($fileArray["tmp_name"][$key], "../uploads/verification/signed_doc/" . $uniqueFileName)){  
        $update =  $connect->query("INSERT INTO `signed_doc`(`req_id`,`cus_id`,`signed_doc_id`, `upload_doc_name`) VALUES ('$req_id','$cus_id','$signedID','$uniqueFileName')");
    }
}


if($update){
    $result = "Signed Doc Info Uploaded Successfully.";
}

echo json_encode($result);
