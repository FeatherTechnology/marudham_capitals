<?php

session_start();

include('../../ajaxconfig.php');

$userid = $_SESSION['userid'];

$groupList = array();
$userQry = $connect->query(" SELECT group_id FROM `user` WHERE user_id = '$userid'");

$userRow = $userQry->fetch();
if ($userRow && !empty($userRow['group_id'])) {

    $groupIds = explode(',', $userRow['group_id']);
    $groupIds = array_filter(array_map('trim', $groupIds));

    if (!empty($groupIds)) {

        $placeholders = implode(',', array_fill(0, count($groupIds), '?'));
        $groupQry = $connect->prepare(" SELECT map_id, group_name  FROM `area_group_mapping`  WHERE map_id IN ($placeholders) ORDER BY map_id ");

        $groupQry->execute($groupIds);
        while ($row = $groupQry->fetch(PDO::FETCH_ASSOC)) {
            $groupList[] = array(
                "group_id"   => $row['map_id'],
                "group_name" => $row['group_name']
            );
        }
    }
}

echo json_encode($groupList);

$connect = null;
?>

