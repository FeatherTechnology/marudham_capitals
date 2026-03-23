<?php
//Also Using in Balance report js & Loan_issue_report js
include('../ajaxconfig.php');

$screen = $_POST['screen'] ?? '';

if ($screen == 'loan_issue') {
    $condition = " ";
} else {
    $condition = " AND status=0 ";
}

$loan_category_arr = array();

$result = $connect->query("SELECT * FROM loan_category_creation where 1 $condition");
while ($row = $result->fetch()) {
    $loan_category_creation_id = $row['loan_category_creation_id'];
    $loan_category_creation_name = $row['loan_category_creation_name'];
    $loan_category_arr[] = array("loan_category_creation_id" => $loan_category_creation_id, "loan_category_creation_name" => $loan_category_creation_name);
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
?>