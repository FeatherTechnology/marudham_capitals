<?php
require '../ajaxconfig.php';

$cus_id                   = $_POST['cus_id'];
$famname                 = $_POST['famname'];
$relationship            = $_POST['relationship'];

if($relationship  == 'Other'){
    $other_remark            = $_POST['other_remark'];
    $other_address           = $_POST['other_address'];
}
else{
    $other_remark            = null;
    $other_address           = null;
}

$relation_dob            = ($_POST['relation_dob'] !='') ? $_POST['relation_dob'] : '0000-00-00';
$relation_age            = $_POST['relation_age'];
$relation_live_deceased  = $_POST['relation_live_deceased'];
$relation_aadhar         = preg_replace('/\s+/', '', $_POST['relation_aadhar']);
$relation_Mobile         = $_POST['relation_Mobile'];
$relation_Occupation     = $_POST['relation_Occupation'];
$relation_Income         = $_POST['relation_Income'];
$relation_Blood          = $_POST['relation_Blood'];
$famTableId              = $_POST['famTableId'];
$authorize               = ($_POST['authorize'] == 1) ? 1 : 0;


if($famTableId == ''){
    $insert_qry = $connect ->query("INSERT INTO `verification_family_info`(`cus_id`,`famname`, `relationship`,`authorize`, `other_remark`, `other_address`, `relation_dob`, `relation_age`, `relation_live_deceased`, `relation_aadhar`, `relation_Mobile`, `relation_Occupation`, `relation_Income`, `relation_Blood`) VALUES ('$cus_id','$famname','$relationship','$authorize','$other_remark','$other_address ','$relation_dob','$relation_age','$relation_live_deceased','$relation_aadhar','$relation_Mobile','$relation_Occupation','$relation_Income','$relation_Blood')");

} else {
    $update = $connect->query("UPDATE `verification_family_info` SET `cus_id`='$cus_id',`famname`='$famname',`relationship`='$relationship',`authorize`='$authorize',`other_remark`='$other_remark',`other_address`='$other_address',`relation_dob`='$relation_dob',`relation_age`='$relation_age',`relation_live_deceased`='$relation_live_deceased',`relation_aadhar`='$relation_aadhar',`relation_Mobile`='$relation_Mobile',`relation_Occupation`='$relation_Occupation',`relation_Income`='$relation_Income',`relation_Blood`='$relation_Blood' WHERE id = '$famTableId ' ");

}

if($insert_qry){
    $result = "Family Info Inserted Successfully.";
}
elseif($update){
    $result = "Family Info Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
?>