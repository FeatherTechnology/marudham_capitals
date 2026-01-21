<?php
include '../ajaxconfig.php';

$id = $_POST['id'] ?? '';

$message = '';

if ($id != '') {
    // 🔹 Check whether feedback label is already used
    $checkQry = $connect->query("SELECT * FROM verification_cus_feedback WHERE feedback_label = '$id'");

    if ($checkQry->rowCount() > 0) {
        //  Already used
        $message = "USED";
    } else {
        // Safe to delete
        $delQry = $connect->query("DELETE FROM cus_feedback_name WHERE id = '$id'");

        if ($delQry) {
            $message = "DELETED";
        } else {
            $message = "ERROR";
        }
    }
}

echo json_encode($message);
$connect = null;
