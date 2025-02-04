<?php
include('../ajaxconfig.php');

$GroupList_arr = array();

$result = $connect->query("SELECT group_name FROM `verification_group_info` order by id desc");

while ($row = $result->fetch()) {
    $GroupList_arr[] = $row['group_name'];
}

echo json_encode($GroupList_arr);

// Close the database connection
$connect = null;