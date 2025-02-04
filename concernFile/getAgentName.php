<?php 
include('../ajaxconfig.php');

$agList_arr = array();
$companyID = $_POST['companyID'];
$result = $connect->query("SELECT ag_name,ag_id FROM `agent_creation` where company_id = '".strip_tags($companyID)."' ");
while( $row = $result->fetch()){
    $ag_name = $row['ag_name'];
    $ag_id = $row['ag_id'];
    $agList_arr[] = array("agName" => $ag_name, "ag_id" => $ag_id);
}

echo json_encode($agList_arr);
?>