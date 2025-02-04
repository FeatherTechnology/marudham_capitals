<?php
include '../ajaxconfig.php';

if (isset($_POST['sub_area_id'])) {
    $sub_area_id = $_POST['sub_area_id'];
}
if (isset($_POST['sub_area_name'])) {
    $sub_area_name = $_POST['sub_area_name'];
}
if (isset($_POST['area_id_ref'])) {
    $area_id_ref = $_POST['area_id_ref'];
}

$crsNme='';
$crsStatus='';
$selectCategory=$connect->query("SELECT * FROM sub_area_list_creation WHERE sub_area_name = '".$sub_area_name."' ");
while ($row=$selectCategory->fetch()){
	$crsNme    = $row["sub_area_name"];
	$crsStatus  = $row["status"];
}

if($crsNme != '' && $crsStatus == 0){
	$message="Sub Area Category Already Exists, Please Enter a Different Name!";
}
else if($crsNme != '' && $crsStatus == 1){
	$updateCategory=$connect->query("UPDATE sub_area_list_creation SET status=0 WHERE sub_area_name='".$sub_area_name."' ");
	$message="Sub Area Category Added Succesfully";
}
else{
	if($sub_area_id>0){
		$updateCategory=$connect->query("UPDATE sub_area_list_creation SET sub_area_name='".$sub_area_name."' WHERE sub_area_id='".$sub_area_id."' ");
		if($updateCategory == true){
		    $message="Sub Area Category Updated Succesfully";
	    }
    }
	else{
	    $insertCategory=$connect->query("INSERT INTO sub_area_list_creation(sub_area_name,area_id_ref) VALUES('".strip_tags($sub_area_name)."','".strip_tags($area_id_ref)."' )");
	    if($insertCategory == true){
		    $message="Sub Area Category Added Succesfully";
	    }
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>