<?php
session_start();
include '../ajaxconfig.php';

if (isset($_POST["req_id"])) {
    $req_id = $_POST["req_id"];
}

$response = 'completed';

$sts_qry = $connect->query("SELECT doc_sts FROM acknowlegement_documentation WHERE req_id = '$req_id' ");

$sts_row = $sts_qry->fetch();
if ($sts_row['doc_sts'] == 'NO') {
    $response = 'pending';
}

echo ($response == 'completed') ? true : false;

// Close the database connection
$connect = null;
?>
