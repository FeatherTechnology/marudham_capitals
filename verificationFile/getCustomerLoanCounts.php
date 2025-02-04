<?php
include('../ajaxconfig.php');

if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$records = array();

$result = $connect->query("SELECT * FROM `in_issue` where cus_id='$cus_id' and cus_status >= 14 ");
$records['loan_count'] =  $result->rowCount();
$records['existing_type'] = '';

while ($res = $result->fetch()) {
    if ($res['cus_status'] >= 14 && $res['cus_status'] < 20) {
        $records['existing_type'] = 'Additional';
    } else if ($res['cus_status'] >= 20 && $records['existing_type'] != 'Additional') {
        $records['existing_type'] = 'Renewal';
    }
}

if ($records['loan_count'] > 0) {
    $result = $connect->query("SELECT created_date FROM `loan_issue` where cus_id='$cus_id' and balance_amount = 0 ORDER BY created_date LIMIT 1");
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