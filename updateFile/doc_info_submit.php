<?php
session_start();

require '../ajaxconfig.php';

if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
}
if(isset($_POST['doc_info_id'])){
    $doc_id = $_POST['doc_info_id'];
}

$doc_upload ='';
if(isset($_FILES['document_info_upd'])){

    $fileArray = $_FILES['document_info_upd'];
    $uploadDir = "../uploads/verification/doc_info/";
    $doc_upload = '';

    foreach ($fileArray['name'] as $key => $val) {
        $fileName = basename($fileArray['name'][$key]);
        $targetFilePath = $uploadDir . $fileName;

        $fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $uniqueFileName = uniqid() . '.' . $fileExtension;
        while (file_exists($uploadDir . $uniqueFileName)) {
            $uniqueFileName = uniqid() . '.' . $fileExtension;
        }

        if (move_uploaded_file($fileArray["tmp_name"][$key], $uploadDir . $uniqueFileName)) {
            $doc_upload .= $uniqueFileName . ',';
        }
    }
    $doc_upload = rtrim($doc_upload, ',');

}


    $update = $connect->query("UPDATE `document_info` SET `doc_upload`='$doc_upload',`update_login_id`= $userid,`updated_date`=now()  WHERE `id`='$doc_id' ");

    if($update){
        $result = "Document Info Updated Successfully.";
    }



echo $result;
?>
