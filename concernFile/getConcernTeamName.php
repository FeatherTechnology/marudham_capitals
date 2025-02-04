<?php 
include('../ajaxconfig.php');

$teamList_arr = array();
$companyID = $_POST['companyID'];
$result = $connect->query("SELECT distinct team FROM `staff_creation` where company_id ='".strip_tags($companyID)."' ");
while( $row = $result->fetch()){
    $team = $row['team'];
    $teamList_arr[] = array("teamName" => $team);
}

echo json_encode($teamList_arr);
?>