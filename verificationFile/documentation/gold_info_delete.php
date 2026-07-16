<?php
include '../../ajaxconfig.php';

$id = $_POST['chequeid'];

$filePath = "../../uploads/gold_info/";

// Get uploaded files
$stmt = $connect->prepare("SELECT gold_upload FROM gold_info WHERE id = ?");
$stmt->execute([$id]);

$files = $stmt->fetchColumn();

if (!empty($files)) {

    $docUpd = array_map('trim', explode(',', $files));

    foreach ($docUpd as $fileinfo) {

        $file = $filePath . $fileinfo;

        if (!empty($fileinfo) && file_exists($file)) {
            unlink($file);
        }
    }
}

$delct = $connect->query("DELETE FROM gold_info WHERE id = '$id' ");

if ($delct) {
	$message = " Gold Info Deleted Successfully";
}


echo json_encode($message);

// Close the database connection
$connect = null;