<?php
session_start();
$user_id = $_SESSION["userid"];
include '../ajaxconfig.php';

$team_name = trim($_POST['team_name'] ?? '');
$team_name_id = $_POST['team_name_id'] ?? '';

$teamName = '';

if ($team_name != '') {

	// Check whether the team already exists
	$selectTeam = $connect->query("SELECT id, team_name FROM team_creation WHERE team_name = '$team_name'");

	while ($row = $selectTeam->fetch()) {
		$teamName = $row['team_name'];
		$existingId = $row['id'];
	}

	if ($team_name_id == '') {

		// Insert
		if ($teamName != '') {
			$message = "Team Name Already Exists, Please Enter a Different Team Name!";
		} else {
			$insertteamName = $connect->query("INSERT INTO team_creation (team_name, insert_login_id, created_date)
            VALUES ('$team_name', '$user_id', NOW())");

			if ($insertteamName) {
				$message = "Team Name Added Successfully";
			}
		}
	} else {

		// Update
		if ($teamName != '' && $existingId != $team_name_id) {
			$message = "Team Name Already Exists, Please Enter a Different Team Name!";
		} else {
			$updateteamName = $connect->query("UPDATE team_creation
                SET team_name = '$team_name',
                    update_login_id = '$user_id',
                    updated_date = NOW()
                WHERE id = '$team_name_id'");

			if ($updateteamName) {
				$message = "Team Name Updated Successfully";
			}
		}
	}
} else {
	$message = "Team Name is required";
}

echo json_encode($message);

$connect = null;
