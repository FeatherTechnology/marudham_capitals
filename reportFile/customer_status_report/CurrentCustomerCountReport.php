
<?php
include '../../ajaxconfig.php';

$search_date        = $_POST['search_date'];
$type               = $_POST['type'];
$line               = isset($_POST['line']) ? $_POST['line'] : '';
$user_id            = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$group_map          = isset($_POST['group_map']) ? $_POST['group_map'] : '';
$due_followup       = isset($_POST['due_followup']) ? $_POST['due_followup'] : '';
$sub_status_type    = $_POST['sub_status_type'];
$loan_category      = $_POST['loan_category'];
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

    $userQry = $connect->query("SELECT user_id, fullname, line_id 
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
    $nameField   = "NULL";
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
    $joinTable = "JOIN area_duefollowup_mapping adm ON FIND_IN_SET(al.area_id, adm.area_id)";
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
$odQuery = $connect->query("SELECT DISTINCT cs3.req_id 
    FROM customer_status cs3 
    JOIN collection col ON cs3.req_id = col.req_id 
    WHERE cs3.sub_status = 'OD' 
    AND col.coll_sub_status IN ('Current','Pending','OD') 
    AND DATE_FORMAT(col.coll_date, '%Y-%m-01') >= DATE_FORMAT('$search_date', '%Y-%m-01');
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
        AND DATE_FORMAT(col.coll_date, '%Y-%m-01') >= DATE_FORMAT('$search_date', '%Y-%m-01');
");

while ($row = $DueNilQuery->fetch(PDO::FETCH_ASSOC)) {
    $DueNilReqIds[] = $row['req_id'];
}
$DueNilReqIdStr = !empty($DueNilReqIds) ? implode(',', $DueNilReqIds) : 'NULL';

foreach ($loan_category as $cat_id) {
    // Step 1: Fetch customers
    $where = "AND alc.loan_category = $cat_id";

    $custQry = $connect->query("SELECT 
        ii.req_id,
        ii.loan_id,
        cs.sub_status,
        cs.bal_amnt,
        iv.responsible,
        alc.due_amt_cal,
        alc.tot_amt_cal,
        alc.due_start_from,
        alc.due_method_scheme,
        alc.due_method_calc,
        alc.maturity_month as maturity_date,
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
                    AND (
                        YEAR(cc.closing_date) > YEAR('$search_date')
                        OR (
                            YEAR(cc.closing_date) = YEAR('$search_date')
                            AND MONTH(cc.closing_date) >= MONTH('$search_date')
                        )
                    )
                )
                OR (ii.req_id IN ($odReqIdStr))
                OR (ii.req_id IN ($DueNilReqIdStr))
            )
            AND DATE(ii.updated_date) < '$toDate_month_start';
        ");
    $customers = $custQry->fetchAll(PDO::FETCH_ASSOC);
    if (empty($customers)) continue;

    $req_ids = array_column($customers, 'req_id');

    $coll_DueNilReqIds = [];
    $coll_DueNilQuery = $connect->query("SELECT DISTINCT cs5.req_id
    FROM customer_status cs5
    JOIN (
        SELECT c.req_id, MIN(c.coll_date) AS first_coll_date
        FROM collection c
        WHERE MONTH(c.coll_date) = MONTH('$search_date')
        AND YEAR(c.coll_date) = YEAR('$search_date')
        GROUP BY c.req_id
    ) first_col ON cs5.req_id = first_col.req_id
    JOIN collection col
        ON col.req_id = first_col.req_id
        AND col.coll_date = first_col.first_coll_date
    WHERE cs5.sub_status = 'Closed'
    AND col.coll_sub_status IN ('Due Nil');");

    while ($row = $coll_DueNilQuery->fetch(PDO::FETCH_ASSOC)) {
        $coll_DueNilReqIds[] = $row['req_id'];
    }

    $Due_Nil_reqIDS = [];
    $Due_Nil_query = $connect->query("SELECT c.req_id
    FROM
        collection c
    JOIN customer_status cs
        ON cs.req_id = c.req_id
    WHERE
        c.coll_sub_status = 'Due Nil'
        AND DATE(c.coll_date) > '$search_date'
        AND cs.sub_status = 'Closed'
        -- :lock: Ensure Due Nil is FIRST entry of that month
        AND NOT EXISTS (
            SELECT 1
            FROM collection x
            WHERE
                x.req_id = c.req_id
                AND YEAR(x.coll_date) = YEAR(c.coll_date)
                AND MONTH(x.coll_date) = MONTH(c.coll_date)
                AND x.coll_date < c.coll_date
        )
        AND (
            -- Case 1: NO collection in search month
            NOT EXISTS (
                SELECT 1
                FROM collection s
                WHERE
                    s.req_id = c.req_id
                    AND YEAR(s.coll_date) = YEAR('$search_date')
                    AND MONTH(s.coll_date) = MONTH('$search_date')
            )
            OR
            -- Case 2: NO collection in search & previous month
            (
                NOT EXISTS (
                SELECT 1
                FROM collection s2
                WHERE
                    s2.req_id = c.req_id
                    AND YEAR(s2.coll_date) = YEAR('$search_date')
                    AND MONTH(s2.coll_date) = MONTH('$search_date')
            )
            AND NOT EXISTS (
                SELECT 1
                FROM collection p
                WHERE
                    p.req_id = c.req_id
                    AND YEAR(p.coll_date) = YEAR(DATE_SUB('$search_date', INTERVAL 1 MONTH))
                    AND MONTH(p.coll_date) = MONTH(DATE_SUB('$search_date', INTERVAL 1 MONTH))
            )
        )
    );");

    while ($row = $Due_Nil_query->fetch(PDO::FETCH_ASSOC)) {
        $Due_Nil_reqIDS[] = $row['req_id'];
    }

    $colls_DueNilReqIds = array_merge($coll_DueNilReqIds, $Due_Nil_reqIDS);

    $filtered_ids = array_diff($req_ids, $colls_DueNilReqIds);
    $id_list = !empty($filtered_ids) ? implode(',', $filtered_ids) : '';

    $collectionData = [];

    if (!empty($id_list)) {
        $colQry = $connect->query("SELECT req_id, coll_date, payable_amt,due_amt_track, total_paid_track,pending_amt,coll_sub_status
            FROM collection
            WHERE req_id IN ($id_list) AND DATE(coll_date) <= '$search_date'
            ORDER BY req_id, coll_date ASC
        ");

        while ($col = $colQry->fetch(PDO::FETCH_ASSOC)) {
            $collectionData[$col['req_id']][] = $col;
        }

        $paidSummary = [];
        $paidQry = $connect->query("SELECT c.req_id, 
        SUM(c.due_amt_track) AS total_paid, 
        MIN(c.due_amt) AS monthly_due, 
        MIN(a.due_start_from) AS due_start_from, 
        MAX(c.coll_date) AS last_paid_date,
        COUNT(DISTINCT EXTRACT(YEAR_MONTH FROM c.coll_date)) AS paid_month_count,
        COALESCE(SUM(CASE WHEN c.coll_date < DATE_FORMAT('$search_date', '%Y-%m-01') 
        THEN c.due_amt_track ELSE 0 END), 0) AS till_last_month_paid
        FROM collection c
        JOIN acknowlegement_loan_calculation a ON c.req_id = a.req_id
        WHERE DATE(c.coll_date) <= '$search_date'
        AND c.req_id IN ($id_list)
        GROUP BY c.req_id;");

        while ($row = $paidQry->fetch()) {
            $start = new DateTime($row['due_start_from']);
            $end = new DateTime($search_date);
            $months = ($end->format('Y') - $start->format('Y')) * 12 + ($end->format('m') - $start->format('m')) + 1;

            $paidSummary[$row['req_id']] = [
                'total_paid'            => (float)$row['total_paid'],
                'expected_due'          => (float)($months * $row['monthly_due']),
                'previous_due'          => (float)(($months - 1) * $row['monthly_due']),
                'last_paid_date'        => $row['last_paid_date'],
                'till_last_month_paid'  => $row['till_last_month_paid'],
                'paid_month_count'      => $row['paid_month_count'],
                'monthly_due'           => (float)$row['monthly_due'],
                'due_start_from'        => $row['due_start_from'],
                'future_due'            => (float)(($months + 1) * $row['monthly_due']),
            ];
        }
    }

    // Step 4: Decide grouping
    $groups = [];
    foreach ($customers as $cust) {
        if ($type == 1 || $type == 3 || $type == 4) {
            $groups[$cust['map_name']][] = $cust;
        } else { // type 2
            $groups[$userName][] = $cust;
        }
    }

    // Step 5: Process each group
    foreach ($groups as $groupName => $custList) {
        $total_count = $t_current_count = $responsible_zero = $balance_count = $paid = $partially_paid = $totals_paid = $paid_percentage = $unpaid_percentage = $unpaid = $payable_zero = $from_pending = 0;

        foreach ($custList as $cust) {

            if (!in_array($cust['req_id'], $filtered_ids)) {
                continue;
            }

            $total_count++;
            // echo $cust['loan_id'] . "<br>";
            $due_start = $cust['due_start_from'];
            // Count responsible = 0
            if ($cust['responsible'] == '0') {
                $responsible_zero++;
            }

            $collList   = $collectionData[$cust['req_id']] ?? [];
            $end        = strtotime(min($cust['maturity_date'], $search_date));
            $start      = strtotime($cust['due_start_from']);

            $months = (date('Y', $end) - date('Y', $start)) * 12 +
                (date('m', $end) - date('m', $start)) + 1;

            $pending_month              = max(0, $months - 1);
            $start_month                = strtotime(date('Y-m-01', strtotime($search_date))); // 1st of the month as timestamp
            $collectedTillMonthStart    = 0;

            $searchMonth        = date('Y-m', strtotime($search_date));
            $lastMonthStatus    = null; // default null
            $lastCollDate       = null;    // track latest record in month

            foreach ($collList as $coll) {
                $collDate = strtotime($coll['coll_date']);

                // Collections before this month
                if ($collDate < $start_month) {
                    $collectedTillMonthStart += (int)$coll['due_amt_track'];
                }

                // Find last record in the search month
                if (date('Y-m', $collDate) === $searchMonth) {
                    if ($lastCollDate === null || $collDate > $lastCollDate) {
                        $lastCollDate       = $collDate;
                        $lastMonthStatus    = $coll['coll_sub_status'];
                    }
                }
            }

            $payable_amount = ($months * $cust['due_amt_cal']) - $collectedTillMonthStart;

            $pending_amount_atMonthStart = ($pending_month * $cust['due_amt_cal']) - $collectedTillMonthStart;

            $isCurrentCustomer = false;
            $isBalanceCustomer = false;

            $iscurrentMonthStart = $payable_amount  <= $cust['due_amt_cal'] && $pending_amount_atMonthStart <= 0 &&
                (
                    (
                        ($cust['due_method_scheme'] === '1' || $cust['due_method_calc'] === 'Monthly')
                        && date('Y-m', strtotime($cust['maturity_date'])) >= date('Y-m', strtotime($toDate_month_start))
                    )
                    ||
                    (
                        ($cust['due_method_scheme'] != '1' || $cust['due_method_calc'] != 'Monthly')
                        && strtotime($cust['maturity_date']) > strtotime($toDate_month_start)
                    )
                );

            if ($iscurrentMonthStart) {
                $t_current_count++;  // fixed for the whole month
                // echo $cust['loan_id'] . "<br>";
                $isCurrentCustomer = true; // flag

                // Balance count check
                if ($payable_amount > 0 && $cust['responsible'] != '0') {
                    $balance_count++;
                    $isBalanceCustomer = true;
                }
            }

            // ===== Payable Zero (current customer) =====
            if ($isCurrentCustomer && $payable_amount <= 0 && $cust['responsible'] != '0') {
                $payable_zero++;
            }

            $isPendingMonthStart = $pending_amount_atMonthStart > 0 &&
                (
                    (
                        ($cust['due_method_scheme'] === '1' || $cust['due_method_calc'] === 'Monthly')
                        && date('Y-m', strtotime($cust['maturity_date'])) >= date('Y-m', strtotime($toDate_month_start))
                    )
                    ||
                    (
                        ($cust['due_method_scheme'] != '1' || $cust['due_method_calc'] != 'Monthly')
                        && strtotime($cust['maturity_date']) > strtotime($toDate_month_start)
                    )
                );

            if ($isBalanceCustomer) {
                $rid = $cust['req_id'];
                if (isset($paidSummary[$rid])) {
                    $p = $paidSummary[$rid];
                    $expected_months = monthDiff($p['due_start_from'], $search_date);

                    switch (true) {
                        case ($p['total_paid'] >= $p['expected_due']
                            && date('Y-m', strtotime($p['last_paid_date'])) == date('Y-m', $start_month)):
                            $paid++;
                            break;

                        case ($p['total_paid'] > 0 && $p['total_paid'] < $p['expected_due']
                            && date('Y-m', strtotime($p['last_paid_date'])) == date('Y-m', $start_month)):
                            $partially_paid++;
                            break;

                        case ($p['total_paid'] == 0):
                        default:
                            $unpaid++;
                            break;
                    }
                } else {
                    $unpaid++;
                }
            }

            $totals_paid       = $paid + $partially_paid;
            $paid_percentage   = $balance_count > 0 ? round(($totals_paid / $balance_count) * 100, 1) : 0.0;
            $unpaid_percentage = $balance_count > 0 ? round(($unpaid / $balance_count) * 100, 1) : 0.0;

            // ===== From Pending =====
            $isPendingCustomer = false; // flag
            if ($isPendingMonthStart) {
                $isPendingCustomer = true; // flag
            }

            // Now use it in your pending logic
            if ($isPendingCustomer) {
                $customer_cleared = false;
                $pay_amnt     = $paidSummary[$cust['req_id']]['expected_due'] ?? null;
                $previous_due = $paidSummary[$cust['req_id']]['previous_due'] ?? null;
                $total_paid   = $paidSummary[$cust['req_id']]['total_paid'] ?? null;

                // Pending cleared within the month
                if ($total_paid >= $previous_due && $total_paid < $pay_amnt && $lastMonthStatus != 'OD') {
                    $customer_cleared = true;
                }

                if ($customer_cleared) {
                    $from_pending++;
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
            'sno'               => $sno++,
            'date'              => date('d-m-Y', strtotime($search_date)),
            'fullname'          => $display_name,
            'loan_category'     => $loan_category_map[$cat_id] ?? $cat_id,
            'total_count'       => $total_count,
            't_current_count'   => $t_current_count,
            'payable_zero'      => $payable_zero,
            'responsible_zero'  => $responsible_zero,
            'balance_count'     => $balance_count,
            'paid'              => $paid,
            'partially_paid'    => $partially_paid,
            'totals_paid'       => $paid + $partially_paid,
            'paid_percentage'   => ($balance_count > 0) ? round(($totals_paid / $balance_count) * 100, 1) : '0.0',
            'unpaid'            => $unpaid,
            'unpaid_percentage' => ($balance_count > 0) ? round(($unpaid / $balance_count) * 100, 1) : '0.0',
            'from_pending'      => $from_pending,
        ];
    }
}

$grand_total = [
    'sno'               => '',
    'date'              => '',
    'fullname'          => 'Total',
    'loan_category'     => '',
    'total_count'       => 0,
    't_current_count'   => 0,
    'payable_zero'      => 0,
    'responsible_zero'  => 0,
    'balance_count'     => 0,
    'paid'              => 0,
    'partially_paid'    => 0,
    'totals_paid'       => 0,
    'paid_percentage'   => 0,
    'unpaid'            => 0,
    'unpaid_percentage' => 0,
    'from_pending'      => 0,
];

foreach ($data as $row) {
    $grand_total['total_count']        += $row['total_count'];
    $grand_total['t_current_count']    += $row['t_current_count'];
    $grand_total['payable_zero']       += $row['payable_zero'];
    $grand_total['responsible_zero']   += $row['responsible_zero'];
    $grand_total['balance_count']      += $row['balance_count'];
    $grand_total['paid']               += $row['paid'];
    $grand_total['partially_paid']     += $row['partially_paid'];
    $grand_total['unpaid']             += $row['unpaid'];
    $grand_total['totals_paid']        += $row['totals_paid'];
    $grand_total['paid_percentage']    += $row['paid_percentage'];
    $grand_total['unpaid_percentage']  += $row['unpaid_percentage'];
    $grand_total['from_pending']       += $row['from_pending'];
}

// Append totals to the end
$data[] = $grand_total;

echo json_encode(["data" => $data]);
