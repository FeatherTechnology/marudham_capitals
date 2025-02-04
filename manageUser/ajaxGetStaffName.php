<?php 
include('../ajaxconfig.php');

if(isset($_POST['role_type'])){
    $role_type = $_POST['role_type'];
}

$staffArr = array();

$result=$connect->query("SELECT * FROM staff_creation where status=0 and staff_type = $role_type ");
while( $row = $result->fetch()){
    $staff_id = $row['staff_id'];
    $staff_name = $row['staff_name'];
    $staffArr[] = array("staff_id" => $staff_id, "staff_name" => $staff_name);
}

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>