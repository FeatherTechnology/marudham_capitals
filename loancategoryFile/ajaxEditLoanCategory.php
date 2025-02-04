<?php
include '../ajaxconfig.php';

if(isset($_POST["loan_category_creation_id"])){
	$loan_category_creation_id  = $_POST["loan_category_creation_id"];
}

$getct = "SELECT * FROM loan_category_creation WHERE loan_category_creation_id = '".$loan_category_creation_id."' AND status=0";
$result = $connect->query($getct);
while($row=$result->fetch())
{
    $loan_category_creation_name = $row['loan_category_creation_name'];
}

echo $loan_category_creation_name;

// Close the database connection
$connect = null;
?>