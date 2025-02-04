<?php
include('../ajaxconfig.php');
if (isset($_POST['sub_area_id'])) {
    $sub_area = $_POST['sub_area_id'];
}

$records = array();

$result = $connect->query("SELECT group_name FROM `area_group_mapping` where status=0 and FIND_IN_SET($sub_area,sub_area_id) ");
$row = $result->fetch();
$records['group_name'] = $row['group_name'];

$result = $connect->query("SELECT line_name FROM `area_line_mapping` where status=0 and FIND_IN_SET($sub_area,sub_area_id) ");
$row = $result->fetch();
$records['line_name'] = $row['line_name'];

echo json_encode($records);

// Close the database connection
$connect = null;