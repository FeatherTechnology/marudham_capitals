<?php
include('../ajaxconfig.php');

$teamList_arr = array();

$result = $connect->query("SELECT id, team_name FROM `team_creation` where 1 ORDER BY id DESC ");

while ($row = $result->fetch()) {
    $team_name = $row['team_name'];
    $id = $row['id'];
    $teamList_arr[] = array("teamName" => $team_name, "id" => $id);
}

echo json_encode($teamList_arr);
