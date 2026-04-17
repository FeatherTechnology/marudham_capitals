<?php
include '../../ajaxconfig.php';

$response = array();
$i = 0;

$user_track = isset($_POST['user_track']) ? $_POST['user_track'] : '';
$user_type = $_POST['user_type'] ?? '';

$where = "1=1";

if ($user_track == '1') {
    // all users (no extra filter)
} else if ($user_track == '2') {
    $where .= " AND confirmation_followup = 0";
} else {
    $where .= " AND (collection = 0 OR due_followup = 0)";
}

if ($user_type == '2') {
    $where .= " AND status = 0"; // Active
} elseif ($user_type == '3') {
    $where .= " AND status = 1"; // Inactive
}

// If '1' (All) → no status filter

$qry = $connect->query("SELECT fullname, GROUP_CONCAT(user_id ORDER BY user_id ASC) AS user_ids 
                        FROM user 
                        WHERE $where
                        GROUP BY fullname 
                        ORDER BY fullname ASC");

while ($row = $qry->fetch()) {
    $response[$i]['user_id'] = $row['user_ids'];
    $response[$i]['username'] = $row['fullname'];
    $i++;
}

echo json_encode($response);

$connect = null;
