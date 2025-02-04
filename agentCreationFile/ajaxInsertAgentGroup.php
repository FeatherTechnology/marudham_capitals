<?php
include '../ajaxconfig.php';

if (isset($_POST['agent_group_id'])) {
    $agent_group_id = $_POST['agent_group_id'];
}
if (isset($_POST['agent_group_name'])) {
    $agent_group_name = $_POST['agent_group_name'];
}

$crsNme='';
$crsStatus='';
$selectCategory=$connect->query("SELECT * FROM agent_group_creation WHERE agent_group_name = '".$agent_group_name."' ");
while ($row=$selectCategory->fetch()){
	$crsNme    = $row["agent_group_name"];
	$crsStatus  = $row["status"];
}

if($crsNme != '' && $crsStatus == 0){
	$message="Agent Group Already Exists, Please Enter a Different Name!";
}
else if($crsNme != '' && $crsStatus == 1){
	$updateCategory=$connect->query("UPDATE agent_group_creation SET status=0 WHERE agent_group_name='".$agent_group_name."' ");
	$message="Agent Group Added Succesfully";
}
else{
	if($agent_group_id>0){
		$updateCategory=$connect->query("UPDATE agent_group_creation SET agent_group_name='".$agent_group_name."' WHERE agent_group_id='".$agent_group_id."' ");
		if($updateCategory == true){
		    $message="Agent Group Updated Succesfully";
	    }
    }
	else{
	    $insertCategory=$connect->query("INSERT INTO agent_group_creation(agent_group_name) VALUES('".strip_tags($agent_group_name)."')");
	    if($insertCategory == true){
		    $message="Agent Group Added Succesfully";
	    }
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>