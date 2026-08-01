<?php
include('../ajaxconfig.php');

$dep_id = $_POST['id'];

/* Check in concern_creation */
$concernQry = $connect->query("SELECT COUNT(id) AS total FROM concern_creation WHERE to_dept_name = '$dep_id'");
$concernData = $concernQry->fetch();

/* Check in staff_creation */
$staffQry = $connect->query("SELECT COUNT(staff_id) AS total FROM staff_creation WHERE department = '$dep_id'");
$staffData = $staffQry->fetch();

if ($concernData['total'] > 0 || $staffData['total'] > 0) {

    echo json_encode([
        "status" => "warning",
        "response" => "This Department Name is already used and cannot be deleted."
    ]);
    exit;
}

/* Delete department */
$deleteqry = $connect->query("DELETE FROM concern_dept_name WHERE id = '$dep_id'");

if ($deleteqry) {

    echo json_encode([
        "status" => "success",
        "response" => "Deleted Successfully"
    ]);
} else {

    echo json_encode([
        "status" => "error",
        "response" => "Error While Deleting"
    ]);
}

$connect = null;
