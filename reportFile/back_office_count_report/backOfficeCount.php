<?php
include '../../ajaxconfig.php';

$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$from_date = date('Y-m-d', strtotime($_POST['from_date']));
$fromDate_month_start = date('Y-m-01', strtotime($from_date));
$user_id = $_POST['user_id'] ?? '';
$data = [];

// **1. User query - Cached result**
$userStmt = $connect->prepare("SELECT user_id, fullname, due_followup_lines 
    FROM user WHERE due_followup_lines IS NOT NULL 
    AND due_followup_lines != '' AND user_id = ?");
$userStmt->execute([$user_id]);
$userRow = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$userRow) {
    echo json_encode(['data' => []]);
    exit;
}

$fullname = $userRow['fullname'];
$line_ids = array_filter(array_map('intval', explode(',', $userRow['due_followup_lines'])));
if (empty($line_ids)) {
    echo json_encode(['data' => []]);
    exit;
}

// **This will store the single unified row calculation**
$unified_metrics = [
    'total_count' => 0, 'payable_zero' => 0, 'responsible_zero' => 0, 'balance_count' => 0,
    'to_follow_paid' => 0, 'to_follow_unpaid' => 0, 'followed_paid' => 0, 'followed_unpaid' => 0,
    'mobile_commitment_paid' => 0, 'mobile_commitment_unpaid' => 0, 'mobile_unavailable_paid' => 0, 'mobile_unavailable_unpaid' => 0,
    'mobile_paid' => 0, 'mobile_total' => 0, 'direct_commitment_paid' => 0, 'direct_commitment_unpaid' => 0,
    'direct_unavailable_paid' => 0, 'direct_unavailable_unpaid' => 0, 'direct_paid' => 0, 'direct_total' => 0,
    'to_follow_unpaid_req_ids' => [], 'followed_unpaid_req_ids' => []
];

// **2. COMBINED OD + DueNil query - Single execution**
$specialReqStmt = $connect->prepare("
    SELECT DISTINCT cs.req_id, cs.sub_status
    FROM customer_status cs 
    JOIN collection col ON cs.req_id = col.req_id 
    WHERE cs.sub_status IN ('OD', 'Due Nil') 
    AND col.coll_sub_status IN ('Current','Pending','OD') 
    AND DATE_FORMAT(col.coll_date, '%Y-%m-01') >= DATE_FORMAT(?, '%Y-%m-01')
");
$specialReqStmt->execute([$from_date]);
$specialRows = $specialReqStmt->fetchAll(PDO::FETCH_ASSOC);

$odReqIds = $dueNilReqIds = [];
foreach ($specialRows as $row) {
    $row['sub_status'] == 'OD' ? $odReqIds[] = $row['req_id'] : $dueNilReqIds[] = $row['req_id'];
}
$odReqIdStr = $odReqIds ? implode(',', array_map('intval', $odReqIds)) : '0';
$dueNilReqIdStr = $dueNilReqIds ? implode(',', array_map('intval', $dueNilReqIds)) : '0';

// **OPTIMIZATION 1: Move Static DueNil Filtering Query OUTSIDE of the line loop**
$dueNilFilterStmt = $connect->prepare("
    SELECT DISTINCT cs5.req_id FROM customer_status cs5
    JOIN (
        SELECT c.req_id, MIN(c.coll_date) AS first_coll_date FROM collection c
        WHERE MONTH(c.coll_date) = MONTH(?) AND YEAR(c.coll_date) = YEAR(?) GROUP BY c.req_id
    ) first_col ON cs5.req_id = first_col.req_id
    JOIN collection col ON col.req_id = first_col.req_id AND col.coll_date = first_col.first_coll_date AND col.coll_sub_status = 'Due Nil'
    WHERE cs5.sub_status = 'Closed'
    UNION
    SELECT DISTINCT c.req_id FROM collection c 
    JOIN customer_status cs ON cs.req_id = c.req_id
    WHERE c.coll_sub_status = 'Due Nil' AND DATE(c.coll_date) > ? AND cs.sub_status = 'Closed'
    AND NOT EXISTS (SELECT 1 FROM collection x WHERE x.req_id = c.req_id AND YEAR(x.coll_date) = YEAR(c.coll_date) AND MONTH(x.coll_date) = MONTH(c.coll_date) AND x.coll_date < c.coll_date)
    AND (
        NOT EXISTS (SELECT 1 FROM collection s WHERE s.req_id = c.req_id AND YEAR(s.coll_date) = YEAR(?) AND MONTH(s.coll_date) = MONTH(?))
        OR (
            NOT EXISTS (SELECT 1 FROM collection s2 WHERE s2.req_id = c.req_id AND YEAR(s2.coll_date) = YEAR(?) AND MONTH(s2.coll_date) = MONTH(?))
            AND NOT EXISTS (SELECT 1 FROM collection p WHERE p.req_id = c.req_id AND YEAR(p.coll_date) = YEAR(DATE_SUB(?, INTERVAL 1 MONTH)) AND MONTH(p.coll_date) = MONTH(DATE_SUB(?, INTERVAL 1 MONTH)))
        )
    )
");
$dueNilFilterStmt->execute([$from_date, $from_date, $from_date, $from_date, $from_date, $from_date, $from_date, $from_date, $from_date]);
$colls_DueNilReqIds = $dueNilFilterStmt->fetchAll(PDO::FETCH_COLUMN);
// Convert to flip mapping once for O(1) lookups later
$colls_DueNilMap = array_flip($colls_DueNilReqIds);


// **OPTIMIZATION 2: Prepare statements ONCE outside the loop to reduce processing overhead**
$custStmt = $connect->prepare("
    SELECT ii.req_id, ii.loan_id, cs.sub_status, cs.bal_amnt, 
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
    WHERE adfm.map_id = ?
    AND (
        cs.bal_amnt > 0 
        OR (cs.sub_status = 'Closed' AND cc.closing_date IS NOT NULL
            AND (YEAR(cc.closing_date) > YEAR(?) OR (YEAR(cc.closing_date) = YEAR(?) 
                AND MONTH(cc.closing_date) >= MONTH(?))))
        OR ii.req_id IN ($odReqIdStr)
        OR ii.req_id IN ($dueNilReqIdStr)
    )
    AND DATE(ii.updated_date) < ?
    GROUP BY ii.req_id
");

// Pre-create time-stamp calculations to save CPU cycles inside iterations
$start_month = strtotime($fromDate_month_start);
$from_date_ym = date('Y-m', strtotime($from_date));
$from_date_start_ym = date('Y-m', $start_month);

// Loop through each line ID in the background
foreach ($line_ids as $current_line_id) {

    $custStmt->execute([$current_line_id, $from_date, $from_date, $from_date, $fromDate_month_start]);
    $customers = $custStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($customers)) {
        continue;
    }

    // Process valid IDs through the global filtering map
    $filtered_ids = [];
    foreach ($customers as $cust) {
        if (!isset($colls_DueNilMap[$cust['req_id']])) {
            $filtered_ids[] = $cust['req_id'];
        }
    }
    
    if (empty($filtered_ids)) {
        continue;
    }
    
    $filtered_ids_map = array_flip($filtered_ids);
    $id_list = implode(',', array_map('intval', $filtered_ids));

    // **5. Collection data retrieval**
    $collectionData = [];
    $colStmt = $connect->prepare("
        SELECT c.req_id, c.coll_date, c.due_amt_track
        FROM collection c
        WHERE c.req_id IN ($id_list) AND DATE(c.coll_date) <= ?
        ORDER BY c.req_id, c.coll_date
    ");
    $colStmt->execute([$from_date]);
    while ($col = $colStmt->fetch(PDO::FETCH_ASSOC)) {
        $collectionData[$col['req_id']][] = $col;
    }

    // Main loops matching and logical metrics
    $t_current_count = $responsible_zero = $balance_count = $payable_zero = 0;
    $balance_req_ids = [];
    $to_follow_unpaid_req_ids = [];
    $followed_unpaid_req_ids = [];

    foreach ($customers as $cust) {
        if (!isset($filtered_ids_map[$cust['req_id']])) continue;

        if ($cust['responsible'] == '0') {
            $responsible_zero++;
        }

        $collList = $collectionData[$cust['req_id']] ?? [];
        $end = min($cust['maturity_date'], $from_date);
        $start = $cust['due_start_from'];
        
        $months = (date('Y', strtotime($end)) - date('Y', strtotime($start))) * 12 + (date('m', strtotime($end)) - date('m', strtotime($start))) + 1;
        $pending_month = max(0, $months - 1);
        $collectedTillMonthStart = 0;

        foreach ($collList as $coll) {
            if (strtotime($coll['coll_date']) < $start_month) {
                $collectedTillMonthStart += (int)$coll['due_amt_track'];
            }
        }

        $payable_amount = ($months * $cust['due_amt_cal']) - $collectedTillMonthStart;
        $pending_amount_atMonthStart = ($pending_month * $cust['due_amt_cal']) - $collectedTillMonthStart;

        $maturity_ym = date('Y-m', strtotime($cust['maturity_date']));
        $isCurrentMonthStart = $payable_amount <= $cust['due_amt_cal'] && $pending_amount_atMonthStart <= 0 && (
            (($cust['due_method_scheme'] === '1' || $cust['due_method_calc'] === 'Monthly') && $maturity_ym >= $from_date_start_ym)
            || (($cust['due_method_scheme'] != '1' || $cust['due_method_calc'] != 'Monthly') && strtotime($cust['maturity_date']) > $start_month)
        );

        if ($isCurrentMonthStart) {
            $t_current_count++;
            if ($payable_amount > 0 && $cust['responsible'] != '0') {
                $balance_count++;
                $balance_req_ids[] = $cust['req_id'];
            }
            if ($payable_amount <= 0 && $cust['responsible'] != '0') {
                $payable_zero++;
            }
        }
    }

    if (!empty($balance_req_ids)) {
        $balance_req_str = implode(',', array_map('intval', $balance_req_ids));
        
        // **6. Commitment + Payment calculation**
        $to_follow_count = $to_follow_paid = $to_follow_unpaid = $followed_count = $followed_paid = 0;
        $payment_cache = [];
        $commitmentData = [];

        $mobile_commitment_paid = $mobile_commitment_unpaid = $mobile_unavailable_paid = $mobile_unavailable_unpaid = 0;
        $direct_commitment_paid = $direct_commitment_unpaid = $direct_unavailable_paid = $direct_unavailable_unpaid = 0;
        $mobile_total = $direct_total = $mobile_paid_count = $direct_paid_count = 0;

        $commitmentStmt = $connect->prepare("
            SELECT com.req_id, com.ftype, com.fstatus,
                   ROW_NUMBER() OVER (PARTITION BY com.req_id ORDER BY FIELD(com.fstatus, 8,1,2,3,4,5,6,7)) as rn
            FROM commitment com
            WHERE com.req_id IN ($balance_req_str)
            AND com.created_date BETWEEN ? AND ?
        ");
        $commitmentStmt->execute(["$from_date 00:00:00", "$to_date 23:59:59"]);

        while ($row = $commitmentStmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['rn'] > 1) continue;
            $commitmentData[$row['req_id']] = ['ftype' => $row['ftype'], 'fstatus' => (int)$row['fstatus']];
        }

        $paymentStmt = $connect->prepare("
            SELECT DISTINCT c.req_id FROM collection c
            WHERE c.req_id IN ($balance_req_str) AND DATE(c.coll_date) BETWEEN ? AND ? AND c.due_amt_track > 0
        ");
        $paymentStmt->execute([$from_date, $to_date]);
        while ($row = $paymentStmt->fetch(PDO::FETCH_ASSOC)) {
            $payment_cache[$row['req_id']] = true;
        }

        foreach ($balance_req_ids as $req_id) {
            $has_payment = isset($payment_cache[$req_id]);
            $has_commitment = isset($commitmentData[$req_id]);

            if ($has_commitment) {
                $followed_count++;
                $has_payment ? $followed_paid++ : $followed_unpaid_req_ids[] = (string)$req_id;
            } else {
                $to_follow_count++;
                $has_payment ? $to_follow_paid++ : $to_follow_unpaid_req_ids[] = (string)$req_id;
            }
        }
        
        $to_follow_unpaid = $to_follow_count - $to_follow_paid;
        $followed_unpaid = $followed_count - $followed_paid;

        foreach ($commitmentData as $req_id => $cData) {
            $ftype = $cData['ftype'];
            $fstatus = $cData['fstatus'];
            $has_payment = isset($payment_cache[$req_id]);

            if ($ftype == 2) { // Mobile
                $mobile_total++;
                if ($fstatus == 8) {
                    $mobile_paid_count++;
                } elseif ($fstatus == 1) {
                    $has_payment ? $mobile_commitment_paid++ : $mobile_commitment_unpaid++;
                } elseif (in_array($fstatus, [2, 3, 4, 5, 6, 7])) {
                    $has_payment ? $mobile_unavailable_paid++ : $mobile_unavailable_unpaid++;
                }
            } elseif ($ftype == 1) { // Direct
                $direct_total++;
                if ($fstatus == 8) {
                    $direct_paid_count++;
                } elseif ($fstatus == 1) {
                    $has_payment ? $direct_commitment_paid++ : $direct_commitment_unpaid++;
                } elseif (in_array($fstatus, [2, 3, 4, 5, 6, 7])) {
                    $has_payment ? $direct_unavailable_paid++ : $direct_unavailable_unpaid++;
                }
            }
        }

        // Merge calculations straight into parent accumulator container
        $unified_metrics['total_count'] += $t_current_count;
        $unified_metrics['payable_zero'] += $payable_zero;
        $unified_metrics['responsible_zero'] += $responsible_zero;
        $unified_metrics['balance_count'] += $balance_count;
        $unified_metrics['to_follow_paid'] += $to_follow_paid;
        $unified_metrics['to_follow_unpaid'] += $to_follow_unpaid;
        $unified_metrics['followed_paid'] += $followed_paid;
        $unified_metrics['followed_unpaid'] += $followed_unpaid;
        $unified_metrics['mobile_commitment_paid'] += $mobile_commitment_paid;
        $unified_metrics['mobile_commitment_unpaid'] += $mobile_commitment_unpaid;
        $unified_metrics['mobile_unavailable_paid'] += $mobile_unavailable_paid;
        $unified_metrics['mobile_unavailable_unpaid'] += $mobile_unavailable_unpaid;
        $unified_metrics['mobile_paid'] += $mobile_paid_count;
        $unified_metrics['mobile_total'] += $mobile_total;
        $unified_metrics['direct_commitment_paid'] += $direct_commitment_paid;
        $unified_metrics['direct_commitment_unpaid'] += $direct_commitment_unpaid;
        $unified_metrics['direct_unavailable_paid'] += $direct_unavailable_paid;
        $unified_metrics['direct_unavailable_unpaid'] += $direct_unavailable_unpaid;
        $unified_metrics['direct_paid'] += $direct_paid_count;
        $unified_metrics['direct_total'] += $direct_total;
        
        $unified_metrics['to_follow_unpaid_req_ids'] = array_values(array_unique(array_merge($unified_metrics['to_follow_unpaid_req_ids'], $to_follow_unpaid_req_ids)));
        $unified_metrics['followed_unpaid_req_ids'] = array_values(array_unique(array_merge($unified_metrics['followed_unpaid_req_ids'], $followed_unpaid_req_ids)));
    } else {
        // If there are no balanced requirement IDs, just pass up basic current tallies 
        $unified_metrics['total_count'] += $t_current_count;
        $unified_metrics['payable_zero'] += $payable_zero;
        $unified_metrics['responsible_zero'] += $responsible_zero;
    }
}

// **7. Format output into exactly a SINGLE unified user row record + GRAND TOTAL row**
$single_record = array_merge([
    'sno' => 1,
    'fullname' => $fullname ?? ''
], $unified_metrics);

$data[] = $single_record;

$data[] = array_merge([
    'sno' => 'Total', 
    'fullname' => 'GRAND TOTAL'
], $unified_metrics);

echo json_encode(['data' => $data]);