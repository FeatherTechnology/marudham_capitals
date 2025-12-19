<?php
include('../ajaxconfig.php');

$id = $_POST['id'];

$qry = $connect->query("UPDATE `concern_creation` SET status = 2  WHERE id = '$id'");
if ($qry) {
    $result = 1;
} else {
    $result = 2;
}



$connect = null; // Close Connection

echo json_encode($result);
