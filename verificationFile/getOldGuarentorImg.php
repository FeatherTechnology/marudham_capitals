<?php

include '../ajaxconfig.php';

$cus_id = $_POST['cus_id'];

$response = [];

$sql = $connect->query("SELECT a.guarentor_name AS fam_id, a.guarentor_relation AS relation, a.guarentor_photo AS img, b.famname AS name FROM customer_profile a JOIN verification_family_info b ON a.guarentor_name = b.id WHERE a.cus_id = $cus_id ORDER BY a.id DESC LIMIT 1");
if ($sql->rowCount() > 0) {
    $row = $sql->fetch();
    $response[] = $row;
}

echo json_encode($response);

// Close the database connection
$connect = null;