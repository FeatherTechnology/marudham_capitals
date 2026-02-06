<?php
include('../../ajaxconfig.php');

$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$toDate_month_start = date('Y-m-01', strtotime($to_date));
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

function monthDiff($start, $end)
{
    return ((date('Y', strtotime($end)) - date('Y', strtotime($start))) * 12)
        + (date('n', strtotime($end)) - date('n', strtotime($start)) + 1);
}

// ===== Extra ID Collections =====
$odReqIds = [];
$odQuery = $connect->query("SELECT DISTINCT cs.req_id
    FROM customer_status cs
    JOIN collection col ON cs.req_id = col.req_id
    WHERE cs.sub_status='OD'
    AND col.coll_sub_status IN ('Current','Due Nil','Pending','OD')
    AND DATE_FORMAT(col.coll_date, '%Y-%m-01') >= DATE_FORMAT('$to_date', '%Y-%m-01')");
while ($row = $odQuery->fetch(PDO::FETCH_ASSOC)) $odReqIds[] = $row['req_id'];
$odReqIdStr = !empty($odReqIds) ? implode(',', $odReqIds) : 0;

$DueNilReqIds = [];
$DueNilQuery = $connect->query("SELECT DISTINCT cs.req_id
    FROM customer_status cs
    JOIN collection col ON cs.req_id = col.req_id
    WHERE cs.sub_status='Due Nil'
    AND col.coll_sub_status IN ('Current','Due Nil','Pending','OD')
    AND DATE_FORMAT(col.coll_date, '%Y-%m-01') >= DATE_FORMAT('$to_date', '%Y-%m-01')");
while ($row = $DueNilQuery->fetch(PDO::FETCH_ASSOC)) $DueNilReqIds[] = $row['req_id'];
$DueNilReqIdStr = !empty($DueNilReqIds) ? implode(',', $DueNilReqIds) : 0;

// ===== Loan Category Map =====
$loan_category_map = [];
$loanCatQry = $connect->query("SELECT loan_category_creation_id, loan_category_creation_name FROM loan_category_creation");
while ($row = $loanCatQry->fetch()) {
    $loan_category_map[$row['loan_category_creation_id']] = $row['loan_category_creation_name'];
}

// ===== User List =====
$userQry = $connect->query("SELECT user_id, fullname, due_followup_lines 
    FROM user WHERE due_followup_lines IS NOT NULL AND due_followup_lines != '' 
    AND user_id = $user_id");

$loan_category = [];
$loanCategoryQry = $connect->query("SELECT DISTINCT loan_category FROM loan_calculation WHERE status = 0");
while ($row = $loanCategoryQry->fetch(PDO::FETCH_ASSOC)) {
    $loan_category[] = (int)$row['loan_category'];
}

$sno = 1;
$data = [];
$grand_totals = [
    'total_count' => 0,
    't_current_count' => 0,
    'payable_zero' => 0,
    'responsible_zero' => 0,
    'paid' => 0,
    'partially_paid' => 0,
    'unpaid' => 0,
    'balance_count' => 0,
    'total_paid' => 0
];

while ($userRow = $userQry->fetch()) {
    $user_id = $userRow['user_id'];
    $fullname = $userRow['fullname'];
    $line_ids = array_filter(array_map('intval', explode(',', $userRow['due_followup_lines'])));
    $line_ids_str = implode(',', $line_ids);
    $condition = "adfm.map_id IN ($line_ids_str)";

    $loan_category_data = [];

    foreach ($loan_category as $cat_id) {
        $cat_name = $loan_category_map[$cat_id] ?? "Unknown($cat_id)";
        if (!isset($loan_category_data[$cat_id])) {
            $loan_category_data[$cat_id] = [
                'sno' => 0,
                'fullname' => $fullname,
                'loan_category' => $cat_name,
                'total_count' => 0,
                't_current_count' => 0,
                'payable_zero' => 0,
                'responsible_zero' => 0,
                'paid' => 0,
                'partially_paid' => 0,
                'unpaid' => 0,
                'balance_count' => 0,
                'total_paid' => 0,
                'paid_percentage' => 0,
                'unpaid_percentage' => 0
            ];
        }

        // ===== Fetch Customers =====
        $custQry = $connect->query("SELECT ii.req_id, ii.loan_id, cs.sub_status, cs.bal_amnt, 
                iv.responsible, alc.due_amt_cal, alc.tot_amt_cal,
                alc.due_start_from, alc.due_method_scheme, alc.due_method_calc, 
                alc.maturity_month as maturity_date
                FROM in_issue ii
                LEFT JOIN in_verification iv ON ii.req_id = iv.req_id
                JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
                JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id
                LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
                JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
                LEFT JOIN closing_customer cc ON ii.req_id = cc.req_id
                JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = al.area_id
                JOIN area_duefollowup_mapping adfm ON adfm.map_id = adfma.duefollowup_map_id
                WHERE 
                alc.loan_category = $cat_id
                AND $condition
                AND (cs.bal_amnt > 0 
                    OR (cs.sub_status='Closed' AND cc.closing_date IS NOT NULL
                        AND (YEAR(cc.closing_date) > YEAR('$to_date')
                        OR (YEAR(cc.closing_date) = YEAR('$to_date') 
                        AND MONTH(cc.closing_date) >= MONTH('$to_date'))))
                    OR ii.req_id IN ($odReqIdStr)
                    OR ii.req_id IN ($DueNilReqIdStr)
                )
                AND DATE(ii.updated_date) < '$toDate_month_start' 
                GROUP BY ii.req_id");

        $customers = $custQry->fetchAll(PDO::FETCH_ASSOC);
        if (!$customers) continue;

        // ===== Fix: total_count counts all fetched customers before filtering =====
        $loan_category_data[$cat_id]['total_count'] += count($customers);

        $req_ids = array_column($customers, 'req_id');

        // ===== Exclude Collection Due Nil =====
        $coll_DueNilReqIds = [];
        $coll_DueNilQuery = $connect->query("SELECT DISTINCT cs.req_id
                FROM customer_status cs
                JOIN (SELECT c.req_id, MIN(c.coll_date) AS first_coll_date
                    FROM collection c
                    WHERE MONTH(c.coll_date) = MONTH('$to_date') AND YEAR(c.coll_date) = YEAR('$to_date')
                    GROUP BY c.req_id) fc ON cs.req_id = fc.req_id
                JOIN collection col ON col.req_id = fc.req_id AND col.coll_date = fc.first_coll_date
                WHERE cs.sub_status='Closed' AND col.coll_sub_status='Due Nil'");
        while ($row = $coll_DueNilQuery->fetch(PDO::FETCH_ASSOC)) $coll_DueNilReqIds[] = $row['req_id'];

        $filtered_ids = array_diff($req_ids, $coll_DueNilReqIds);
        if (!$filtered_ids) continue;
        $id_list = implode(',', $filtered_ids);

        // ===== Collection Data =====
        $collectionData = [];
        $colQry = $connect->query("SELECT req_id, coll_date, payable_amt, due_amt_track, total_paid_track
                FROM collection WHERE req_id IN ($id_list) AND DATE(coll_date) <= '$to_date' 
                ORDER BY req_id, coll_date ASC");
        while ($col = $colQry->fetch(PDO::FETCH_ASSOC)) {
            $collectionData[$col['req_id']][] = $col;
        }

        // ===== Paid Summary =====
        $paidSummary = [];
        // $current_loanId = []; // For debugging purpose
        $paidQry = $connect->query("SELECT c.req_id, SUM(c.due_amt_track) AS total_paid,
                MIN(c.due_amt) AS monthly_due, MIN(a.due_start_from) AS due_start_from,
                MAX(c.coll_date) AS last_paid_date,
                COUNT(DISTINCT EXTRACT(YEAR_MONTH FROM c.coll_date)) AS paid_month_count,
                COALESCE(SUM(CASE WHEN c.coll_date < DATE_FORMAT('$to_date','%Y-%m-01') THEN c.due_amt_track ELSE 0 END),0) AS till_last_month_paid
                FROM collection c
                JOIN acknowlegement_loan_calculation a ON c.req_id = a.req_id
                WHERE DATE(c.coll_date)<= '$to_date' AND c.req_id IN ($id_list)
                GROUP BY c.req_id");

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
                'future_due' => (float)(($months + 1) * $row['monthly_due'])
            ];
        }

        // ===== Classification =====
        foreach ($customers as $row) {
            $rid = $row['req_id'];
            if (!in_array($rid, $filtered_ids)) continue;

            $collList = $collectionData[$rid] ?? [];
            $end = strtotime(min($row['maturity_date'], $to_date));
            $start = strtotime($row['due_start_from']);
            $months = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)) + 1;
            $pending_month = max(0, $months - 1);
            $start_month = strtotime(date('Y-m-01', strtotime($to_date)));
            $collectedTillMonthStart = 0;

            foreach ($collList as $coll) {
                $collDate = strtotime($coll['coll_date']);
                if ($collDate < $start_month) $collectedTillMonthStart += (int)$coll['due_amt_track'];
            }

            $payable_amount = ($months * $row['due_amt_cal']) - $collectedTillMonthStart;
            $pending_amount_atMonthStart = ($pending_month * $row['due_amt_cal']) - $collectedTillMonthStart;

            // ===== Determine current and balance customer =====
            $isCurrentCustomer = false;
            $isBalanceCustomer = false;

            $iscurrentMonthStart  = $payable_amount <= $row['due_amt_cal']
                && $pending_amount_atMonthStart <= 0
                && (
                    (($row['due_method_scheme'] === '1' || $row['due_method_calc'] === 'Monthly')
                        && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', $start_month))
                    || (($row['due_method_scheme'] != '1' || $row['due_method_calc'] != 'Monthly')
                        && strtotime($row['maturity_date']) > $start_month)
                );

            if ($iscurrentMonthStart) {
                $loan_category_data[$cat_id]['t_current_count']++;
                // $current_loanId = $row['loan_id']; // For debugging purpose
                // print_r($current_loanId); // For debugging purpose
                // echo "<br>"; // For debugging purpose

                $isCurrentCustomer = true;

                // Balance customer check
                if ($payable_amount > 0 && $row['responsible'] != 0) {
                    $loan_category_data[$cat_id]['balance_count']++;
                    $isBalanceCustomer = true;
                }
            }

            // ===== Responsible Zero (current customer) =====
            if ($isCurrentCustomer && $row['responsible'] == '0') {
                $loan_category_data[$cat_id]['responsible_zero']++;
            }

            // ===== Payable Zero (current customer) =====
            if ($isCurrentCustomer && $payable_amount <= 0 && $row['responsible'] != '0') {
                $loan_category_data[$cat_id]['payable_zero']++;
            }

            // ===== Paid / Partially Paid / Unpaid (balance customer only) =====
            if ($isBalanceCustomer) {
                if (isset($paidSummary[$rid])) {
                    $p = $paidSummary[$rid];
                    $expected_months = monthDiff($p['due_start_from'], $to_date);

                    switch (true) {
                        case ($p['total_paid'] >= $p['expected_due']
                            && date('Y-m', strtotime($p['last_paid_date'])) == date('Y-m', $start_month)):
                            $loan_category_data[$cat_id]['paid']++;
                            break;

                        case ($p['total_paid'] > 0 && $p['total_paid'] < $p['expected_due']
                            && date('Y-m', strtotime($p['last_paid_date'])) == date('Y-m', $start_month)):
                            $loan_category_data[$cat_id]['partially_paid']++;
                            break;

                        case ($p['total_paid'] == 0):
                        default:
                            $loan_category_data[$cat_id]['unpaid']++;
                            break;
                    }
                } else {
                    $loan_category_data[$cat_id]['unpaid']++;
                }
            }
        }
        // end customer loop

        // ===== Calculate total paid & percentages =====
        $total_paid = $loan_category_data[$cat_id]['paid'] + $loan_category_data[$cat_id]['partially_paid'];

        $balance = $loan_category_data[$cat_id]['balance_count'];

        $loan_category_data[$cat_id]['total_paid'] = $total_paid;

        $loan_category_data[$cat_id]['paid_percentage'] = ($balance > 0) ? round(($total_paid / $balance) * 100, 1) : 0;

        $loan_category_data[$cat_id]['unpaid_percentage'] = ($balance > 0) ? round(($loan_category_data[$cat_id]['unpaid'] / $balance) * 100, 1) : 0;
    } // end loan category loop

    foreach ($loan_category_data as $cat_data) {
        if ($cat_data['t_current_count'] > 0) {   // only keep if total_count > 0
            $cat_data['sno'] = $sno++;
            $data[] = $cat_data;
            foreach ($grand_totals as $key => $val) {
                $grand_totals[$key] += $cat_data[$key];
            }
        }
    }
}

$total_paid = $grand_totals['paid'] + $grand_totals['partially_paid'];
$balance = $grand_totals['balance_count'];

$data[] = [
    'sno' => '',
    'fullname' => 'Total',
    'loan_category' => '',
    't_current_count' => $grand_totals['t_current_count'],
    'payable_zero' => $grand_totals['payable_zero'],
    'responsible_zero' => $grand_totals['responsible_zero'],
    'paid' => $grand_totals['paid'],
    'partially_paid' => $grand_totals['partially_paid'],
    'unpaid' => $grand_totals['unpaid'],
    'balance_count' => $balance,
    'total_paid' => $total_paid,
    'paid_percentage' => ($balance > 0) ? number_format(round(($total_paid / $balance) * 100, 1), 1) : '0.0',
    'unpaid_percentage' => ($balance > 0) ? number_format(round(($grand_totals['unpaid'] / $balance) * 100, 2), 1) : '0.0'
];

// ===== Pagination =====
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : -1;

$recordsTotal = count($data);
$recordsFiltered = $recordsTotal;

if ($length != -1) $data = array_slice($data, $start, $length);

// ===== Output JSON =====
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
