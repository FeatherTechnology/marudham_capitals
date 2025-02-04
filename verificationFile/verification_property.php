<?php
include('../ajaxconfig.php');

$propertydrpdwn_arr = array();

$result = $connect->query("SELECT property_type FROM `verification_property_info` order by id desc");

while ($row = $result->fetch()) {
    $propertydrpdwn_arr[] = $row['property_type'];
}

echo json_encode($propertydrpdwn_arr);

// Close the database connection
$connect = null;