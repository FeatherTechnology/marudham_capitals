<?php
require '../../ajaxconfig.php';

$req_id                = $_POST['reqId'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$cus_profile_id        = $_POST['cus_profile_id'];
$holder_type              = $_POST['holder_type'];
$holder_name             = $_POST['holder_name'];
$holder_relationship_name = $_POST['holder_relationship_name'];
$cheque_relation             = $_POST['cheque_relation'];
$chequebank_name             = $_POST['chequebank_name'];
$cheque_count             = $_POST['cheque_count'];
$chequeID              = $_POST['chequeID'];


if ($chequeID == '') {

    $insert_qry = $connect->query("INSERT INTO `cheque_info`(`cus_id`,`req_id`, `cus_profile_id`, `holder_type`, `holder_name`, `holder_relationship_name`, `cheque_relation`, `chequebank_name`, `cheque_count`) VALUES ('$cus_id','$req_id','$cus_profile_id','$holder_type','$holder_name','$holder_relationship_name','$cheque_relation','$chequebank_name','$cheque_count')");
} else {
    $update = $connect->query("UPDATE `cheque_info` SET  `holder_type`='$holder_type',`holder_name`='$holder_name',`holder_relationship_name`='$holder_relationship_name',`cheque_relation`='$cheque_relation',`chequebank_name`='$chequebank_name',`cheque_count`='$cheque_count' WHERE  `id`='$chequeID' ");
}

if ($insert_qry) {
    $result = "Cheque Info Inserted Successfully.";
} elseif ($update) {
    $result = "Cheque Info Updated Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
