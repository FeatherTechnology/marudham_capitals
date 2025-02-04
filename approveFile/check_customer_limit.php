<?php
include('../ajaxconfig.php');
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$qry = $connect->query("SELECT loan_limit FROM customer_register WHERE cus_id = $cus_id ");
if ($qry->rowCount() > 0) {
    $cus_loan_limit = $qry->fetch()['loan_limit'];
    if ($cus_loan_limit != '' and $cus_loan_limit != NULL) {
        echo json_encode(['cus_limit' => $cus_loan_limit]);
    } else {
        echo json_encode(['cus_limit' => '']);
    }
} else {
    echo json_encode(['cus_limit' => '']);
}


// Close the database connection
$connect = null;
