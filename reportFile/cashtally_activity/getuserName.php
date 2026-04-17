<?php
include('../../ajaxconfig.php');

$result = [];

$user_type = $_POST['user_type'] ?? '';

// Build condition
$where = "cash_tally = 0 AND user_id !=1";

if ($user_type == '2') {
    $where .= " AND status = 0"; // Active
} elseif ($user_type == '3') {
    $where .= " AND status = 1"; // Inactive
}
// If '1' (All) → no status filter

$qry = $connect->query("SELECT fullname, user_id AS user_ids
    FROM user
    WHERE $where
    GROUP BY fullname
    ORDER BY fullname ASC
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null;
echo json_encode($result);