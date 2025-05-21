<?php
session_start();

require '../ajaxconfig.php';

if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
}
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}
if(isset($_POST['doc_info_id'])){
    $doc_id = $_POST['doc_info_id'];
}
if (isset($_POST['doc_name'])) {
    $doc_name = $_POST['doc_name'];
}
if (isset($_POST['doc_details'])) {
    $doc_details = $_POST['doc_details'];
}
if (isset($_POST['doc_type'])) {
    $doc_type = $_POST['doc_type'];
}
if (isset($_POST['doc_holder'])) {
    $doc_holder = $_POST['doc_holder'];
}
if (isset($_POST['holder_name'])) {
    $holder_name = $_POST['holder_name'];
}
if (isset($_POST['relation_name'])) {
    $relation_name = $_POST['relation_name'];
}
if (isset($_POST['relation'])) {
    $relation = $_POST['relation'];
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

if ($doc_id == '') {

    $insert_qry = $connect->query("INSERT INTO `document_info`(`cus_id`, `req_id`, `doc_name`, `doc_detail`, `doc_type`, `doc_holder`, `holder_name`,`relation_name`, `relation`, `doc_upload`, `insert_login_id`,`created_date`) VALUES ('$cus_id','$req_id','$doc_name','$doc_details','$doc_type','$doc_holder','$holder_name','$relation_name','$relation','$doc_upload','$userid',now())");

    if ($insert_qry) {
        $result = "Document Info Inserted Successfully.";
    }

} else {

    $update = $connect->query("UPDATE `document_info` SET `doc_name` = '$doc_name', `doc_detail` = '$doc_details', `doc_type` = '$doc_type', `doc_holder` = '$doc_holder',  `holder_name` = '$holder_name', `relation_name` = '$relation_name', `relation` = '$relation', `doc_upload` = '$doc_upload', `update_login_id` = $userid, `updated_date` = NOW()  WHERE `id` = '$doc_id' ");

    if($update){
        $result = "Document Info Updated Successfully.";
    }

}


echo $result;
?>
