<?php
include('../ajaxconfig.php');

$famList_arr = array();

$famId = $_POST['famid'];
$result = $connect->query("SELECT famname,relationship FROM `verification_family_info` where id='$famId' ");

while ($row = $result->fetch()) {
    $famList_arr['relation'] = $row['relationship'];
    $famList_arr['famname'] = $row['famname'];
}

echo json_encode($famList_arr);

// Close the database connection
$connect = null;