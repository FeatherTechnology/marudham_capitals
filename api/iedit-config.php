<?php
date_default_timezone_set('Asia/Kolkata');

$host = "localhost";
$db_user = "root";
$db_pass = "";
$dbname = "marudham";

$mysqli = mysqli_connect($host, $db_user, $db_pass, $dbname) or die("Error in database connection" . mysqli_connect_error());
mysqli_set_charset($mysqli, "utf8");
$timeZoneQry = "set time_zone = '+5:30' ";
$mysqli->query($timeZoneQry);
