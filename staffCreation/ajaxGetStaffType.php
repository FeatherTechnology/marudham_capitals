<?php 
include('../ajaxconfig.php');

$agent_group_arr = array();

$result=$connect->query("SELECT * FROM staff_type_creation where status=0");
while( $row = $result->fetch()){
    $staff_type_id = $row['staff_type_id'];
    $staff_type_name = $row['staff_type_name'];
    $agent_group_arr[] = array("staff_type_id" => $staff_type_id, "staff_type_name" => $staff_type_name);
}

echo json_encode($agent_group_arr);

// Close the database connection
$connect = null;
?>