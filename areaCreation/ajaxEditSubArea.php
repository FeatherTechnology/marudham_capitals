<?php
include '../ajaxconfig.php';

if(isset($_POST["sub_area_id"])){
	$sub_area_id  = $_POST["sub_area_id"];
}

$getct = "SELECT * FROM sub_area_list_creation WHERE sub_area_id = '".$sub_area_id."' AND status=0";
$result = $connect->query($getct);
while($row=$result->fetch())
{
    $sub_area_name = $row['sub_area_name'];
}

echo $sub_area_name;

// Close the database connection
$connect = null;
?>