<?php
include '../ajaxconfig.php';

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}

$qry = $connect->query("SELECT lcc.loan_category_creation_name , ack.cus_id_loan, ack.cus_name_loan , ii.loan_id FROM acknowlegement_loan_calculation ack left join loan_category_creation lcc on lcc.loan_category_creation_id  = ack.loan_category  left join in_issue ii on ii.req_id = ack.req_id where ack.req_id = $req_id ");
$row = $qry->fetch();

$response['cus_id'] = $row['cus_id_loan'];
$response['cus_name'] = $row['cus_name_loan'];
$response['loan_category'] = $row['loan_category_creation_name'];
$response['loan_id'] = $row['loan_id'];


echo json_encode($response);

// Close the database connection
$connect = null;
?>