<?php
require '../ajaxconfig.php';

$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$bank_name             = $_POST['bank_name'];
$branch_name           = $_POST['branch_name'];
$account_holder_name   = $_POST['account_holder_name'];
$account_number        = $_POST['account_number'];
$Ifsc_code             = $_POST['Ifsc_code'];
$bankID                = $_POST['bankID'];

if(!empty($_FILES['bank_upload']['name'])){
    $upload    = $_FILES['bank_upload']['name'];

    $path = "../uploads/bankUploads/";
    $fileName = $_FILES['bank_upload']['name'];
    $filePath = $_FILES['bank_upload']['tmp_name'];

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

if($bankID == ''){

$insert_qry = $connect ->query("INSERT INTO `verification_bank_info`(`cus_id`, `bank_name`, `branch_name`, `acc_holder_name`, `acc_no`, `ifsc_code`,`upload`) VALUES ('$cus_id','$bank_name','$branch_name','$account_holder_name','$account_number','$Ifsc_code','$uniqueFileName')");

}
else{
    if (!empty($_FILES['bank_upload']['name'])) {
        $bank_upload_id = $uniqueFileName;
        // we need to unlink old files
        $qry = $connect->query("SELECT upload FROM `verification_bank_info` where id='" . strip_tags($bankID) . "' ");
        $old_pic = $qry->fetch()['upload'];
        unlink("../uploads/bankUploads/" . $old_pic);
    } else {
        $bank_upload_id = $_POST['bank_upload_id'];
    }
$insert_qry = $connect->query("UPDATE `verification_bank_info` SET `cus_id`='$cus_id',`bank_name`='$bank_name',`branch_name`='$branch_name',`acc_holder_name`='$account_holder_name',`acc_no`='$account_number',`ifsc_code`='$Ifsc_code',`upload`='$bank_upload_id' WHERE `id`='$bankID'");

}

if($insert_qry){
    $result = "Bank Info Inserted Successfully.";
}
elseif($update){
    $result = "Bank Info Updated Successfully.";
}

echo json_encode($result);
