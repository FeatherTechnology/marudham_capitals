<?php
include '../ajaxconfig.php';

if(isset($_POST["area_id"])){
	$area_id  = $_POST["area_id"];
}

$getct = "SELECT * FROM area_list_creation WHERE area_id = '".$area_id."' AND status=0";
$result = $connect->query($getct);
while($row=$result->fetch())
{
    $area_name = $row['area_name'];
}

echo $area_name;

// Close the database connection
$connect = null;
?>