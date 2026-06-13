<?php
session_start();
$user_id = $_SESSION["userid"];
require '../ajaxconfig.php';

$req_id                = $_POST['reqId'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$feedback_label        = $_POST['feedback_label'];
$cus_feedback              = $_POST['cus_feedback'];
$feedback_remark              = $_POST['feedback_remark'];
$feedbackID              = $_POST['feedbackID'];

$uploadedFiles = '';

if (isset($_FILES['customer_summary_uploads'])) {

    $filesArr3 = $_FILES['customer_summary_uploads'];
    $uploadDir = "../uploads/customer_summary/";

    foreach ($filesArr3['name'] as $key => $val) {

        $fileName = basename($filesArr3['name'][$key]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        $uniqueFileName = uniqid() . '.' . $fileType;

        while (file_exists($uploadDir . $uniqueFileName)) {
            $uniqueFileName = uniqid() . '.' . $fileType;
        }

        if (
            move_uploaded_file(
                $filesArr3['tmp_name'][$key],
                $uploadDir . $uniqueFileName
            )
        ) {
            $uploadedFiles .= $uniqueFileName . ',';
        }
    }
}

if ($feedbackID == '') {
    $insert_qry = $connect->query("INSERT INTO `verification_cus_feedback`( `cus_id`,`req_id`, `feedback_label`, `cus_feedback`, `upload`, `feedback_remark`,`insert_login_id`,`inserted_date`) VALUES ('$cus_id','$req_id','$feedback_label','$cus_feedback','$uploadedFiles', '$feedback_remark','$user_id',now())");
} else {
        
    if (!empty($_FILES['customer_summary_uploads']['name'])) {
        // we need to unlink old files
        $qry = $connect->query("SELECT upload FROM `verification_cus_feedback` where `id`='$feedbackID' ");
        $old_pic = $qry->fetch()['upload'];
        $files = array_filter(array_map('trim', explode(',', $old_pic)));

        foreach ($files as $file) {
            $filePath = "../uploads/customer_summary/" . $file;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

    } else {
        $uploadedFiles = $_POST['cus_summary_upload'];
    }

    $update = $connect->query("UPDATE `verification_cus_feedback` SET `cus_id`='$cus_id',`req_id`='$req_id',`feedback_label`='$feedback_label',`cus_feedback`='$cus_feedback', `upload` = '$uploadedFiles',`feedback_remark`='$feedback_remark' WHERE `id`='$feedbackID' ");
}

if ($insert_qry) {
    $result = "Feedback Inserted Successfully.";
} elseif ($update) {
    $result = "Feedback Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;