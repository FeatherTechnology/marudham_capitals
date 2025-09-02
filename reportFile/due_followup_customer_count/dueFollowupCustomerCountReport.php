<?php
include('../../ajaxconfig.php');
$data = [];

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$toDate_month_start = date('Y-m-01', strtotime($to_date));

function monthDiff($start, $end)
{
    return ((date('Y', strtotime($end)) - date('Y', strtotime($start))) * 12)
        + (date('n', strtotime($end)) - date('n', strtotime($start)) + 1);
}

// Step 1: Fetch Due Nil req_ids in the same month and year as $to_date
$dueNilReqIds = [];
$dueNilQuery = $connect->query("SELECT DISTINCT cs2.req_id 
    FROM customer_status cs2
    JOIN collection col ON cs2.req_id = col.req_id
    WHERE 
        cs2.sub_status = 'Due Nil' 
        AND col.coll_sub_status = 'Current'
        AND MONTH(col.coll_date) = MONTH('$to_date')
        AND YEAR(col.coll_date) = YEAR('$to_date')
");
while ($row = $dueNilQuery->fetch(PDO::FETCH_ASSOC)) {
    $dueNilReqIds[] = $row['req_id'];
}
$dueNilReqIdStr = !empty($dueNilReqIds) ? implode(',', $dueNilReqIds) : 0;

// Step 2: Fetch Pending and Due Nil req_ids in the same month and year as $to_date to exclude
$pendingReqIds = [];
$pendingQuery = $connect->query("SELECT DISTINCT cs3.req_id 
    FROM customer_status cs3
    JOIN collection col ON cs3.req_id = col.req_id
    WHERE 
        cs3.sub_status IN('Current','Due Nil')
        AND col.coll_sub_status = 'Pending'
        AND MONTH(col.coll_date) = MONTH('$to_date')
        AND YEAR(col.coll_date) = YEAR('$to_date')
");
while ($row = $pendingQuery->fetch(PDO::FETCH_ASSOC)) {
    $pendingReqIds[] = $row['req_id'];
}
$pendingReqIdStr = !empty($pendingReqIds) ? implode(',', $pendingReqIds) : 0;

// Step 3: Fetch DueNil , Pending , OD req_ids in the same month and year as $to_date to exclude
$coll_DueNilReqIds = [];
$coll_DueNilQuery = $connect->query("SELECT DISTINCT cs5.req_id
FROM customer_status cs5
JOIN (
    SELECT c.req_id, MIN(c.coll_date) AS first_coll_date
    FROM collection c
    WHERE MONTH(c.coll_date) = MONTH('$to_date')
    AND YEAR(c.coll_date) = YEAR('$to_date')
    GROUP BY c.req_id
) first_col ON cs5.req_id = first_col.req_id
JOIN collection col
    ON col.req_id = first_col.req_id
    AND col.coll_date = first_col.first_coll_date
WHERE cs5.sub_status = 'Closed'
AND col.coll_sub_status IN ('Due Nil','Pending','OD');
");

while ($row = $coll_DueNilQuery->fetch(PDO::FETCH_ASSOC)) {
    $coll_DueNilReqIds[] = $row['req_id'];
}
$coll_DueNilReqIdStr = !empty($coll_DueNilReqIds) ? implode(',', $coll_DueNilReqIds) : 0;

// Loan Category Map
$loan_category_map = [];
$loanCatQry = $connect->query("SELECT loan_category_creation_id, loan_category_creation_name FROM loan_category_creation");
while ($row = $loanCatQry->fetch()) {
    $loan_category_map[$row['loan_category_creation_id']] = $row['loan_category_creation_name'];
}

// Pre-Fetch Paid Summary
$paidSummary = [];
$paidQry = $connect->query("SELECT 
        c.req_id, 
        SUM(c.due_amt_track) AS total_paid, 
        MIN(c.due_amt) AS monthly_due, 
        MIN(a.due_start_from) AS due_start_from, 
        MAX(c.coll_date) AS last_paid_date,
        COUNT(DISTINCT DATE_FORMAT(c.coll_date, '%Y-%m')) AS paid_month_count,
        tlmp.till_last_month_paid
    FROM collection c
    JOIN acknowlegement_loan_calculation a 
        ON c.req_id = a.req_id
    LEFT JOIN (
        SELECT 
            req_id, 
            SUM(due_amt_track) AS till_last_month_paid  
        FROM collection
        WHERE coll_date < DATE_FORMAT('$to_date', '%Y-%m-01')  -- all payments before current month
        GROUP BY req_id
    ) tlmp 
        ON tlmp.req_id = c.req_id
    WHERE DATE(c.coll_date) <= '$to_date'
    GROUP BY c.req_id
");


while ($row = $paidQry->fetch()) {
    $start = new DateTime($row['due_start_from']);
    $end = new DateTime($to_date);
    $months = ($end->format('Y') - $start->format('Y')) * 12 + ($end->format('m') - $start->format('m')) + 1;

    $paidSummary[$row['req_id']] = [
        'total_paid' => (float)$row['total_paid'],
        'expected_due' => (float)($months * $row['monthly_due']),
        'previous_due' => (float)(($months - 1) * $row['monthly_due']),
        'last_paid_date' => $row['last_paid_date'],
        'till_last_month_paid' => $row['till_last_month_paid'],
        'paid_month_count' => $row['paid_month_count'],
        'monthly_due' => (float)$row['monthly_due'],
        'due_start_from' => $row['due_start_from'],
        'future_due' => (float)(($months + 1) * $row['monthly_due']),
    ];
}

// Fetch user list
$userQry = $connect->query("SELECT user_id, fullname, due_followup_lines FROM user 
    WHERE due_followup_lines IS NOT NULL 
    AND due_followup_lines != '' 
    AND user_id = $user_id");

$sno = 1;
$grand_totals = [
    'total_count' => 0,
    'payable_zero' => 0,
    'responsible' => 0,
    'paid' => 0,
    'partial_paid' => 0,
    'unpaid' => 0,
];

while ($userRow = $userQry->fetch()) {
    $user_id = $userRow['user_id'];
    $fullname = $userRow['fullname'];
    $line_ids = array_filter(array_map('intval', explode(',', $userRow['due_followup_lines'])));

    $loan_category_data = [];
    $customer_loanId = [];

    foreach ($line_ids as $line_id) {
        $mapQry = $connect->query("SELECT area_id, loan_category_id, customer_status FROM area_duefollowup_mapping WHERE map_id = $line_id");
        if (!$mapRow = $mapQry->fetch()) continue;

        $area_ids = implode(',', array_filter(array_map('intval', explode(',', $mapRow['area_id']))));
        $loan_cat_ids = array_filter(array_map('intval', explode(',', $mapRow['loan_category_id'])));
        $status_list = "'" . implode("','", array_filter(array_map('trim', explode(',', $mapRow['customer_status'])))) . "'";

        if (!$area_ids || !$loan_cat_ids  || !$status_list) continue;

        foreach ($loan_cat_ids as $cat_id) {
            $cat_name = $loan_category_map[$cat_id] ?? "Unknown($cat_id)";
            if (!isset($loan_category_data[$cat_id])) {
                $loan_category_data[$cat_id] = [
                    'sno' => 0,
                    'fullname' => $fullname,
                    'loan_category' => $cat_name,
                    'total_count' => 0,
                    'payable_zero' => 0,
                    'responsible' => 0,
                    'paid' => 0,
                    'partial_paid' => 0,
                    'unpaid' => 0
                ];
            }

            $qry = $connect->query("SELECT 
                alc.loan_category, 
                ii.req_id, 
                iv.responsible, 
                ii.loan_id, 
                alc.due_start_from
                FROM 
                    in_issue ii
                    LEFT JOIN in_verification iv ON ii.req_id = iv.req_id
                    JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
                    JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id
                    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
                    JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
                    JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)
                    LEFT JOIN closing_customer cc ON ii.req_id = cc.req_id
                WHERE 
                    cp.area_confirm_area IN ($area_ids)
                    AND alc.loan_category = $cat_id
                    AND (
                        cs.sub_status IN ($status_list)
                        OR (
                            cs.sub_status = 'Closed'
                            AND cc.closing_date IS NOT NULL
                            AND MONTH(cc.closing_date) = MONTH('$to_date')
                            AND YEAR(cc.closing_date) = YEAR('$to_date')
                        )
                        OR ii.req_id IN ($dueNilReqIdStr)
                    )
                    AND ii.req_id NOT IN ($pendingReqIdStr) 
                    AND ii.req_id NOT IN ($coll_DueNilReqIdStr)
                    AND DATE(ii.updated_date) < '$toDate_month_start'
                GROUP BY ii.loan_id");

            while ($row = $qry->fetch()) {
                $req_id = $row['req_id'];
                $responsible = $row['responsible'];
                $due_start_from = $row['due_start_from'];

                $loan_category_data[$cat_id]['total_count']++;
                $customer_loanId = $row['loan_id'];
                print_r($customer_loanId);
                echo "<br>";

                if ($responsible === '0') {
                    $loan_category_data[$cat_id]['responsible']++;
                }

                if (isset($paidSummary[$req_id])) {
                    $paid = $paidSummary[$req_id]['total_paid'];
                    $expected = $paidSummary[$req_id]['expected_due'];
                    $previous_due = $paidSummary[$req_id]['previous_due'];
                    $future_due = $paidSummary[$req_id]['future_due'];
                    $last_paid = $paidSummary[$req_id]['last_paid_date'];
                    $due_start = $paidSummary[$req_id]['due_start_from'];
                    $monthly_due = $paidSummary[$req_id]['monthly_due'];
                    $till_last_month_paid = $paidSummary[$req_id]['till_last_month_paid'];
                    $paid_month_count = $paidSummary[$req_id]['paid_month_count'];

                    // Expected months from due start to to_date
                    $expected_months = monthDiff($due_start, $to_date);

                    switch (true) {
                        // Case 1: Advance Paid before due start (full month(s) paid ahead)
                        case (strtotime($due_start) > strtotime($to_date)):
                            $loan_category_data[$cat_id]['payable_zero']++;
                            break;

                        // Case 2: Fully Paid before this month
                        case ($paid >= $expected && strtotime($last_paid) < strtotime($toDate_month_start)):
                            $loan_category_data[$cat_id]['payable_zero']++;
                            break;

                        // Case 3: Fully Paid before this month 
                        case ($till_last_month_paid >= $expected):
                            $loan_category_data[$cat_id]['payable_zero']++;
                            break;

                        // Case 4: Paid more months than expected and amount is over expected or over expected + future due
                        case (
                            $paid_month_count > $expected_months && $paid >= ($expected + $monthly_due) &&
                            date('Y-m', strtotime($last_paid)) == date('Y-m', strtotime($to_date))
                        ):
                            $loan_category_data[$cat_id]['payable_zero']++;
                            break;

                        // Case 5: Due start in current month but only current due paid
                        case (date('Y-m', strtotime($due_start)) == date('Y-m', strtotime($toDate_month_start))
                            && $paid >= $expected):
                            $loan_category_data[$cat_id]['paid']++;
                            break;

                        // Case 6: Fully Paid (paid >= expected and last payment is this month)
                        case ($paid >= $expected
                            && date('Y-m', strtotime($last_paid)) == date('Y-m', strtotime($toDate_month_start))):
                            $loan_category_data[$cat_id]['paid']++;
                            break;

                        // Case 7: Partial Paid
                        case ($paid > 0 && $paid < $expected
                            && date('Y-m', strtotime($last_paid)) == date('Y-m', strtotime($toDate_month_start))):
                            $loan_category_data[$cat_id]['partial_paid']++;
                            break;

                        // Case 8: No Payment
                        case ($paid == 0):
                            $loan_category_data[$cat_id]['unpaid']++;
                            break;

                        // Case 9: Fallback
                        default:
                            $loan_category_data[$cat_id]['unpaid']++;
                            break;
                    }
                } elseif (
                    strtotime($due_start_from) > strtotime($to_date) &&
                    date('Y-m', strtotime($due_start_from)) != date('Y-m', strtotime($to_date))
                ) {
                    $loan_category_data[$cat_id]['payable_zero']++;
                } else {
                    $loan_category_data[$cat_id]['unpaid']++;
                }
            }
        }
    }

    foreach ($loan_category_data as $cat_data) {
        $cat_data['sno'] = $sno++;
        $data[] = $cat_data;

        foreach ($grand_totals as $key => $val) {
            $grand_totals[$key] += $cat_data[$key];
        }
    }
}

// Add total row
$data[] = [
    'sno' => '',
    'fullname' => 'Total',
    'loan_category' => '',
    'total_count' => $grand_totals['total_count'],
    'payable_zero' => $grand_totals['payable_zero'],
    'responsible' => $grand_totals['responsible'],
    'paid' => $grand_totals['paid'],
    'partial_paid' => $grand_totals['partial_paid'],
    'unpaid' => $grand_totals['unpaid'],
];

// Pagination
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : -1;

$recordsTotal = count($data);
$recordsFiltered = $recordsTotal;

if ($length != -1) {
    $data = array_slice($data, $start, $length);
}

// Output JSON
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
