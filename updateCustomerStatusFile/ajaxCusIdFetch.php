<?php 
include '../ajaxconfig.php';
//Need to get all current customer's Request id to check the customer status and insert in the customer_status table to store status.

$customer_req_id = array();
$qry = $connect->query("SELECT cp.req_id as cp_req_id FROM acknowlegement_customer_profile cp JOIN in_issue ii ON cp.req_id = ii.req_id where ii.status = 0 and (ii.cus_status >= 14 and ii.cus_status <= 17) ");
$customer_req_id = array_column($qry->fetchAll(PDO::FETCH_ASSOC), 'cp_req_id');

echo json_encode($customer_req_id);
?>