<?php
include "../../ajaxConfig.php";

$cusid = $_POST['cusid'];

$qry = $connect->prepare("SELECT doc_id FROM acknowlegement_documentation ad JOIN in_issue ii ON ad.req_id = ii.req_id WHERE ii.cus_id = :cusid AND ii.cus_status BETWEEN 14 AND 23");
$qry->execute([':cusid' => $cusid]);
$stmt = $qry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($stmt);

// Close the database connection
$connect = null;
?>