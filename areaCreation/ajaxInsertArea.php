<?php
include '../ajaxconfig.php';

if (isset($_POST['area_id'])) {
    $area_id = $_POST['area_id'];
}
if (isset($_POST['area_name'])) {
    $area_name = $_POST['area_name'];
}
if (isset($_POST['taluk'])) {
    $taluk = $_POST['taluk'];
}

$crsNme='';
$crsStatus='';
$selectCategory=$connect->query("SELECT * FROM area_list_creation WHERE area_name = '".$area_name."' ");
while ($row=$selectCategory->fetch()){
	$crsNme    = $row["area_name"];
	$crsStatus  = $row["status"];
}

if($crsNme != '' && $crsStatus == 0){
	$message="Area Category Already Exists, Please Enter a Different Name!";
}
else if($crsNme != '' && $crsStatus == 1){
	$updateCategory=$connect->query("UPDATE area_list_creation SET status=0 WHERE area_name='".$area_name."' ");
	$message="Area Category Added Succesfully";
}
else{
	if($area_id>0){
		$updateCategory=$connect->query("UPDATE area_list_creation SET area_name='".$area_name."' WHERE area_id='".$area_id."' ");
		if($updateCategory == true){
		    $message="Area Category Updated Succesfully";
	    }
    }
	else{
	    $insertCategory=$connect->query("INSERT INTO area_list_creation(area_name,taluk) VALUES('".strip_tags($area_name)."','".strip_tags($taluk)."')");
	    if($insertCategory == true){
		    $message="Area Category Added Succesfully";
	    }
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>