<?php
include '../ajaxconfig.php';

if (isset($_POST['con_sub_id'])) {
    $con_sub_id = $_POST['con_sub_id'];
}
if (isset($_POST['com_sub_add'])) {
    $com_sub_add = $_POST['com_sub_add'];
}

$crsNme='';
$crsStatus='';
$selectCategory=$connect->query("SELECT * FROM concern_subject WHERE concern_subject = '".$com_sub_add."' ");
while ($row=$selectCategory->fetch()){
	$crsNme    = $row["concern_subject"];
	$crsStatus  = $row["status"];
}

if($crsNme != '' && $crsStatus == 0){
	$message="Concern Subject Already Exists, Please Enter a Different Subject!";
}
else if($crsNme != '' && $crsStatus == 1){
	$updateCategory=$connect->query("UPDATE concern_subject SET status=0 WHERE concern_subject='".$com_sub_add."' ");
	$message="Concern Subject Added Succesfully";
}
else{
	if($con_sub_id>0){
		$updateCategory=$connect->query("UPDATE concern_subject SET concern_subject='".$com_sub_add."' WHERE con_sub_id='".$con_sub_id."' ");
		if($updateCategory == true){
		    $message="Concern Subject Updated Succesfully";
	    }
    }
	else{
	    $insertCategory=$connect->query("INSERT INTO concern_subject(concern_subject) VALUES('".strip_tags($com_sub_add)."')");
	    if($insertCategory == true){
		    $message="Concern Subject Added Succesfully";
	    }
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>