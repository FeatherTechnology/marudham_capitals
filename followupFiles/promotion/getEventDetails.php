<?php
include("../../ajaxconfig.php");

$event_id = $_POST['event_id'];

// Step 1: Fetch event row
$eventQry = $connect->query("SELECT * FROM events WHERE id = '$event_id'");
$event = $eventQry->fetch(PDO::FETCH_ASSOC);

if($event) {
    $event_id = $event['id'];

    // Step 2: Fetch all areas for this event
    $areaQry = $connect->query("
        SELECT GROUP_CONCAT(DISTINCT event_area) as all_area_ids
        FROM event_areas 
        WHERE event_id = '$event_id'
    ");
    $areaRow = $areaQry->fetch(PDO::FETCH_ASSOC);
    $allAreas = $areaRow['all_area_ids'] ?? '';

    // Step 3: Fetch all customers for this event
    $rows = $connect->query("
        SELECT e.* ,u.fullname
        FROM event_promotion e LEFT JOIN user u ON e.insert_login_id = u.user_id 
        WHERE e.event_id = '$event_id'
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Attach all_areas (like old code did)
    $event['all_areas'] = $allAreas;

    echo json_encode([
        "success" => true,
        "event" => $event,
        "rows" => $rows
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
