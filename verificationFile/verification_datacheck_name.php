<?php
require '../ajaxconfig.php';

if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$NameList = array();

$names = $connect->query("SELECT `famname`,`relationship`,`relation_aadhar`,`relation_Mobile` FROM `verification_family_info` WHERE  cus_id = '$cus_id' ");

while ($famName = $names->fetch()) {
    $famname = $famName['famname'];
    $relationship = $famName['relationship'];
    $aadhar = $famName['relation_aadhar'];
    $mobile = $famName['relation_Mobile'];

    $NameList[] = array("fam_name" => $famname, "aadhar" => $aadhar, "mobile" => $mobile, "relationship" => $relationship);
}


echo json_encode($NameList);

// Close the database connection
$connect = null;