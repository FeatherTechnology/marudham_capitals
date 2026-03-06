<?php 
include('../ajaxconfig.php');

$deptList_arr = array();
$companyID = $_POST['companyID'];
$result = $connect->query("SELECT id, dep_name FROM `concern_dept_name` where 1 ORDER BY id DESC ");
while( $row = $result->fetch()){
    $dept_name = $row['dep_name'];
    $id = $row['id'];
    $deptList_arr[] = array("deptName" => $dept_name, "id" => $id);
}

echo json_encode($deptList_arr);
?>