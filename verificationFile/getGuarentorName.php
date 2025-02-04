<?php
include('../ajaxconfig.php');

$famname = '';

$req_id = $_POST['req_id'];
$cus_id = $_POST['cus_id'];
$result = $connect->query("SELECT fam.famname FROM customer_profile cp JOIN verification_family_info fam ON cp.guarentor_name = fam.id where cp.req_id='$req_id' and cp.cus_id = '$cus_id'");

while ($row = $result->fetch()) {
    $famname = $row['famname'];
}

echo $famname;

// Close the database connection
$connect = null;