<?php
require '../../ajaxconfig.php';

$cus_id                     = preg_replace('/\D/', '', $_POST['cus_id']);
$req_id                     = $_POST['cheque_req_id'];
$holder_type                = $_POST['holder_type'];
$holder_name                = '';
$holder_relationship_name   = '';

if ($holder_type == '0' || $holder_type == '1') {
    $holder_name = $_POST['holder_name'];
} else {
    $holder_relationship_name = $_POST['holder_relationship_name'];
}

$holderName = ($holder_type == '0' || $holder_type == '1') ? $holder_name : $holder_relationship_name;

$cheque_relation           = $_POST['cheque_relation'];
$chequebank_name           = $_POST['chequebank_name'];
$cheque_count              = $_POST['cheque_count'];
$cheque_upd_no             = explode(',', $_POST['cheque_upd_no']);
$chequeID                  = $_POST['chequeID'];
$cus_profile_id            = $_POST['cus_profile_id'];

if ($chequeID == '') {

    $insert = $connect->query("INSERT INTO `cheque_info`(`cus_id`,`req_id`, `cus_profile_id`, `holder_type`, `holder_name`, `holder_relationship_name`, `cheque_relation`, `chequebank_name`, `cheque_count`) VALUES ('$cus_id','$req_id','$cus_profile_id','$holder_type','$holder_name','$holder_relationship_name','$cheque_relation','$chequebank_name','$cheque_count')");
    $chequeID = $connect->lastInsertId();

} else {
    $insert = $connect->query("UPDATE `cheque_info` SET  `holder_type`='$holder_type',`holder_name`='$holder_name',`holder_relationship_name`='$holder_relationship_name',`cheque_relation`='$cheque_relation',`chequebank_name`='$chequebank_name',`cheque_count`='$cheque_count' WHERE  `id`='$chequeID' ");
}


$connect->query("DELETE FROM `cheque_upd` WHERE `cheque_table_id`='$chequeID'");
$connect->query("DELETE FROM `cheque_no_list` WHERE `cheque_table_id`='$chequeID'");

if (isset($_FILES['cheque_upd']) && isset($_FILES['cheque_upd']['name']) && is_array($_FILES['cheque_upd']['name'])) {

    $filesArr3 = $_FILES['cheque_upd'];

    foreach ($filesArr3['name'] as $key => $val) {
        $fileName = basename($filesArr3['name'][$key]);
        $targetFilePath = "../../uploads/verification/cheque_upd/" . $fileName;

        $fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $uniqueFileName = uniqid() . '.' . $fileExtension;
        while (file_exists("../../uploads/verification/cheque_upd/" . $uniqueFileName)) {
            $uniqueFileName = uniqid() . '.' . $fileExtension;
        }

        if (move_uploaded_file($filesArr3["tmp_name"][$key], "../../uploads/verification/cheque_upd/" . $uniqueFileName)) {
            // Perform database insertion
            $insert =  $connect->query("INSERT INTO `cheque_upd`(`cus_id`,`req_id`, `cheque_table_id`, `upload_cheque_name`) VALUES ('$cus_id','$req_id','$chequeID','$uniqueFileName')");
        }
    }
}


foreach ($cheque_upd_no as $chequeNo) {
    $insert  = $connect->query("INSERT INTO `cheque_no_list`( `cus_id`,`req_id`, `cheque_table_id`, `cheque_holder_type`, `cheque_holder_name`, `cheque_no`) VALUES ('$cus_id','$req_id','$chequeID',' $holder_type','$holderName','$chequeNo')");
}


if ($insert) {
    $result = "Cheque Uploaded Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
