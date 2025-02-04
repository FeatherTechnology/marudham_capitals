<?php
include '../../../ajaxconfig.php';

if(isset($_POST["name_id"])){
	$name_id  = $_POST["name_id"];
}
$message = '';

$ctqry=$connect->query("SELECT * FROM ct_cr_hinvest WHERE name_id = '".$name_id."' ");
while($row=$ctqry->fetch()){
	$isdel=$row["id"];
}

if($isdel != ''){ 
	$message="You Don't Have Rights To Delete This Name Detail";
}
else
{ 
	$delct=$connect->query("UPDATE name_detail_creation SET status = 1 WHERE name_id = '".$name_id."' ");
	if($delct){
		$message="Name Detail Inactivated Successfully";
	}
}

echo $message;

// Close the database connection
$connect = null;
?>