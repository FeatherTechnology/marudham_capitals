<?php
include('../ajaxconfig.php');

$role_type = [];
if (!empty($_POST['role_type'])) {
    $role_type = explode(',', $_POST['role_type']);
}

$selected_id = $_POST['selected_id'] ?? '';

$queryParts = [];
$params = [];

/* Part 1: Allowed roles */
if (!empty($role_type)) {
    $placeholders = rtrim(str_repeat('?,', count($role_type)), ',');
    $queryParts[] = "
        SELECT staff_type_id, staff_type_name 
        FROM staff_type_creation 
        WHERE staff_type_name IN ($placeholders)
    ";
    $params = array_merge($params, $role_type);
}

/* Part 2: Selected role (edit mode) */
if (!empty($selected_id)) {
    $queryParts[] = "
        SELECT staff_type_id, staff_type_name 
        FROM staff_type_creation 
        WHERE staff_type_id = ?
    ";
    $params[] = $selected_id;
}

/* If nothing to fetch */
if (empty($queryParts)) {
    echo json_encode([]);
    exit;
}

/* Combine with UNION */
$sql = implode(' UNION ', $queryParts) . " ORDER BY staff_type_name ASC";

$qry = $connect->prepare($sql);
$qry->execute($params);

echo json_encode($qry->fetchAll(PDO::FETCH_ASSOC));
$connect = null;
?>
