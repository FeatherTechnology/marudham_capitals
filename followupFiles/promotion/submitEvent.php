<?php
session_start();
$user_id = $_SESSION['userid'];
include("../../ajaxconfig.php");

$areaString = $_POST['areaString']; // comma separated area ids
$event_name = $_POST['event_name'];
$event_hidden_id = $_POST['event_hidden_id']; // for update
$rowsData = json_decode($_POST['rowsData'], true); // array of all row data

if ($event_hidden_id != '') {
    // Update event
    $connect->query("UPDATE events 
                     SET event_name='$event_name', 
                         update_login_id='$user_id', 
                         updated_date = NOW() 
                     WHERE id='$event_hidden_id'");

    $event_id = $event_hidden_id;

    // Clear old event areas
    $connect->query("DELETE FROM event_areas WHERE event_id='$event_id'");
} else {
    // Insert new event
    $connect->query("INSERT INTO events (event_name, created_date, insert_login_id) 
                     VALUES ('$event_name', NOW(), '$user_id')");
    $event_id = $connect->lastInsertId();
}

// Insert multiple areas for this event
$areas = explode(",", $areaString);
foreach ($areas as $area) {
    $area = trim($area);
    if ($area != '') {
        $connect->query("INSERT INTO event_areas (event_id, event_area) VALUES ('$event_id', '$area')");
    }
}

// Loop through all customers (rows)
foreach ($rowsData as $row) {
    $cus_name = $row['cus_name'];
    $cus_mobile_num = $row['cus_mobile_num'];
    $cus_area_name = $row['cus_area_name'];
    $sub_area_name = $row['sub_area_name'];
    $event_date = $row['currentDate'];
    $cus_hidden_id = $row['cus_hidden_id'];

    if ($cus_hidden_id != '') {
        // Update existing record
        $connect->query("UPDATE event_promotion SET 
            event_id = '$event_id',
            event_created_date = '$event_date',
            name = '$cus_name',
            mobile_num = '$cus_mobile_num',
            area = '$cus_area_name',
            sub_area = '$sub_area_name',
            update_login_id = '$user_id',
            updated_date = NOW()
            WHERE id = '$cus_hidden_id'");
    } else {
        // Insert new record
        $connect->query("INSERT INTO event_promotion (
            event_id, event_created_date, name, mobile_num, area, sub_area, insert_login_id
        ) VALUES (
            '$event_id', '$event_date', '$cus_name', '$cus_mobile_num', '$cus_area_name', '$sub_area_name', '$user_id'
        )");
    }
}

echo json_encode([
    'message' => 'Event Submitted Successfully'
]);

$connect = null;
?>
