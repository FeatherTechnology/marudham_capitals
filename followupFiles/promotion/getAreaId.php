<?php
include '../../ajaxconfig.php';
session_start();

$userid = $_SESSION["userid"];

$loan_category_arr = [];
$user_area = [];

// Get user access details
$stmt = $connect->prepare("
    SELECT promotion_activity_mapping_access, group_id, line_id, due_followup_lines
    FROM user
    WHERE status = 0 AND user_id = ?
");
$stmt->execute([$userid]);
$run = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$run) {
    $user_area = [];
} else {

    $accessType = (int)$run['promotion_activity_mapping_access'];

    if ($accessType === 1 && !empty($run['group_id'])) {
        // 🔹 Group-based access
        $group_ids = array_map('intval', array_filter(explode(',', $run['group_id'])));
        $placeholders = implode(',', array_fill(0, count($group_ids), '?'));

        $stmt = $connect->prepare("
            SELECT DISTINCT agmsa.area_id
            FROM area_group_mapping_area agmsa
            JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
            WHERE agm.status = 0 AND agmsa.group_map_id IN ($placeholders)
        ");
        $stmt->execute($group_ids);

        $user_area = $stmt->fetchAll(PDO::FETCH_COLUMN);

    } elseif ($accessType === 2 && !empty($run['line_id'])) {
        // 🔹 Line-based access
        $line_ids = array_map('intval', array_filter(explode(',', $run['line_id'])));
        $placeholders = implode(',', array_fill(0, count($line_ids), '?'));

        $stmt = $connect->prepare("
            SELECT DISTINCT alsa.area_id
            FROM area_line_mapping_area alsa
            JOIN area_line_mapping alm ON alm.map_id = alsa.line_map_id
            WHERE alm.status = 0 AND alsa.line_map_id IN ($placeholders)
        ");
        $stmt->execute($line_ids);

        $user_area = $stmt->fetchAll(PDO::FETCH_COLUMN);

    } elseif ($accessType === 3 && !empty($run['due_followup_lines'])) {
        // 🔹 DueFollowup-based access
        $due_ids = array_map('intval', array_filter(explode(',', $run['due_followup_lines'])));
        $placeholders = implode(',', array_fill(0, count($due_ids), '?'));

        $stmt = $connect->prepare("
            SELECT DISTINCT adma.area_id
            FROM area_duefollowup_mapping_area adma
            JOIN area_duefollowup_mapping adm ON adm.map_id = adma.duefollowup_map_id
            WHERE adm.status = 0 AND adma.duefollowup_map_id IN ($placeholders)
        ");
        $stmt->execute($due_ids);

        $user_area = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

// ✅ Remove duplicates & reindex
$user_area = array_values(array_unique(array_map('intval', $user_area)));
// ✅ Get area names
if (!empty($user_area)) {
    $areaIds = implode(',', $user_area);

    $result = $connect->query(" SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND area_enable = 0 
          AND area_id IN ($areaIds)  ");

    while ($row = $result->fetch()) {
        $loan_category_arr[] = [
            "area_id"   => $row['area_id'],
            "area_name" => $row['area_name']
        ];
    }
}

echo json_encode($loan_category_arr);
$connect = null;
?>
