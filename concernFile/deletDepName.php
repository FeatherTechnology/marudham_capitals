<?php
include('../ajaxconfig.php');

$dep_id = $_POST['id'];

/* 🔹 Check if department used */
$expQry = $connect->query("SELECT COUNT(id) as total FROM concern_creation WHERE to_dept_name = '$dep_id'");
$expData = $expQry->fetch();

if ($expData['total'] > 0) {

    echo json_encode([
        "status" => "warning",
        "response" => "This Department Name is already used in concern creation..!"
    ]);
    exit;
}

/* 🔹 Delete department */
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
?>