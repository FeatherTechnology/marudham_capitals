<?php
session_start();
$user_id = $_SESSION["userid"];
include '../ajaxconfig.php';

$con_dep_name = trim($_POST['con_dep_name'] ?? '');
$con_dep_name_id = $_POST['con_dep_name_id'] ?? '';

$depName = '';

if ($con_dep_name != '') {

	// Check whether the department already exists
	$selectDepartment = $connect->query("SELECT id, dep_name FROM concern_dept_name WHERE dep_name = '$con_dep_name'");

	while ($row = $selectDepartment->fetch()) {
		$depName = $row['dep_name'];
		$existingId = $row['id'];
	}

	if ($con_dep_name_id == '') {

		// Insert
		if ($depName != '') {
			$message = "Department Already Exists, Please Enter a Different Department!";
		} else {
			$insertDepName = $connect->query("INSERT INTO concern_dept_name (dep_name, insert_login_id, created_date)
            VALUES ('$con_dep_name', '$user_id', NOW())");

			if ($insertDepName) {
				$message = "Department Added Successfully";
			}
		}
	} else {

		// Update
		if ($depName != '' && $existingId != $con_dep_name_id) {
			$message = "Department Already Exists, Please Enter a Different Department!";
		} else {
			$updateDepName = $connect->query("UPDATE concern_dept_name
                SET dep_name = '$con_dep_name',
                    update_login_id = '$user_id',
                    updated_date = NOW()
                WHERE id = '$con_dep_name_id'");

			if ($updateDepName) {
				$message = "Department Name Updated Successfully";
			}
		}
	}
} else {
	$message = "Department Name is required";
}

echo json_encode($message);

$connect = null;
