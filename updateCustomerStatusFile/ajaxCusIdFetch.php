<?php 
include '../ajaxconfig.php';
//Need to get all current customer's Request id to check the customer status and insert in the customer_status table to store status.

$customer_req_id = array();
$qry = $connect->query("SELECT ii.req_id as cp_req_id FROM in_issue ii where ii.status = 0 and ii.cus_status BETWEEN 14 AND 17 ORDER BY ii.id ASC");
$customer_req_id = array_column($qry->fetchAll(PDO::FETCH_ASSOC), 'cp_req_id');

echo json_encode($customer_req_id);
?>