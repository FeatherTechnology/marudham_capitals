<?php
include('../../ajaxconfig.php');
@session_start();
$user_id = $_SESSION['userid'];

$rows = []; // Initialize array

if ($user_id != '') {
    // Step 1: Fetch role type and access details of the user
    $userRes = $connect->query("SELECT line_id , group_id , due_followup_lines , promotion_activity_mapping_access, role_type FROM user WHERE user_id = $user_id");
    $userRow = $userRes->fetch();
    $role_type = $userRow['role_type'];
    $group_id = $userRow['group_id'];
    $line_id = $userRow['line_id'];
    $due_followup_lines = $userRow['due_followup_lines'];
    $promotion_activity_mapping_access = $userRow['promotion_activity_mapping_access'];

    if ($promotion_activity_mapping_access == 1) {
        $condition = "agm.map_id IN ($group_id)";
    } elseif ($promotion_activity_mapping_access == 2) {
        $condition = "alm.map_id IN ($line_id)";
    } elseif ($promotion_activity_mapping_access == 3) {
        $condition = "adfm.map_id IN ($due_followup_lines)";
    }
    // Step 2: Set up base query condition based on role_type
    if ($role_type == 7 || $role_type == 3) {
        $sql = $connect->query("
        SELECT 
        e.id,
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
          e.id,
            e.event_name,
            MIN(ep.event_created_date) AS created_date, 
            GROUP_CONCAT(DISTINCT al.area_name ORDER BY al.area_name SEPARATOR ', ') AS area_names,
            COUNT(DISTINCT ep.id) AS total_customer
        FROM events e
        JOIN event_promotion ep ON ep.event_id = e.id
        JOIN event_areas ea ON ea.event_id = e.id
        JOIN area_list_creation al ON al.area_id = ea.event_area
        JOIN area_group_mapping_area agma ON agma.area_id = al.area_id
        JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
        JOIN area_line_mapping_area alma ON alma.area_id = al.area_id
        JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
        JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = al.area_id
        JOIN area_duefollowup_mapping adfm ON adfm.map_id = adfma.duefollowup_map_id
        WHERE $condition
        GROUP BY e.id
        ORDER BY e.id DESC; 
    ");
    }

    if ($sql->rowCount() > 0) {
        $i = 1;
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $formattedDate = date('d-m-Y', strtotime($row['created_date']));
            $action = '<button class="btn btn-primary edit_event" data-event="' . $row['id'] . '">Edit</button>';
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
