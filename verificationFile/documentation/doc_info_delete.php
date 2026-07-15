<?php
include '../../ajaxconfig.php';

$id = $_POST['id'];

$filePath = "../../uploads/verification/doc_info/";

// Get uploaded files
$stmt = $connect->prepare("SELECT doc_upload FROM document_info WHERE id = ?");
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

// Delete database record
$delct = $connect->prepare("DELETE FROM document_info WHERE id = ?");
$delct->execute([$id]);

$message = $delct->rowCount()
    ? "Document Info Deleted Successfully"
    : "No record found";

echo json_encode($message);

$connect = null;