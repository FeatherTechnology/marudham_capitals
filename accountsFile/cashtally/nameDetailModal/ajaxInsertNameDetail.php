<?php
session_start();
$user_id = $_SESSION['userid'];

include '../../../ajaxconfig.php';

if (isset($_POST['name_id'])) {
	$name_id = $_POST['name_id'];
}
if (isset($_POST['name'])) {
	$name = $_POST['name'];
}
if (isset($_POST['area'])) {
	$area = $_POST['area'];
}
if (isset($_POST['ident'])) {
	$ident = $_POST['ident'];
}
$opt_for = $_POST['opt_for'];


$nameCheck = '';
$name_sts = '';
$qry = $connect->query("SELECT * FROM name_detail_creation WHERE name = '$name' and opt_for = '$opt_for' ");

while ($row = $qry->fetch()) {
	$nameCheck    = $row["name"];
	$name_sts  = $row["status"];
}

if ($nameCheck != '' && $name_sts == 0) {
	$message = "Name Detail Already Exists, Please Enter a Different Name!";
} else if ($nameCheck != '' && $name_sts == 1) {
	$qry = $connect->query("UPDATE name_detail_creation SET status=0 WHERE name = '$name' and opt_for = '$opt_for' ");
	$message = "Name Detail Added Succesfully";
} else {
	if ($name_id > 0) {
		$qry = $connect->query("UPDATE name_detail_creation SET name='$name' WHERE name_id = '$name_id' ");
		if ($qry == true) {
			$message = "Name Detail Updated Succesfully";
		}
	} else {
		$qry = $connect->query("INSERT INTO name_detail_creation(name,area,ident,opt_for,insert_login_id) VALUES('" . strip_tags($name) . "','" . strip_tags($area) . "','" . strip_tags($ident) . "','" . $opt_for . "','$user_id')");
		if ($qry == true) {
			$message = "Name Detail Added Succesfully";
		}
	}
}

echo json_encode($message);

// Close the database connection
$connect = null;
