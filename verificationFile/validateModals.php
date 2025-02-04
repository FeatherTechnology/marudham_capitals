<?php
include('../ajaxconfig.php');
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}
if (isset($_POST['table'])) {
    $table = $_POST['table'];
}

$response = '';
$selectIC = $connect->query("SELECT * FROM $table WHERE cus_id = '" . $cus_id . "' ") or die('Error on Family Table');
if ($selectIC->rowCount() > 0) {
    $response = 1;
} else {
    $response = 0;
}

echo $response;

// Close the database connection
$connect = null;