<?php 
include('../ajaxconfig.php');

$staffList_arr = array();
$type = $_POST['type'];
if($type == '1'){
    $columnName = 'department';
}elseif($type == '2'){
    $columnName = 'team';
}

$staffFrom = $_POST['staffFrom'];
$companyID = $_POST['companyID'];
$result = $connect->query("SELECT  staff_id,staff_name FROM `staff_creation` where company_id ='".strip_tags($companyID)."' && $columnName = '".strip_tags($staffFrom)."' ");
while( $row = $result->fetch()){
    $staff_id = $row['staff_id'];
    $staff_name = $row['staff_name'];
    $staffList_arr[] = array("staffID" => $staff_id, "staffName"=> $staff_name );
}

echo json_encode($staffList_arr);
?>