<?php
include('../ajaxconfig.php');
session_start();

$userid = $_SESSION['userid'] ?? '';
$area   = $_POST['area'] ?? '';

// 1. Get user group IDs
$stmt = $connect->prepare("SELECT group_id FROM user WHERE status = 0 AND user_id = ?");
$stmt->execute([$userid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$groupIds = array_map('intval', explode(',', $user['group_id']));
$groupPlaceholders = implode(',', array_fill(0, count($groupIds), '?'));

// 2. Get allowed sub-area IDs for those groups
$stmt = $connect->prepare("SELECT DISTINCT agmsa.sub_area_id FROM area_group_mapping_sub_area agmsa
    INNER JOIN area_group_mapping agm ON agmsa.group_map_id = agm.map_id
    WHERE agm.status = 0 AND agm.map_id IN ($groupPlaceholders)");
$stmt->execute($groupIds);

$allowedSubAreas = $stmt->fetchAll(PDO::FETCH_COLUMN);
$subAreaPlaceholders = implode(',', array_fill(0, count($allowedSubAreas), '?'));

// 3. Fetch final sub-areas directly (no PHP filtering)
$params = array_merge([$area], $allowedSubAreas);

$stmt = $connect->prepare("SELECT sub_area_id, sub_area_name FROM sub_area_list_creation 
    WHERE area_id_ref = ? AND status = 0 AND sub_area_enable = 0 AND sub_area_id IN ($subAreaPlaceholders)");
$stmt->execute($params);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

$connect = null;