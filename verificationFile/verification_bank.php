<?php
include('../ajaxconfig.php');

$bankdrpdwn_arr = array();

$result = $connect->query("SELECT bank_name FROM `verification_bank_info` order by id desc");

while ($row = $result->fetch()) {
    $bankdrpdwn_arr[] = $row['bank_name'];
}

echo json_encode($bankdrpdwn_arr);

// Close the database connection
$connect = null;