<?php
include '../ajaxconfig.php';

$area = $_POST['area'] ?? '';
$map  = $_POST['map']  ?? '';
$sub_area_upd  = ($_POST['sub_area_upd']) ? explode(',', $_POST['sub_area_upd']) : [];

$area_array = array_filter(array_map('intval', explode(',', $area)));

if (empty($area_array)) {
    echo json_encode([]);
    exit;
}

/* Get all sub-areas for selected areas */
$placeholders = implode(',', array_fill(0, count($area_array), '?'));

$stmt = $connect->prepare("
    SELECT sub_area_id, sub_area_name, area_id_ref
    FROM sub_area_list_creation
    WHERE status = 0
      AND area_id_ref IN ($placeholders)
");
$stmt->execute($area_array);
$subAreas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($subAreas)) {
    echo json_encode([]);
    exit;
}

/* Get all mapped sub_area_ids */
if ($map == 'line') {
    $mappedStmt = $connect->query("
        SELECT DISTINCT sub_area_id
        FROM area_line_mapping_sub_area
    ");
} else if($map == 'group'){
    $mappedStmt = $connect->query("
        SELECT DISTINCT sub_area_id
        FROM area_group_mapping_sub_area
    ");
}

$mappedSubAreas = array_flip($mappedStmt->fetchAll(PDO::FETCH_COLUMN));

/* Build response (same structure as before) */
$records = [];
$areaIndex = [];

foreach ($subAreas as $row) {

    $area_id     = (int) $row['area_id_ref'];
    $sub_area_id = (int) $row['sub_area_id'];

    // No sub-areas OR fully mapped → SKIP
    if (isset($mappedSubAreas[$sub_area_id]) && !in_array($sub_area_id, $sub_area_upd)) {
        continue;
    }

    // Maintain original nested structure
    if (!isset($areaIndex[$area_id])) {
        $areaIndex[$area_id] = count($records);
        $records[] = [];
    }

    $records[$areaIndex[$area_id]][] = [
        'sub_area_id'   => $sub_area_id,
        'sub_area_name' => $row['sub_area_name']
    ];
}

echo json_encode($records);
$connect = null;