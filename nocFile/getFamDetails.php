<?php
include('../ajaxconfig.php');
if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}
if(isset($_POST['cus_id'])){
    $cus_id = $_POST['cus_id'];
}

$result = $connect->query("SELECT id,famname,relationship FROM `verification_family_info` where cus_id='$cus_id'");

while( $row = $result->fetch()){
    $fam_name = $row['famname'];
    $fam_id = $row['id'];
    $relationship = $row['relationship'];
    $famList_arr[] = array("fam_id" => $fam_id, "fam_name" => $fam_name, "relationship" => $relationship);
}

echo json_encode($famList_arr)
?>