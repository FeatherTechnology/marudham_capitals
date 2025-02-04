<?php
require '../../ajaxconfig.php';

$req_id                = $_POST['cheque_req_id'];
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$chequeID              = $_POST['chequeID'];
$filesArr3             = $_FILES['cheque_upd'];
$cheque_upd_no         = explode(',', $_POST['cheque_upd_no']);
$holder_type           = $_POST['holder_type'];

if ($holder_type == '0' || $holder_type == '1') {
    $holderName = $_POST['holder_name'];
} else {
    $holderName = $_POST['holder_relationship_name'];
}

$connect->query("DELETE FROM `cheque_upd` WHERE `cheque_table_id`='$chequeID'");
$connect->query("DELETE FROM `cheque_no_list` WHERE `cheque_table_id`='$chequeID'");

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
        $update =  $connect->query("INSERT INTO `cheque_upd`(`cus_id`,`req_id`, `cheque_table_id`, `upload_cheque_name`) VALUES ('$cus_id','$req_id','$chequeID','$uniqueFileName')");
    }
}



foreach ($cheque_upd_no as $chequeNo) {
    $insert  = $connect->query("INSERT INTO `cheque_no_list`( `cus_id`,`req_id`, `cheque_table_id`, `cheque_holder_type`, `cheque_holder_name`, `cheque_no`) VALUES ('$cus_id','$req_id','$chequeID',' $holder_type','$holderName','$chequeNo')");
}


if ($update || $insert) {
    $result = "Cheque Uploaded Successfully.";
}

echo json_encode($result);

// Close the database connection
$connect = null;
