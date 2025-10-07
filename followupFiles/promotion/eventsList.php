<?php
include('../../ajaxconfig.php');
@session_start();
$user_id = $_SESSION['userid'];

$rows = []; // Initialize array

if ($user_id != '') {
    // Step 1: Fetch role type and access details of the user
    $userRes = $connect->query("SELECT role_type FROM user WHERE user_id = $user_id");
    $userRow = $userRes->fetch(PDO::FETCH_ASSOC);
    $role_type = $userRow['role_type'];

    // Step 2: Set up base query condition based on role_type
    if ($role_type == 7 || $role_type == 3) {
        $sql = $connect->query("
        SELECT 
            e.event_name,
            MIN(ep.event_created_date) AS created_date, 
            GROUP_CONCAT(DISTINCT al.area_name ORDER BY al.area_name SEPARATOR ', ') AS area_names,
            COUNT(DISTINCT ep.id) AS total_customer
        FROM events e
        JOIN event_promotion ep ON ep.event_id = e.id
        JOIN event_areas ea ON ea.event_id = e.id
        JOIN area_list_creation al ON al.area_id = ea.event_area
        WHERE 1
        GROUP BY e.id
        ORDER BY e.id DESC; 
    ");
    } else {
        $sql = $connect->query("
        SELECT 
            e.event_name,
            MIN(ep.event_created_date) AS created_date, 
            GROUP_CONCAT(DISTINCT al.area_name ORDER BY al.area_name SEPARATOR ', ') AS area_names,
            COUNT(DISTINCT ep.id) AS total_customer
        FROM events e
        JOIN event_promotion ep ON ep.event_id = e.id
        JOIN event_areas ea ON ea.event_id = e.id
        JOIN area_list_creation al ON al.area_id = ea.event_area
        WHERE e.insert_login_id = '$user_id'
        GROUP BY e.id
        ORDER BY e.id DESC; 
    ");
    }

    if ($sql->rowCount() > 0) {
        $i = 1;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $formattedDate = date('d-m-Y', strtotime($row['created_date']));
            $action = '<button class="btn btn-primary edit_event" data-event="' . $row['event_name'] . '">Edit</button>';
            $rows[] = [
                $i++,
                $formattedDate,
                $row['event_name'],
                $row['area_names'],
                $row['total_customer'],
                $action
            ];
        }
    }
}

// Always return JSON array (empty if no records)
echo json_encode($rows);
