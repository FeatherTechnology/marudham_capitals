<?php 
include('../ajaxconfig.php');

$teamList_arr = array();
$deptname = $_POST['dept'];
$result = $connect->query("SELECT distinct team FROM `staff_creation` where department ='".strip_tags($deptname)."' ");
while( $row = $result->fetch()){
    $team = $row['team'];
    $teamList_arr[] = array("teamName" => $team);
}

echo json_encode($teamList_arr);
?>