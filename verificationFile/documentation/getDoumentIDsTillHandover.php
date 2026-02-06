<?php
include "../../ajaxConfig.php";

$cusid = $_POST['cusid'];

$qry = $connect->prepare("SELECT doc_id, n.cus_status FROM acknowlegement_documentation ad JOIN noc n ON ad.req_id = n.req_id WHERE n.noc_replace_status = 1 AND ad.cus_id_doc = :cusid");
$qry->execute([':cusid' => $cusid]);
$stmt = $qry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($stmt);

// Close the database connection
$connect = null;
?>