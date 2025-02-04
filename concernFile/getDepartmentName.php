<?php 
include('../ajaxconfig.php');

$deptList_arr = array();
$result = $connect->query("SELECT distinct department FROM `staff_creation` where department !='' ");
while( $row = $result->fetch()){
    $dept_name = $row['department'];
    $deptList_arr[] = array("deptName" => $dept_name);
}

echo json_encode($deptList_arr);
?>