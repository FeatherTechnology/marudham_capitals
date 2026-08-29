<?php
//Also using in edit_loan_issue.js
include '../ajaxconfig.php';

if(isset($_POST["cus_id"])){
    $cus_id = $_POST["cus_id"];
}

$records = array();
$how_to_know_obj = [
    '0' => 'Customer Reference',
    '1' => 'Advertisement',
    '2' => 'Promotion Activity',
    '3' => 'Agent Reference',
    '4' => 'Staff Reference',
    '5' => 'Other Reference',
    '6' => 'Renewal'
];

$qry = $connect->query("SELECT autogen_cus_id, cus_id, customer_name, how_to_know, monthly_income, other_income,income_date, support_income, commitment, monthly_due_capacity, loan_limit, about_customer FROM customer_register WHERE cus_id = $cus_id");
if($qry->rowCount() > 0){
    $row = $qry->fetch();
    $records['autogen_cus_id'] = $row['autogen_cus_id'];
    $records['cus_id'] = $row['cus_id'];
    $records['customer_name'] = $row['customer_name'];
    $records['how_to_know'] = $how_to_know_obj[$row['how_to_know']];
    $records['monthly_income'] = $row['monthly_income'];
    $records['other_income'] = $row['other_income'];
    $records['income_date'] = $row['income_date'];
    $records['support_income'] = $row['support_income'];
    $records['commitment'] = $row['commitment'];
    $records['monthly_due_capacity'] = $row['monthly_due_capacity'];
    $records['loan_limit'] = $row['loan_limit'];
    $records['about_customer'] = $row['about_customer'];
}

//using in loan issue 
$result = $connect->query("
    SELECT ii.cus_status, cs.created_date AS last_created_date
    FROM in_issue ii
    LEFT JOIN closed_status cs ON cs.req_id = ii.req_id
    WHERE ii.cus_id = '$cus_id' AND ii.cus_status >= 14
");

$records['loan_count'] = $result->rowCount();

if ($records['loan_count'] > 0) {
    $result = $connect->query("SELECT created_date FROM loan_issue WHERE cus_id='$cus_id' and balance_amount = 0 ORDER BY created_date LIMIT 1");
    $res = $result->fetch();
    $first_loan_date = date('d-m-Y', strtotime($res['created_date']));

    $records['first_loan'] =  $first_loan_date;

    $now = new DateTime(); // current datetime object
    $custom = new DateTime($res['created_date']); // custom datetime object

    $diff = $custom->diff($now); // difference between two dates

    $years = $diff->y; // number of years in difference
    $months = $diff->m; // number of months in difference

    $records['travel'] = $months . ' Months,' . $years . ' Years.';
} else {
    $records['first_loan'] = '';
    $records['travel'] = '';
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>