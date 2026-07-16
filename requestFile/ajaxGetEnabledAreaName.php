<?php
include('../ajaxconfig.php');
session_start();

$userid = $_SESSION['userid'] ?? '';
$taluk  = $_POST['talukselected'] ?? '';
$type   = $_POST['type'] ?? '';

if ($type == 'request') {

    // Get user access details
    $stmt = $connect->prepare("
        SELECT group_id, line_id, due_followup_lines, req_mapping_access
        FROM user
        WHERE user_id = ? AND status = 0
    ");
    $stmt->execute([$userid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $accessMap = [
        1 => ['group_id', 'area_group_mapping_area', 'group_map_id', 'area_id'],
        2 => ['line_id', 'area_line_mapping_area', 'line_map_id', 'area_id'],
        3 => ['due_followup_lines', 'area_duefollowup_mapping_area', 'duefollowup_map_id', 'area_id']
    ];

    $accessType = (int)$user['req_mapping_access'];

    if (!isset($accessMap[$accessType])) {
        echo json_encode([]);
        exit;
    }

    [$source, $table, $mapCol, $selCol] = $accessMap[$accessType];

    $ids = array_filter(array_map('intval', explode(',', $user[$source] ?? '')));

    if (empty($ids)) {
        echo json_encode([]);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $connect->prepare("
        SELECT DISTINCT $selCol
        FROM $table
        WHERE $mapCol IN ($placeholders)
    ");
    $stmt->execute($ids);

    $allowedAreas = array_values(array_filter($stmt->fetchAll(PDO::FETCH_COLUMN)));

} else {

    // Existing Group-based code
    $stmt = $connect->prepare("
        SELECT group_id
        FROM user
        WHERE status = 0 AND user_id = ?
    ");
    $stmt->execute([$userid]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $groupIds = array_filter(array_map('intval', explode(',', $user['group_id'])));

    if (empty($groupIds)) {
        echo json_encode([]);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($groupIds), '?'));

    $stmt = $connect->prepare("
        SELECT DISTINCT agma.area_id
        FROM area_group_mapping_area agma
        INNER JOIN area_group_mapping agm
            ON agma.group_map_id = agm.map_id
        WHERE agm.status = 0
          AND agm.map_id IN ($placeholders)
    ");
    $stmt->execute($groupIds);

    $allowedAreas = array_values(array_filter($stmt->fetchAll(PDO::FETCH_COLUMN)));
}

if (empty($allowedAreas)) {
    echo json_encode([]);
    exit;
}

$areaPlaceholders = implode(',', array_fill(0, count($allowedAreas), '?'));

$params = array_merge(["%$taluk%"], $allowedAreas);

$stmt = $connect->prepare("
    SELECT area_id, area_name
    FROM area_list_creation
    WHERE taluk LIKE ?
      AND status = 0
      AND area_enable = 0
      AND area_id IN ($areaPlaceholders)
    ORDER BY area_name
");
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

$connect = null;