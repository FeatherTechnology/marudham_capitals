<?php 
include('../ajaxconfig.php');

$area_id_upd = !empty($_POST['area_id_upd'])
    ? array_map('intval', explode(',', $_POST['area_id_upd']))
    : [];

$placeholders = '';
$params = [];

if (!empty($area_id_upd)) {
    $placeholders = implode(',', array_fill(0, count($area_id_upd), '?'));
    $params = $area_id_upd;
}

$sql = "
    SELECT DISTINCT alc.area_id, alc.area_name
    FROM area_list_creation alc
    LEFT JOIN area_duefollowup_mapping_area adma
        ON alc.area_id = adma.area_id
    WHERE alc.status = 0
      AND (
            adma.area_id IS NULL
            " . (!empty($placeholders) ? " OR alc.area_id IN ($placeholders)" : "") . "
          )
    ORDER BY alc.area_name
";

$stmt = $connect->prepare($sql);
$stmt->execute($params);
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($areas);

// Close the database connection
$connect = null;
?>