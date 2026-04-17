<?php
include('../../ajaxconfig.php');

$result = [];

$user_type = $_POST['user_type'] ?? '';

// Build condition
$where = "la.user_id IS NOT NULL";

if ($user_type == '2') {
    $where .= " AND u.status = 0"; // Active
} elseif ($user_type == '3') {
    $where .= " AND u.status = 1"; // Inactive
}
// If '1' (All) → no status filter

$qry = $connect->query("SELECT u.user_id, u.fullname
    FROM user u
    LEFT JOIN location_audit la ON u.user_id = la.user_id
    WHERE $where
    GROUP BY u.fullname
    ORDER BY u.fullname ASC
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null;
echo json_encode($result);