<?php 
include('../ajaxconfig.php');

$staffArr = array();

$result=$connect->query("SELECT map_id , duefollowup_name FROM area_duefollowup_mapping where 1 ");
    while( $row = $result->fetch()){
        $map_id = $row['map_id'];
        $duefollowup_name = $row['duefollowup_name'];
        $staffArr[] = array("map_id" => $map_id, "duefollowup_name" => $duefollowup_name);
        }

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>