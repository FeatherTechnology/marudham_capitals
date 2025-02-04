<?php
require '../ajaxconfig.php';

$req_id              = $_POST['req_id'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$holder_type           = $_POST['holder_type'];
$chequebank_name              = $_POST['chequebank_name'];
$cheque_count              = $_POST['cheque_count'];
$chequeID              = $_POST['chequeID'];
$cheque_upd_no         = explode(',',$_POST['cheque_upd_no']);//stored each numbers in an array

if($holder_type == '0' || $holder_type == '1'){
    $holderName = $_POST['holder_name'];
}else{
    $holderName = $_POST['holder_relationship_name'];
}

// $connect->query("DELETE FROM `cheque_upd` WHERE `cheque_table_id`='$chequeID'");
// $connect->query("DELETE FROM `cheque_no_list` WHERE `cheque_table_id`='$chequeID'");

$filesArray = $_FILES['cheque_upd'];//files passed as array

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


foreach($cheque_upd_no as $chequeNo){
    $insert  = $connect->query("INSERT INTO `cheque_no_list`( `req_id`,`cus_id`,`cheque_table_id`, `cheque_holder_type`, `cheque_holder_name`, `cheque_no`) 
    VALUES ('$req_id','$cus_id','$chequeID',' $holder_type','$holderName','$chequeNo')");
}


if($update && $insert){
    $result = "Cheque Uploaded Successfully.";
}

echo json_encode($result);
?>