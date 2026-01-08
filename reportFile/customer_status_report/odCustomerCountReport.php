
<?php
include '../../ajaxconfig.php';

$search_date        = $_POST['search_date'];
$type               = $_POST['type'];
$line               = isset($_POST['line']) ? $_POST['line'] : '';
$user_id            = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$sub_status_type    = $_POST['sub_status_type'];
$loan_category      = $_POST['loan_category'];
$group_map          = isset($_POST['group_map']) ? $_POST['group_map'] : '';
$due_followup       = isset($_POST['due_followup']) ? $_POST['due_followup'] : '';
$toDate_month_start = date('Y-m-01', strtotime($search_date));

if (!is_array($loan_category)) {
    $loan_category = [$loan_category];
}

if (!is_array($user_id)) {
    $user_id = explode(',', $user_id); // convert CSV to array
}
$user_id = array_unique(array_map('intval', $user_id)); // remove duplicates & ensure integers

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
    $line_id_str    = implode(',', $line_ids);
    $condition      = "alm.map_id IN ($line_id_str)";
    $joinTable      = "JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)";
    $userName       = implode(', ', array_unique($display_names));
    $nameField      = "NULL";
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
    AND col.coll_sub_status IN ('Current','Due Nil','Pending','OD') 
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
        alc.due_amt_cal,
        alc.due_period,
        alc.tot_amt_cal,
        alc.sub_category,
        alc.due_start_from,
        alc.due_method_scheme,
        alc.due_method_calc,
        alc.maturity_month as maturity_date,
        $nameField as map_name
        FROM in_issue ii
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
                OR  (
                cs.sub_status = 'Closed'
                AND cc.closing_date IS NOT NULL
                AND (
                    YEAR(cc.closing_date) > YEAR('$search_date')
                    OR (
                        YEAR(cc.closing_date) = YEAR('$search_date')
                        AND MONTH(cc.closing_date) >= MONTH('$search_date')
                    )
                )
                )  OR (ii.req_id IN ($odReqIdStr))
            OR (ii.req_id IN ($DueNilReqIdStr)) 
            )
            AND DATE(ii.updated_date) < '$toDate_month_start'; 
    ");

    $customers = $custQry->fetchAll(PDO::FETCH_ASSOC);
    if (empty($customers)) continue;
    // Step 2: Get collection info for those req_ids
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
    AND col.coll_sub_status IN ('Due Nil');
    ");

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
        $colQry = $connect->query("SELECT req_id, coll_date, payable_amt, penalty,coll_charge,due_amt_track, total_paid_track
            FROM collection
            WHERE req_id IN ($id_list)
            AND DATE(coll_date) <= '$search_date'
            ORDER BY req_id, coll_date ASC
        ");
        while ($col = $colQry->fetch(PDO::FETCH_ASSOC)) {
            $collectionData[$col['req_id']][] = $col;
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
        $total_count = $t_od_count = $today_od_clear = $t_od_clear = $partially_paid = $total_paid_od = $paid_percentage = $unpaid_percentage = $unpaid = 0;
        foreach ($custList as $cust) {
            if (!in_array($cust['req_id'], $filtered_ids)) {
                continue;
            }
            $total_count++;

            $collList = $collectionData[$cust['req_id']] ?? [];
            $start_month = strtotime(date('Y-m-01', strtotime($search_date))); // 1st of the month as timestamp
            $collectedTillMonthStart = 0;

            foreach ($collList as $coll) {
                $collDate = strtotime($coll['coll_date']);
                if ($collDate < $start_month) {  // strictly before 1st Aug
                    $collectedTillMonthStart += (int)$coll['due_amt_track']; // cast to int
                }
            }
            $od_amount_atMonthStart = ($cust['tot_amt_cal']) - $collectedTillMonthStart;

            $isOdMonthStart =
                intval($od_amount_atMonthStart) > 0 &&
                $cust['sub_status'] != 'Error' &&
                $cust['sub_status'] != 'Legal' &&
                (
                    (
                        ($cust['due_method_scheme'] === '1' || $cust['due_method_calc'] === 'Monthly')
                        && date('Y-m', strtotime($cust['maturity_date'])) < date('Y-m', strtotime($toDate_month_start))
                    )
                    ||
                    (
                        ($cust['due_method_scheme'] != '1' && $cust['due_method_calc'] != 'Monthly')
                        && strtotime($cust['maturity_date']) < strtotime($toDate_month_start)
                    )
                );

            $isODCustomer = false; // flag
            if ($isOdMonthStart) {
                $t_od_count++;  // fixed for the whole month
                $isODCustomer = true; // flag
            }

            if ($isODCustomer) {
                $customer_cleared = false;
                $customer_partial = false;
                $hadCollectionThisMonth = false;

                $searchMonth = date('m', strtotime($search_date));
                $searchYear  = date('Y', strtotime($search_date));

                foreach ($collList as $coll) {
                    $coll_date = strtotime($coll['coll_date']);
                    if (date('m', $coll_date) == $searchMonth && date('Y', $coll_date) == $searchYear) {
                        $hadCollectionThisMonth = true;

                        $payable_amnts   = (int)$coll['payable_amt'];
                        $due_amt_track = (int)$coll['due_amt_track'];

                        // Today pending clear
                        if ($due_amt_track >= $payable_amnts && date('Y-m-d', $coll_date) == $search_date) {
                            $today_od_clear++;
                            $customer_cleared = true;
                        }

                        // Pending cleared within the month
                        if ($due_amt_track >= $payable_amnts) {
                            $customer_cleared = true;
                        }
                        // Partial
                        elseif ($payable_amnts > 0 && $due_amt_track < $payable_amnts) {
                            $customer_partial = true;
                        }
                    }
                }

                // Decide final classification once per customer
                if ($customer_cleared) {
                    $t_od_clear++;
                } elseif ($customer_partial) {
                    $partially_paid++;
                } elseif (!$hadCollectionThisMonth) {
                    // No collection in this month at all
                    $unpaid++;
                } else {
                    // Had collection but still unpaid
                    $unpaid++;
                }
            }
        }

        $display_name       = ($type == 2) ? $userName : $groupName;
        $total_paid_od      = $t_od_clear + $partially_paid;
        $paid_percentage    = ($t_od_count > 0) ? number_format(round(($total_paid_od / $t_od_count) * 100, 1), 1) : '0.0';
        $unpaid_percentage  = ($t_od_count > 0) ? number_format(round(($unpaid / $t_od_count) * 100, 1), 1) : '0.0';

        $data[] = [
            'sno'               => $sno++,
            'date'              => date('d-m-Y', strtotime($search_date)),
            'fullname'          => $display_name,
            'loan_category'     => $loan_category_map[$cat_id] ?? $cat_id,
            'total_count'       => $total_count,
            't_od_count'        => $t_od_count,
            'today_od_clear'    => $today_od_clear,
            't_od_clear'        => $t_od_clear,
            'partially_paid'    => $partially_paid,
            'total_paid_od'     => $total_paid_od,
            'paid_percentage'   => ($t_od_count > 0) ? number_format(round(($total_paid_od / $t_od_count) * 100, 1), 1) : '0.0',
            'unpaid'            => $unpaid,
            'unpaid_percentage' => ($t_od_count > 0) ? number_format(round(($unpaid / $t_od_count) * 100, 1), 1) : '0.0',
        ];
    }
}

$grand_total = [
    'sno'               => '',
    'date'              => '',
    'fullname'          => 'Total',
    'loan_category'     => '',
    'total_count'       => 0,
    't_od_count'        => 0,
    'today_od_clear'    => 0,
    't_od_clear'        => 0,
    'partially_paid'    => 0,
    'total_paid_od'     => 0,
    'paid_percentage'   => 0,
    'unpaid'            => 0,
    'unpaid_percentage' => 0,
];

foreach ($data as $row) {
    $grand_total['total_count']       += $row['total_count'];
    $grand_total['t_od_count']        += $row['t_od_count'];
    $grand_total['today_od_clear']    += $row['today_od_clear'];
    $grand_total['t_od_clear']        += $row['t_od_clear'];
    $grand_total['partially_paid']    += $row['partially_paid'];
    $grand_total['total_paid_od']     += $row['total_paid_od'];
    $grand_total['paid_percentage']   += $row['paid_percentage'];
    $grand_total['unpaid']            += $row['unpaid'];
    $grand_total['unpaid_percentage'] += $row['unpaid_percentage'];
}

// Append totals to the end
$data[] = $grand_total;

echo json_encode(["data" => $data]);
