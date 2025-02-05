<?php
require '../ajaxconfig.php';

if (isset($_POST['reqId'])) {
    $reqId = $_POST['reqId'];
}

if (isset($_POST['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_POST['cus_id']); // Remove non-digit characters
}

$records = array();
$run = $connect->query("SELECT id, famname, relationship FROM `verification_family_info` WHERE cus_id = '" . $cus_id . "' ");
$cnt = $run->rowCount();

if ($cnt > 0) {
    while ($row = $run->fetch()) {
        $records[] = array(
            'id' => $row['id'],
            'fam_mem' => $row['famname'] . ' - ' . $row['relationship']
        );
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
