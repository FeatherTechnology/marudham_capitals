<?php
require "../../ajaxconfig.php";

$type = $_POST['type'] ?? '';

if ($type == '5') {

    $qry = $connect->query("SELECT id, dep_name AS name FROM concern_dept_name ORDER BY dep_name ASC");
} elseif ($type == '6') {

    $qry = $connect->query("SELECT id, team_name AS name FROM team_creation ORDER BY team_name ASC");
} else {
    echo json_encode([]);
    exit;
}

$result = $qry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
