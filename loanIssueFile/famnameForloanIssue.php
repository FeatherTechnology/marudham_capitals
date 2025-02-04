<?php 
include('../ajaxconfig.php');

$famList_arr = array();

$reqId = $_POST['reqId'];
$cus_id = $_POST['cus_id'];
$result = $connect->query("SELECT id,famname,relationship,relation_aadhar FROM `verification_family_info` where cus_id='$cus_id'");

while( $row = $result->fetch()){
    $fam_name = $row['famname'];
     $fam_id = $row['id'];
     $relationship = $row['relationship'];
     $relation_aadhar = $row['relation_aadhar'];
    $famList_arr[] = array("fam_id" => $fam_id, "fam_name" => $fam_name, "relationship" => $relationship, "aadharno"=>$relation_aadhar);
}

echo json_encode($famList_arr);
?>