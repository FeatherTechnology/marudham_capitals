<?php
include '../ajaxconfig.php';

if(isset($_POST["staff_type_id"])){
	$staff_type_id  = $_POST["staff_type_id"];
}
$isdel = '';

$ctqry=$connect->query("SELECT * FROM staff_creation WHERE staff_type = '".$staff_type_id."' ");
while($row=$ctqry->fetch()){
	$isdel=$row["staff_id"];
}

if($isdel != ''){ 
	$message="You Don't Have Rights To Delete This Staff Type";
}
else
{ 
	$delct=$connect->query("UPDATE staff_type_creation SET status = 1 WHERE staff_type_id = '".$staff_type_id."' ");
	if($delct){
		$message="Staff Type Inactivated Successfully";
	}
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>