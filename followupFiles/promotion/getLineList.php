<?php

session_start();

include('../../ajaxconfig.php');

$userid = $_SESSION['userid'];
$groupList = array();

$userQry = $connect->query("SELECT line_id FROM `user` WHERE user_id = '$userid' ");

$userRow = $userQry->fetch();

if ($userRow && !empty($userRow['line_id'])) {

    $LineIds = explode(',', $userRow['line_id']);

    $LineIds = array_filter(array_map('trim', $LineIds));
    if (!empty($LineIds)) {

        $placeholders = implode(',', array_fill(0, count($LineIds), '?'));
        $groupQry = $connect->prepare(" SELECT map_id, line_name FROM `area_line_mapping`  WHERE map_id IN ($placeholders) ORDER BY map_id ");

        $groupQry->execute($LineIds);
        while ($row = $groupQry->fetch(PDO::FETCH_ASSOC)) {
            $groupList[] = array(
                "line_id"   => $row['map_id'],
                "line_name" => $row['line_name']
            );
        }
    }
}
echo json_encode($groupList);
$connect = null;
?>

