<?php
include '../ajaxconfig.php';

if(isset($_POST["id"])){
	$id  = $_POST["id"];
}

$getct = "SELECT * FROM concern_subject WHERE concern_sub_id = '".$id."' AND status=0";
$result = $connect->query($getct);
while($row=$result->fetch())
{
    $concern_subject = $row['concern_subject'];
}

echo $concern_subject;

// Close the database connection
$connect = null;
?>