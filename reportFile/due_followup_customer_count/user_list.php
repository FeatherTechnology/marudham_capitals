<?php
include('../../ajaxconfig.php');

$result = [];

$user_type = $_POST['user_type'] ?? '';

// Build condition
$where = "due_followup_lines IS NOT NULL AND due_followup_lines != ''";

if ($user_type == '2') {
    $where .= " AND status = 0"; // Active
} elseif ($user_type == '3') {
    $where .= " AND status = 1"; // Inactive
}
// If '1' (All) → no status filter

$qry = $connect->query("SELECT user_id, fullname
    FROM user
    WHERE $where
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null;
echo json_encode($result);