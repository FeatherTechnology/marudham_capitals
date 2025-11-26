<?php
include '../ajaxconfig.php';

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$qry = $connect->query("SELECT ack.cus_id_loan, cr.autogen_cus_id, ack.cus_name_loan, ii.loan_id, ad.doc_id, lcc.loan_category_creation_name FROM acknowlegement_loan_calculation ack JOIN customer_register cr ON ack.cus_id_loan = cr.cus_id LEFT JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = ack.loan_category LEFT JOIN in_issue ii ON ii.req_id = ack.req_id LEFT JOIN acknowlegement_documentation ad ON ack.req_id = ad.req_id WHERE ack.req_id = $req_id ");
$row = $qry->fetch();

$response['cus_id'] = $row['cus_id_loan'];
$response['autogen_cus_id'] = $row['autogen_cus_id'];
$response['cus_name'] = $row['cus_name_loan'];
$response['loan_id'] = $row['loan_id'];
$response['doc_id'] = $row['doc_id'];
$response['loan_category'] = $row['loan_category_creation_name'];

echo json_encode($response);

// Close the database connection
$connect = null;
?>