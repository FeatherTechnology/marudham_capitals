<?php
require '../../ajaxconfig.php';

$req_id                = $_POST['reqId'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$doc_name              = $_POST['doc_name'];
$sign_type             = $_POST['sign_type'];
$signType_relationship = $_POST['signType_relationship'];
$doc_Count             = $_POST['doc_Count'];
$cus_profile_id        = $_POST['cus_profile_id'];
$signedID              = $_POST['signedID'];

if ($sign_type == '1') {
    $qry = $connect->query("SELECT fam.id from verification_family_info fam JOIN customer_profile cp on cp.guarentor_name = fam.id where cp.req_id = $req_id");
    // $qry = $connect->query("SELECT fam.id from verification_family_info fam JOIN customer_profile cp on cp.req_id = fam.req_id where fam.req_id = $req_id"); 
    $signType_relationship = $qry->fetch()['id'];
}

if ($signedID == '') {

    $insert_qry = $connect->query("INSERT INTO `signed_doc_info`(`cus_id`,`doc_name`, `sign_type`, `signType_relationship`, `doc_Count`, `req_id`, `cus_profile_id`) VALUES ('$cus_id','$doc_name','$sign_type','$signType_relationship','$doc_Count','$req_id','$cus_profile_id')");
} else {
    $update = $connect->query("UPDATE `signed_doc_info` SET `cus_id`='$cus_id',`doc_name`='$doc_name',`sign_type`='$sign_type',`signType_relationship`='$signType_relationship',`doc_Count`='$doc_Count' WHERE `id`='$signedID' ");
}

if ($insert_qry) {
    $result = "Signed Doc Info Inserted Successfully.";
} elseif ($update) {
    $result = "Signed Doc Info Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
