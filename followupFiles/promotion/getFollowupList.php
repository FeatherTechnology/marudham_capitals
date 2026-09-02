<?php

session_start();

include('../../ajaxconfig.php');

$userid = $_SESSION['userid'];
$groupList = array();

$userQry = $connect->query("SELECT due_followup_lines FROM `user` WHERE user_id = '$userid' ");

$userRow = $userQry->fetch();

if ($userRow && !empty($userRow['due_followup_lines'])) {

    $dueFollowupIds = explode(',', $userRow['due_followup_lines']);

    $dueFollowupIds = array_filter(array_map('trim', $dueFollowupIds));
    if (!empty($dueFollowupIds)) {

        $placeholders = implode(',', array_fill(0, count($dueFollowupIds), '?'));
        $groupQry = $connect->prepare(" SELECT map_id, duefollowup_name FROM `area_duefollowup_mapping`  WHERE map_id IN ($placeholders) ORDER BY map_id ");

        $groupQry->execute($dueFollowupIds);
        while ($row = $groupQry->fetch(PDO::FETCH_ASSOC)) {
            $groupList[] = array(
                "due_followup_lines_id"   => $row['map_id'],
                "duefollowup_name" => $row['duefollowup_name']
            );
        }
    }
}
echo json_encode($groupList);
$connect = null;
?>

