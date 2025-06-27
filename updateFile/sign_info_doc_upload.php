<?php
require '../ajaxconfig.php';

$req_id                  = $_POST['req_id'];
$cus_id                  = $_POST['cus_id'];
$signedID                = $_POST['signedID'];
$sign_type               = $_POST['sign_type'];
$signType_relationship   = $_POST['signType_relationship'];
$doc_Count               = $_POST['doc_Count'];
$fileArray               = $_FILES['signdoc_upd'] ?? '';

if ($signedID == '') {

    if ($sign_type == '1') {
        $qry = $connect->query("SELECT fam.id from verification_family_info fam JOIN customer_profile cp on cp.guarentor_name = fam.id where cp.req_id = $req_id");
        $signType_relationship = $qry->fetch()['id'];
    }

    $qry = $connect->query("SELECT id FROM `customer_profile` WHERE `req_id` = $req_id");
    $cus_profile_id = $qry->fetch()['id'];

    $update = $connect->query("INSERT INTO `signed_doc_info`(`cus_id`,`doc_name`, `sign_type`, `signType_relationship`, `doc_Count`, `req_id`, `cus_profile_id`) VALUES ('$cus_id','0','$sign_type','$signType_relationship','$doc_Count','$req_id','$cus_profile_id')");
    
    $signedID = $connect->lastInsertId();

} else {
    $update = $connect->query("UPDATE `signed_doc_info` SET `doc_name`= '0', `sign_type`= '$sign_type', `signType_relationship`= '$signType_relationship', `doc_Count`= '$doc_Count' WHERE `id`= '$signedID' ");

}

// $connect->query("DELETE FROM `signed_doc` WHERE `signed_doc_id` ='$signedID'");
if(!empty($fileArray)){
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
}

if($update){
    $result = "Signed Doc Info Uploaded Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;