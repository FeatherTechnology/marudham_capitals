<?php

include('../../ajaxconfig.php');

session_start();
$userid = $_SESSION['userid'];
$screen = $_POST['screen'] ?? '';
if ($screen == '1') {
    $column = 'pro_aty_access';
} else {
    $column = 'repro_aty_access';
}
$sql = $connect->query("SELECT 	$column FROM user u  where u.user_id='$userid'");

if ($sql->rowCount() > 0) {
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// Close the database connection
$connect = null;
echo json_encode($row);


?>