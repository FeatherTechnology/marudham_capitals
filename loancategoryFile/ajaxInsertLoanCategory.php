<?php
include '../ajaxconfig.php';

if (isset($_POST['loan_category_creation_id'])) {
    $loan_category_creation_id = $_POST['loan_category_creation_id'];
}
if (isset($_POST['loan_category_creation_name'])) {
    $loan_category_creation_name = $_POST['loan_category_creation_name'];
}

$crsNme='';
$crsStatus='';
$selectCategory=$connect->query("SELECT * FROM loan_category_creation WHERE loan_category_creation_name = '".$loan_category_creation_name."' ");
while ($row=$selectCategory->fetch()){
	$crsNme    = $row["loan_category_creation_name"];
	$crsStatus  = $row["status"];
}

if($crsNme != '' && $crsStatus == 0){
	$message="Loan Category Already Exists, Please Enter a Different Name!";
}
else if($crsNme != '' && $crsStatus == 1){
	$updateCategory=$connect->query("UPDATE loan_category_creation SET status=0 WHERE loan_category_creation_name='".$loan_category_creation_name."' ");
	$message="Loan Category Added Succesfully";
}
else{
	if($loan_category_creation_id>0){
		$updateCategory=$connect->query("UPDATE loan_category_creation SET loan_category_creation_name='".$loan_category_creation_name."' WHERE loan_category_creation_id='".$loan_category_creation_id."' ");
		if($updateCategory == true){
		    $message="Loan Category Updated Succesfully";
	    }
    }
	else{
	    $insertCategory=$connect->query("INSERT INTO loan_category_creation(loan_category_creation_name) VALUES('".strip_tags($loan_category_creation_name)."')");
	    if($insertCategory == true){
		    $message="Loan Category Added Succesfully";
	    }
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>