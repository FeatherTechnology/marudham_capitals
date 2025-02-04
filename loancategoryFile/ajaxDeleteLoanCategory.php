<?php
include '../ajaxconfig.php';

if(isset($_POST["loan_category_creation_id"])){
	$loan_category_creation_id  = $_POST["loan_category_creation_id"];
}
$isdel = '';

$ctqry=$connect->query("SELECT * FROM loan_category WHERE loan_category_name = '".$loan_category_creation_id."' ");
while($row=$ctqry->fetch()){
	$isdel=$row["loan_category_id"];
}

if($isdel != ''){ 
	$message="You Don't Have Rights To Delete This Loan Category";
}
else
{ 
	$delct=$connect->query("UPDATE loan_category_creation SET status = 1 WHERE loan_category_creation_id = '".$loan_category_creation_id."' ");
	if($delct){
		$message="Loan Category Inactivated Successfully";
	}
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>