
<?php
include '../../ajaxconfig.php';

$search_date = $_POST['search_date'];
$type            = $_POST['type'];
$line            = isset($_POST['line']) ? $_POST['line'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$group_map            = isset($_POST['group_map']) ? $_POST['group_map'] : '';
$due_followup            = isset($_POST['due_followup']) ? $_POST['due_followup'] : '';
$sub_status_type = $_POST['sub_status_type'];
$loan_category = $_POST['loan_category'];
$toDate_month_start = date('Y-m-01', strtotime($search_date));
function monthDiff($start, $end)
{
    return ((date('Y', strtotime($end)) - date('Y', strtotime($start))) * 12)
        + (date('n', strtotime($end)) - date('n', strtotime($start)) + 1);
}

if (!is_array($loan_category)) {
    $loan_category = [$loan_category];
}

if (!is_array($user_id)) {
    $user_id = explode(',', $user_id);
}
$user_id = array_unique(array_map('intval', $user_id));

if (!is_array($line)) {
    $line = explode(',', $line);
}
if (!is_array($group_map)) {
    $group_map = explode(',', $group_map);
}
if (!is_array($due_followup)) {
    $due_followup = explode(',', $due_followup);
}

// ==== Build condition depending on type ====
if ($type == 1) {
    // 🔹 Line based
    if (empty($line)) {
        echo json_encode(["data" => []]);
        exit;
    }

    $line_str  = implode(',', $line);
    $condition = "alm.map_id IN ($line_str)";
    $joinTable = "JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)";
    $nameField = "alm.line_name";
} else if ($type == 2) {
    // 🔹 User based
    if (empty($user_id)) {
        echo json_encode(["data" => []]);
        exit;
    }
    $user_id_str = implode(',', $user_id);

    $userQry = $connect->query("
        SELECT user_id, fullname, line_id 
        FROM user 
        WHERE user_id IN ($user_id_str) AND status = 0
    ");
    $userRows = $userQry->fetchAll();
    if (empty($userRows)) {
        echo json_encode(["data" => []]);
        exit;
    }

    $line_ids = [];
    $display_names = [];
    foreach ($userRows as $row) {
        $line_ids = array_merge($line_ids, explode(',', $row['line_id']));
        $display_names[$row['user_id']] = $row['fullname'];
    }
    $line_ids = array_unique(array_filter(array_map('intval', $line_ids)));

    if (empty($line_ids)) {
        echo json_encode(["data" => []]);
        exit;
    }
    $line_id_str = implode(',', $line_ids);
    $condition   = "alm.map_id IN ($line_id_str)";
    $joinTable   = "JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)";
    $userName    = implode(', ', array_unique($display_names));
    $nameField = "NULL";
} else if ($type == 3) {
    // 🔹 Group based
    if (empty($group_map)) {
        echo json_encode(["data" => []]);
        exit;
    }

    $group_str  = implode(',', $group_map);
    $condition  = "ag.map_id IN ($group_str)";
    $joinTable  = "JOIN area_group_mapping ag ON FIND_IN_SET(al.area_id, ag.area_id)";
    $nameField  = "ag.group_name";
} else if ($type == 4) {
    if (empty($due_followup)) {
        echo json_encode(["data" => []]);
        exit;
    }

    $due_followup_str = implode(',', $due_followup);
    $joinTable = "
    JOIN area_duefollowup_mapping adm ON FIND_IN_SET(al.area_id, adm.area_id)
";
    // Condition only for line_ids
    $condition = "adm.map_id IN ($due_followup_str)";
    $nameField = "adm.duefollowup_name";
}

$data = [];
$sno = 1;
$loan_category_map = [];
$loanCatQry = $connect->query("SELECT loan_category_creation_id, loan_category_creation_name FROM loan_category_creation");
while ($row = $loanCatQry->fetch()) {
    $loan_category_map[$row['loan_category_creation_id']] = $row['loan_category_creation_name'];
}
// Step 2: Fetch Pending Current req_ids to exclude
$odReqIds = [];
$odQuery = $connect->query("
    SELECT DISTINCT cs3.req_id 
    FROM customer_status cs3 
    JOIN collection col ON cs3.req_id = col.req_id 
    WHERE cs3.sub_status = 'OD' 
    AND col.coll_sub_status IN ('Current','Due Nil','Pending','OD') 
    AND (
        (MONTH(col.coll_date) = MONTH('$search_date') AND YEAR(col.coll_date) = YEAR('$search_date'))
    )
");

while ($row = $odQuery->fetch(PDO::FETCH_ASSOC)) {
    $odReqIds[] = $row['req_id'];
}

$odReqIdStr = !empty($odReqIds) ? implode(',', $odReqIds) : 'NULL';

// Step 3: Fetch DueNil Current req_ids to exclude
$DueNilReqIds = [];
$DueNilQuery = $connect->query("SELECT DISTINCT cs4.req_id 
    FROM customer_status cs4
    JOIN collection col ON cs4.req_id = col.req_id
    WHERE 
        cs4.sub_status = 'Due Nil'
        AND col.coll_sub_status IN ('Current','Due Nil','Pending','OD')
          AND (
        (MONTH(col.coll_date) = MONTH('$search_date') AND YEAR(col.coll_date) = YEAR('$search_date'))
    )
");
while ($row = $DueNilQuery->fetch(PDO::FETCH_ASSOC)) {
    $DueNilReqIds[] = $row['req_id'];
}
$DueNilReqIdStr = !empty($DueNilReqIds) ? implode(',', $DueNilReqIds) : 'NULL';

foreach ($loan_category as $cat_id) {
    // Step 1: Fetch customers
    $where = "AND alc.loan_category = $cat_id";
    if ($type == 4) {
        // add the due-followup's own loan category constraint here (now $cat_id is defined)
        $where .= " AND adm.loan_category_id = $cat_id";
    }

    $custQry = $connect->query("
        SELECT 
            ii.req_id,
            ii.loan_id,
            cs.sub_status,
            cs.bal_amnt,
             iv.responsible,
             alc.due_start_from,
            $nameField as map_name
        FROM in_issue ii
         LEFT JOIN in_verification iv ON ii.req_id = iv.req_id
        JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
        JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id
        LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
        JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        $joinTable
        LEFT JOIN closing_customer cc ON ii.req_id = cc.req_id
        WHERE $condition
         $where 
          AND (
                cs.bal_amnt > 0
             OR (
                cs.sub_status = 'Closed'
                AND cc.closing_date IS NOT NULL
                AND MONTH(cc.closing_date) = MONTH('$search_date')
                AND YEAR(cc.closing_date) = YEAR('$search_date')
             )
             OR (ii.req_id IN ($odReqIdStr))
             OR (ii.req_id IN ($DueNilReqIdStr))
          )
          AND DATE(ii.updated_date) < '$toDate_month_start'
    ");
    $customers = $custQry->fetchAll(PDO::FETCH_ASSOC);
    if (empty($customers)) continue;

    $req_ids = array_column($customers, 'req_id');

    // Step 2: Fetch collection info
    $collectionMap = [];
    if (!empty($req_ids)) {
        $id_list = implode(',', $req_ids);
        $colQry = $connect->query("
SELECT c.req_id, c.coll_sub_status, c.coll_date
FROM collection c
JOIN (
    SELECT req_id, 
           COALESCE(
               MIN(CASE 
                       WHEN DATE(coll_date) >= '$toDate_month_start' 
                        AND DATE(coll_date) <= '$search_date' 
                       THEN DATE(coll_date) 
                   END),
               MAX(CASE 
                       WHEN DATE(coll_date) < '$toDate_month_start' 
                       THEN DATE(coll_date) 
                   END)
           ) AS target_date
    FROM collection
    WHERE DATE(coll_date) <= '$search_date'
      AND req_id IN ($id_list)
    GROUP BY req_id
) first_rec
  ON c.req_id = first_rec.req_id
 AND DATE(c.coll_date) = first_rec.target_date
ORDER BY c.req_id, c.coll_date;
");
        while ($col = $colQry->fetch(PDO::FETCH_ASSOC)) {
            // Keep only the latest entry for each req_id
            if (!isset($collectionMap[$col['req_id']])) {
                $collectionMap[$col['req_id']] = $col;
            }
        }
        $paidSummary = [];
        $paidQry = $connect->query("SELECT 
    c.req_id, 
    SUM(c.due_amt_track) AS total_paid, 
    MIN(c.due_amt) AS monthly_due, 
    MIN(a.due_start_from) AS due_start_from, 
    MAX(c.coll_date) AS last_paid_date,
    COUNT(DISTINCT EXTRACT(YEAR_MONTH FROM c.coll_date)) AS paid_month_count,
    COALESCE(SUM(CASE WHEN c.coll_date < DATE_FORMAT('$search_date', '%Y-%m-01') 
                      THEN c.due_amt_track ELSE 0 END), 0) AS till_last_month_paid
FROM collection c
JOIN acknowlegement_loan_calculation a 
      ON c.req_id = a.req_id
WHERE c.coll_date <= '$search_date'
  AND c.req_id IN ($id_list)
GROUP BY c.req_id;
");
        while ($row = $paidQry->fetch()) {
            $start = new DateTime($row['due_start_from']);
            $end = new DateTime($search_date);
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
    }

    // Step 3: Pre-fetch "pending NIL" req_ids
    $currentReqIds = [];
    $currentQuery = $connect->query("
         SELECT DISTINCT cs4.req_id
FROM customer_status cs4
JOIN (
    SELECT c.req_id, MIN(c.coll_date) AS first_coll_date
    FROM collection c
    WHERE MONTH(c.coll_date) = MONTH('$search_date')
      AND YEAR(c.coll_date) = YEAR('$search_date')
    GROUP BY c.req_id
) first_col ON cs4.req_id = first_col.req_id
JOIN collection col
      ON col.req_id = first_col.req_id
     AND col.coll_date = first_col.first_coll_date
WHERE cs4.sub_status IN ('Closed','Due Nil')
  AND col.coll_sub_status IN ('Current');
    ");

    while ($row = $currentQuery->fetch(PDO::FETCH_ASSOC)) {
        $currentReqIds[$row['req_id']] = true;
    }

    $pendingReqIds = [];
    $pendingQuery = $connect->query("
    SELECT DISTINCT cs2.req_id 
    FROM customer_status cs2
    JOIN collection col ON cs2.req_id = col.req_id
    WHERE cs2.sub_status = 'Current'
      AND col.coll_sub_status = 'Pending'
      AND MONTH(col.coll_date) = MONTH('$search_date')
      AND YEAR(col.coll_date) = YEAR('$search_date')
");
    while ($row = $pendingQuery->fetch(PDO::FETCH_ASSOC)) {   // ✅ fixed here
        $pendingReqIds[$row['req_id']] = true;
    }

    // Step 4: Decide grouping
    if ($type == 1) {
        // Group by line name
        $groups = [];
        foreach ($customers as $cust) {
            $groups[$cust['map_name']][] = $cust;
        }
    } else if ($type == 2) {
        // Group everything together (user-wise)
        $groups = [$userName => $customers];
    } else if ($type == 3) {
        $groups = [];
        foreach ($customers as $cust) {
            $groups[$cust['map_name']][] = $cust;
        }
    } else if ($type == 4) {
        $groups = [];
        foreach ($customers as $cust) {
            $groups[$cust['map_name']][] = $cust; // now directly grouped by duefollowup_name
        }
    }

    // Step 5: Process each group
    foreach ($groups as $groupName => $custList) {
        $total_count = $t_current_count = $responsible_zero = $paid = $partially_paid = $unpaid = $payable_zero = 0;


        foreach ($custList as $cust) {
            $total_count++;
        $due_start = $cust['due_start_from'];
            // Count responsible = 0
            if ($cust['responsible'] == '0') {
                $responsible_zero++;
            }
            $coll = $collectionMap[$cust['req_id']] ?? null;
            $isCurrentCustomer = false;

            if (
                ($cust['sub_status'] == 'Current' && !isset($pendingReqIds[$cust['req_id']]))  // include true Current but not Pending
                || isset($currentReqIds[$cust['req_id']])  // include Closed/Due Nil with Current collection
            ) {
                
                $t_current_count++;
                $isCurrentCustomer = true;
                // echo $cust['loan_id'] . "<br>";
            }

            // Due Nil adjust
            if (
                $coll &&
                in_array($coll['coll_sub_status'], ['Pending', 'Current', 'OD', 'Due Nil']) &&
                $cust['sub_status'] === 'Due Nil'
            ) {
                $searchYearMonth = date('Y-m', strtotime($search_date));
                $req_id = $coll['req_id'];
                $checkQry = $connect->query("
                    SELECT 1 FROM collection 
                    WHERE req_id = '$req_id'
                      AND DATE_FORMAT(coll_date, '%Y-%m') = '$searchYearMonth'
                    LIMIT 1
                ");
                if ($checkQry->rowCount() == 0) {
                    $total_count--;
                }
            }
            if (
                $coll &&
                in_array($coll['coll_sub_status'], ['Due Nil']) &&
                $cust['sub_status'] === 'Closed'
            ) {
                $total_count--;
            }
            if ($isCurrentCustomer) {
                if (isset($paidSummary[$cust['req_id']])) {
                    $ps = $paidSummary[$cust['req_id']];

                    $paidAmt       = $ps['total_paid'];
                    $expected      = $ps['expected_due'];
                    $previous_due  = $ps['previous_due'];
                    $future_due    = $ps['future_due'];
                    $last_paid     = $ps['last_paid_date'];
                    $due_start     = $ps['due_start_from'];
                    $monthly_due   = $ps['monthly_due'];
                    $till_last_paid = $ps['till_last_month_paid'];
                    $paid_months   = $ps['paid_month_count'];

                    $expected_months = monthDiff($due_start, $search_date);

                    switch (true) {
                        case (strtotime($due_start) > strtotime($search_date)):
                            $payable_zero++;
                            // echo $cust['loan_id'] . "<br>";
                            break;

                        case ($paidAmt >= $expected && strtotime($last_paid) < strtotime($toDate_month_start)):
                            $payable_zero++;
                            //  echo $cust['loan_id'] . "<br>";
                            break;

                        case ($till_last_paid >= $expected):
                            $payable_zero++;
                            // echo $cust['loan_id'] . "<br>";
                            break;

                        case ($paid_months > $expected_months && $paidAmt >= ($expected + $monthly_due)
                            && date('Y-m', strtotime($last_paid)) == date('Y-m', strtotime($search_date))):
                            $payable_zero++;
                            // echo $cust['loan_id'] . "<br>";
                            break;

                        case (date('Y-m', strtotime($due_start)) == date('Y-m', strtotime($toDate_month_start))
                            && $paidAmt >= $expected):
                            $paid++;
                            break;

                        case ($paidAmt >= $expected
                            && date('Y-m', strtotime($last_paid)) == date('Y-m', strtotime($toDate_month_start))):
                            $paid++;
                            break;

                        case ($paidAmt > 0 && $paidAmt < $expected
                            && date('Y-m', strtotime($last_paid)) == date('Y-m', strtotime($toDate_month_start))):
                            $partially_paid++;
                            break;

                        case ($paidAmt == 0):
                            $unpaid++;
                            break;

                        default:
                            $unpaid++;
                            break;
                    }
                } elseif (
                    strtotime($due_start) > strtotime($search_date) &&
                    date('Y-m', strtotime($due_start)) != date('Y-m', strtotime($search_date))
                ) {
                    $payable_zero++;
                    // echo $cust['loan_id'] . "<br>";
                } else {
                    $unpaid++;
                }
            }
        }

        if ($type == 1) {
            $display_name = $groupName; // Line name
        } elseif ($type == 2) {
            $display_name = $userName;  // User fullname
        } elseif ($type == 3) {
            $display_name = $groupName; // Group name
        } elseif ($type == 4) {
            $display_name = $groupName; // Due Followup name
        }

        $data[] = [
            'sno' => $sno++,
            'date' => date('d-m-Y', strtotime($search_date)),
            'fullname' => $display_name,
            'loan_category' => $loan_category_map[$cat_id] ?? $cat_id,
            'total_count' => $total_count,
            't_current_count' => $t_current_count,
            'payable_zero' => $payable_zero,
            'responsible_zero' => $responsible_zero,  // ✅ new column
            'paid' => $paid,
            'partially_paid' => $partially_paid,
            'unpaid' => $unpaid
        ];
    }
}



$grand_total = [
    'sno' => '',
    'date' => '',
    'fullname' => 'Total',
    'loan_category' => '',
    'total_count' => 0,
    't_current_count' => 0,
    'payable_zero' => 0,
    'responsible_zero' => 0,
    'paid' => 0,
    'partially_paid' => 0,
    'unpaid' => 0
];

foreach ($data as $row) {
    $grand_total['total_count']       += $row['total_count'];
    $grand_total['t_current_count']   += $row['t_current_count'];
    $grand_total['payable_zero']   += $row['payable_zero'];
    $grand_total['responsible_zero']   += $row['responsible_zero'];
    $grand_total['paid'] += $row['paid'];
    $grand_total['partially_paid']    += $row['partially_paid'];
    $grand_total['unpaid']            += $row['unpaid'];
}

// Append totals to the end
$data[] = $grand_total;

echo json_encode(["data" => $data]);
