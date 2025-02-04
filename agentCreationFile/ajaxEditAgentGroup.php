<?php
include '../ajaxconfig.php';

if(isset($_POST["agent_group_id"])){
	$agent_group_id  = $_POST["agent_group_id"];
}

$getct = "SELECT * FROM agent_group_creation WHERE agent_group_id = '".$agent_group_id."' AND status=0";
$result = $connect->query($getct);
while($row=$result->fetch())
{
    $agent_group_name = $row['agent_group_name'];
}

echo $agent_group_name;

// Close the database connection
$connect = null;
?>