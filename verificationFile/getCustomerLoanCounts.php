<?php
include('../ajaxconfig.php');

if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$records = array();

$result = $connect->query("
    SELECT ii.cus_status, cs.created_date AS last_created_date
    FROM in_issue ii
    LEFT JOIN closed_status cs ON cs.req_id = ii.req_id
    WHERE ii.cus_id = '$cus_id' AND ii.cus_status >= 14
");

$records['loan_count'] = $result->rowCount();
$records['existing_type'] = '';
if ($records['loan_count'] > 0) {

    while ($res = $result->fetch(PDO::FETCH_ASSOC)) {

        // 1️⃣ Additional has highest priority
        if ($res['cus_status'] >= 14 && $res['cus_status'] < 20) {
            $records['existing_type'] = 'Additional';
            break; // stop checking further rows
        }

        // 2️⃣ Renewal / Re-Active logic (only if not Additional)
        if ($res['cus_status'] >= 20 && $records['existing_type'] != 'Additional') {

            $lastDate = $res['last_created_date'];

            if (!empty($lastDate)) {
                // End of the month of last created_date
                $monthEnd = date('Y-m-t', strtotime($lastDate));

                // First day of next month
                $nextMonthStart = date('Y-m-d', strtotime($monthEnd . ' +1 day'));

                // Add 3 months to calculate reactive date
                $reactiveDate = date('Y-m-d', strtotime($nextMonthStart . ' +3 months'));

                $today = date('Y-m-d');

                // Decide Renewal or Re-Active
                if ($today < $reactiveDate) {
                    $records['existing_type'] = 'Renewal';
                } else {
                    $records['existing_type'] = 'Re-active';
                }
            }
        }
    }
} else {
    $records['existing_type'] = 'Existing-New';
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
