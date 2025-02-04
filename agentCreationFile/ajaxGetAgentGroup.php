<?php 
include('../ajaxconfig.php');

$agent_group_arr = array();

$result=$connect->query("SELECT * FROM agent_group_creation where status=0");
while( $row = $result->fetch()){
    $agent_group_id = $row['agent_group_id'];
    $agent_group_name = $row['agent_group_name'];
    $agent_group_arr[] = array("agent_group_id" => $agent_group_id, "agent_group_name" => $agent_group_name);
}

echo json_encode($agent_group_arr);

// Close the database connection
$connect = null;
?>