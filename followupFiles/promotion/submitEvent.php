<?php
session_start();
$user_id = $_SESSION['userid'];
include("../../ajaxconfig.php");

$area_id = $_POST['areaString']; // comma separated area ids
$event_hidden_id = $_POST['event_hidden_id']; // for update
$rowsData = json_decode($_POST['rowsData'], true); // array of all row data

if ($event_hidden_id != '') {
    // Update event
    $connect->query("UPDATE events 
                     SET event_name='$area_id', 
                         update_login_id='$user_id', 
                         updated_date = NOW() 
                     WHERE id='$event_hidden_id'");

    $event_id = $event_hidden_id;

} else {
    // Insert new event
    $connect->query("INSERT INTO events (event_name, created_date, insert_login_id) 
                     VALUES ('$area_id', NOW(), '$user_id')");
    $event_id = $connect->lastInsertId();
}

// Loop through all customers (rows)
foreach ($rowsData as $row) {
    $cus_name = $row['cus_name'];
    $cus_mobile_num = $row['cus_mobile_num'];
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
            sub_area = '$sub_area_name',
            update_login_id = '$user_id',
            updated_date = NOW()
            WHERE id = '$cus_hidden_id'");
    } else {
        // Insert new record
        $connect->query("INSERT INTO event_promotion (
            event_id, event_created_date, name, mobile_num, sub_area, insert_login_id
        ) VALUES (
            '$event_id', '$event_date', '$cus_name', '$cus_mobile_num', '$sub_area_name', '$user_id'
        )");
    }
}

echo json_encode([
    'message' => 'Event Submitted Successfully'
]);

$connect = null;
?>
