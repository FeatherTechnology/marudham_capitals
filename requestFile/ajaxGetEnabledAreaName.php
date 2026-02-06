<?php
include('../ajaxconfig.php');
session_start();

$userid = $_SESSION['userid'] ?? '';
$taluk  = $_POST['talukselected'] ?? '';

// 1. Get user group IDs 
$stmt = $connect->prepare("SELECT group_id FROM user WHERE status = 0 AND user_id = ?");
$stmt->execute([$userid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$groupIds = array_map('intval', explode(',', $user['group_id']));
$placeholders = implode(',', array_fill(0, count($groupIds), '?'));

// 2. Get all allowed area IDs for those groups
$stmt = $connect->prepare("SELECT DISTINCT agma.area_id FROM area_group_mapping_area agma
    INNER JOIN area_group_mapping agm ON agma.group_map_id = agm.map_id
    WHERE agm.status = 0 AND agm.map_id IN ($placeholders)");
$stmt->execute($groupIds);

$allowedAreas = $stmt->fetchAll(PDO::FETCH_COLUMN);
$areaPlaceholders = implode(',', array_fill(0, count($allowedAreas), '?'));

// 3. Fetch final areas directly (NO PHP FILTERING)
$params = array_merge(["%$taluk%"], $allowedAreas);

$stmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation 
    WHERE taluk LIKE ? AND status = 0 AND area_enable = 0 AND area_id IN ($areaPlaceholders)");
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

$connect = null;