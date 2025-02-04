<?php 
include('../ajaxconfig.php');

$deptList_arr = array();
$companyID = $_POST['companyID'];
$result = $connect->query("SELECT distinct department FROM `staff_creation` where company_id ='".strip_tags($companyID)."' ");
while( $row = $result->fetch()){
    $dept_name = $row['department'];
    $deptList_arr[] = array("deptName" => $dept_name);
}

echo json_encode($deptList_arr);
?>