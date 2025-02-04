<?php
include '../ajaxconfig.php';

if (isset($_POST['staff_type_id'])) {
    $staff_type_id = $_POST['staff_type_id'];
}
if (isset($_POST['staff_type_name'])) {
    $staff_type_name = $_POST['staff_type_name'];
}

$crsNme='';
$crsStatus='';
$selectCategory=$connect->query("SELECT * FROM staff_type_creation WHERE staff_type_name = '".$staff_type_name."' ");
while ($row=$selectCategory->fetch()){
	$crsNme    = $row["staff_type_name"];
	$crsStatus  = $row["status"];
}

if($crsNme != '' && $crsStatus == 0){
	$message="Staff Type Already Exists, Please Enter a Different Name!";
}
else if($crsNme != '' && $crsStatus == 1){
	$updateCategory=$connect->query("UPDATE staff_type_creation SET status=0 WHERE staff_type_name='".$staff_type_name."' ");
	$message="Staff Type Added Succesfully";
}
else{
	if($staff_type_id>0){
		$updateCategory=$connect->query("UPDATE staff_type_creation SET staff_type_name='".$staff_type_name."' WHERE staff_type_id='".$staff_type_id."' ");
		if($updateCategory == true){
		    $message="Staff Type Updated Succesfully";
	    }
    }
	else{
	    $insertCategory=$connect->query("INSERT INTO staff_type_creation(staff_type_name) VALUES('".strip_tags($staff_type_name)."')");
	    if($insertCategory == true){
		    $message="Staff Type Added Succesfully";
	    }
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>