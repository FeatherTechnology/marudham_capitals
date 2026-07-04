<?php
include "../ajaxconfig.php";
$cusid = $_POST['cus_id'];

$qry = $connect->prepare("SELECT COUNT(*) AS total_fingerprints
FROM fingerprints f
LEFT JOIN verification_family_info vfi ON f.adhar_num = vfi.relation_aadhar
WHERE f.adhar_num = ? || vfi.cus_id = ?");

$qry->execute([$cusid, $cusid]);
$response = (int) $qry->fetchColumn();

echo json_encode($response);

// Close the database connection
$connect = null;
?>