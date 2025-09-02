<?php
include '../../ajaxconfig.php';
session_start();

$userid = $_SESSION["userid"];

$loan_category_arr = array();
$user_area = array();

$Qry = $connect->query("SELECT promotion_activity_mapping_access, group_id, line_id, due_followup_lines 
                        FROM user 
                        WHERE status=0 AND user_id= '" . $userid . "'"); 
$run = $Qry->fetch();

$accessType = $run['promotion_activity_mapping_access'];

if ($accessType == 1) { 
    // ✅ Group-based access
    $user_group = explode(',', $run['group_id']);
    foreach ($user_group as $group_id) {
        $Qry = $connect->query("SELECT area_id FROM area_group_mapping WHERE status = 0 AND map_id = $group_id");
        while ($row = $Qry->fetch()) {
            $user_area = array_merge($user_area, explode(',', $row['area_id']));
        }
    }

} elseif ($accessType == 2) { 
    // ✅ Line-based access
    $user_line = explode(',', $run['line_id']);
    foreach ($user_line as $line_id) {
        $Qry = $connect->query("SELECT area_id FROM area_line_mapping WHERE status = 0 AND map_id = $line_id");
        while ($row = $Qry->fetch()) {
            $user_area = array_merge($user_area, explode(',', $row['area_id']));
        }
    }

} elseif ($accessType == 3) { 
    // ✅ DueFollowup-based access
    $user_due = explode(',', $run['due_followup_lines']);
    foreach ($user_due as $due_id) {
        $Qry = $connect->query("SELECT area_id FROM area_duefollowup_mapping WHERE status = 0 AND map_id = $due_id");
        while ($row = $Qry->fetch()) {
            $user_area = array_merge($user_area, explode(',', $row['area_id']));
        }
    }
}

$user_area = array_unique($user_area); // remove duplicates

// Now get area names
if (count($user_area) > 0) {
    $areaIds = implode(',', $user_area);
    $result = $connect->query("SELECT area_id, area_name 
                               FROM area_list_creation 
                               WHERE status=0 AND area_enable = 0 AND area_id IN ($areaIds)");
    while ($row = $result->fetch()) {
        $loan_category_arr[] = array("area_id" => $row['area_id'], "area_name" => $row['area_name']);
    }
}

echo json_encode($loan_category_arr);
$connect = null;
?>
