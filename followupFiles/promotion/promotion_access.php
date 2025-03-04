<?php

include('../../ajaxconfig.php');

session_start();
$userid = $_SESSION['userid'];

$sql = $connect->query("SELECT 	pro_aty_access FROM user u  where u.user_id='$userid'");

if ($sql->rowCount() > 0) {
    $row = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// Close the database connection
$connect = null;
echo json_encode($row);


?>