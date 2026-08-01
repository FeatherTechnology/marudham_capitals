<?php
include('../ajaxconfig.php');

$team_id = $_POST['id'];

/* Check in staff_creation */
$staffQry = $connect->query("SELECT COUNT(staff_id) AS total FROM staff_creation WHERE team = '$team_id'");
$staffData = $staffQry->fetch();

if ($staffData['total'] > 0) {

    echo json_encode([
        "status" => "warning",
        "response" => "This Team Name is already used and cannot be deleted."
    ]);
    exit;
}

/* Delete Team */
$deleteqry = $connect->query("DELETE FROM team_creation WHERE id = '$team_id'");

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
