<?php
include('../ajaxconfig.php');

$map = $_POST['map'] ?? 'group';
$area_id_upd = ($_POST['area_id_upd']) ? explode(',', $_POST['area_id_upd']) : [];

/* Get all active areas */
$areas = $connect->query("
    SELECT area_id, area_name
    FROM area_list_creation
    WHERE status = 0
")->fetchAll(PDO::FETCH_ASSOC);

if (empty($areas)) {
    echo json_encode([]);
    exit;
}

/* Get total ACTIVE sub-area count per area */
$subAreaCount = $connect->query("
    SELECT area_id_ref, COUNT(*) AS total_sub
    FROM sub_area_list_creation
    WHERE status = 0
    GROUP BY area_id_ref
")->fetchAll(PDO::FETCH_KEY_PAIR);

/* Get mapped sub-area count per area 
If only check mapping area & sub area tables it return map id based record so $mappedSub >= $totalSub get true always. ex: $totalSub = 5, $totalSub = 111; but we need to check whether those 5 sub area are mapped or not. so using sub_area_list_creation table as JOIN
*/
if ($map === 'line') {

    $mapped = $connect->query("
        SELECT alma.area_id, COUNT(DISTINCT almsa.sub_area_id) AS mapped_sub
        FROM area_line_mapping_area alma
        JOIN area_line_mapping_sub_area almsa
            ON alma.line_map_id = almsa.line_map_id
        JOIN sub_area_list_creation salc
            ON salc.sub_area_id = almsa.sub_area_id
            AND salc.area_id_ref = alma.area_id
            AND salc.status = 0
        GROUP BY alma.area_id
    ")->fetchAll(PDO::FETCH_KEY_PAIR);

} else {

    $mapped = $connect->query("
        SELECT agma.area_id, COUNT(DISTINCT agmsa.sub_area_id) AS mapped_sub
        FROM area_group_mapping_area agma
        JOIN area_group_mapping_sub_area agmsa
            ON agma.group_map_id = agmsa.group_map_id
        JOIN sub_area_list_creation salc
            ON salc.sub_area_id = agmsa.sub_area_id
            AND salc.area_id_ref = agma.area_id
            AND salc.status = 0
        GROUP BY agma.area_id
    ")->fetchAll(PDO::FETCH_KEY_PAIR);
}

/* Build dropdown (ONLY enabled areas) */
$response = [];

foreach ($areas as $area) {

    $area_id = $area['area_id'];

    $totalSub  = $subAreaCount[$area_id] ?? 0;
    $mappedSub = $mapped[$area_id] ?? 0;

    // No sub-areas OR fully mapped → SKIP
    if (($totalSub == 0 || $mappedSub >= $totalSub) && !in_array($area_id, $area_id_upd)) {
        continue;
    }

    $response[] = [
        'area_id'   => $area_id,
        'area_name' => $area['area_name']
    ];
}

echo json_encode($response);
$connect = null;
