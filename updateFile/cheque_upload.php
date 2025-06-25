<?php
require '../ajaxconfig.php';

$req_id                     = $_POST['req_id'];
$cus_id                     = preg_replace('/\D/', '', $_POST['cus_id']);
$holder_type                = $_POST['holder_type'];
$chequebank_name            = $_POST['chequebank_name'];
$cheque_count               = $_POST['cheque_count'];
$chequeID                   = $_POST['chequeID'];
$cheque_relation            = $_POST['cheque_relation'];
$cheque_upd_no              = explode(',',$_POST['cheque_upd_no']);//stored each numbers in an array
$filesArray                 = $_FILES['cheque_upd'] ?? '';//files passed as array
$holder_name                = '';
$holder_relationship_name   = '';

if($holder_type == '0' || $holder_type == '1'){
    $holder_name = $_POST['holder_name'];
}else{
    $holder_relationship_name = $_POST['holder_relationship_name'];
}

$holderName = ($holder_type == '0' || $holder_type == '1') ? $holder_name : $holder_relationship_name;

// $connect->query("DELETE FROM `cheque_upd` WHERE `cheque_table_id`='$chequeID'");
// $connect->query("DELETE FROM `cheque_no_list` WHERE `cheque_table_id`='$chequeID'");

if ($chequeID == '') {

    $qry = $connect->query("SELECT id FROM `customer_profile` WHERE `req_id` = $req_id");
    $cus_profile_id = $qry->fetch()['id'];

    $update = $connect->query("INSERT INTO `cheque_info`(`cus_id`,`req_id`, `cus_profile_id`, `holder_type`, `holder_name`, `holder_relationship_name`, `cheque_relation`, `chequebank_name`, `cheque_count`) VALUES ('$cus_id','$req_id','$cus_profile_id','$holder_type','$holder_name','$holder_relationship_name','$cheque_relation','$chequebank_name','$cheque_count')");

    $chequeID = $connect->lastInsertId();

} else {
    $update = $connect->query("UPDATE `cheque_info` SET `holder_type`= '$holder_type', `holder_name`= '$holder_name', `holder_relationship_name`= '$holder_relationship_name', `cheque_relation`= '$cheque_relation', `chequebank_name`= '$chequebank_name', `cheque_count`= '$cheque_count' WHERE `id`= '$chequeID' ");
}

$connect->query("DELETE FROM `cheque_no_list` WHERE `cheque_table_id`='$chequeID'");

if(!empty($fileArray)){
    $connect->query("DELETE FROM `cheque_upd` WHERE `cheque_table_id`='$chequeID'");
    
    foreach($filesArray['name'] as $key=>$val)
    {
        $fileName = basename($filesArray['name'][$key]);  
        $targetFilePath = "../uploads/verification/cheque_upd/".$fileName; 
    
        $fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        $uniqueFileName = uniqid() . '.' . $fileExtension;
        while(file_exists("../uploads/verification/cheque_upd/".$uniqueFileName)){
            $uniqueFileName = uniqid() . '.' . $fileExtension;
        }
    
        // Upload file to server  
        if(move_uploaded_file($filesArray["tmp_name"][$key], "../uploads/verification/cheque_upd/" . $uniqueFileName)){  
            $update =  $connect->query("INSERT INTO `cheque_upd`(`cus_id`,`req_id`, `cheque_table_id`, `upload_cheque_name`) VALUES ('$cus_id','$req_id','$chequeID','$uniqueFileName')");
        }
    }
}


foreach($cheque_upd_no as $chequeNo){
    $update  = $connect->query("INSERT INTO `cheque_no_list`( `req_id`,`cus_id`,`cheque_table_id`, `cheque_holder_type`, `cheque_holder_name`, `cheque_no`) 
    VALUES ('$req_id','$cus_id','$chequeID',' $holder_type','$holderName','$chequeNo')");
}


if($update){
    $result = "Cheque Uploaded Successfully.";
}

echo json_encode($result);
?>