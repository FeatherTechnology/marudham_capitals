<?php 
include('../ajaxconfig.php');

$agList_arr = array();
$ag_id = $_POST['ag_id'];
$result = $connect->query("SELECT b.agent_group_name FROM `agent_creation` a JOIN `agent_group_creation` b  ON a.ag_group_id = b.agent_group_id where a.ag_id = '".strip_tags($ag_id)."' ");
$row = $result->fetch();
$agList_arr['agGroupName'] = $row['agent_group_name'];

echo json_encode($agList_arr);
?>