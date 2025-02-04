<?php 
include('../ajaxconfig.php');

$branchList_arr = array();
$companyID = $_POST['companyID'];
$result = $connect->query("SELECT branch_id,branch_name FROM `branch_creation` where company_name = '".strip_tags($companyID)."' ");
while( $row = $result->fetch()){
    $branch_id = $row['branch_id'];
    $branch_name = $row['branch_name'];
    $branchList_arr[] = array("branchID" => $branch_id, "branchName" => $branch_name);
}

echo json_encode($branchList_arr);
?>