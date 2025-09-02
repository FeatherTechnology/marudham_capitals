
<?php
include '../../ajaxconfig.php';

$search_date = $_POST['search_date'];
$type            = $_POST['type'];
$line            = isset($_POST['line']) ? $_POST['line'] : '';
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$sub_status_type = $_POST['sub_status_type'];
$loan_category = $_POST['loan_category'];
$group_map            = isset($_POST['group_map']) ? $_POST['group_map'] : '';
$due_followup            = isset($_POST['due_followup']) ? $_POST['due_followup'] : '';
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
                OR (
                    cs.sub_status = 'Closed'
                    AND cc.closing_date IS NOT NULL
                    AND MONTH(cc.closing_date) = MONTH('$search_date')
                    AND YEAR(cc.closing_date) = YEAR('$search_date')
                )  OR (ii.req_id IN ($odReqIdStr))
        OR (ii.req_id IN ($DueNilReqIdStr)) 
          )
          AND DATE(ii.updated_date) < '$toDate_month_start'
    ");
    $customers = $custQry->fetchAll(PDO::FETCH_ASSOC);
    if (empty($customers)) continue;
    // Step 2: Get collection info for those req_ids
    $req_ids = array_column($customers, 'req_id');

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
    }

    // Step 3: Counters
    $odReqIds = [];
    $odNilQuery = $connect->query("
    SELECT DISTINCT cs2.req_id 
    FROM customer_status cs2
    JOIN collection col ON cs2.req_id = col.req_id
    WHERE cs2.sub_status IN ('Closed','Due Nil')
      AND col.coll_sub_status = 'OD'
      AND MONTH(col.coll_date) = MONTH('$search_date')
      AND YEAR(col.coll_date) = YEAR('$search_date')
");
    while ($row = $odNilQuery->fetch(PDO::FETCH_ASSOC)) {
        $odReqIds[$row['req_id']] = true; // use hash for O(1) lookup
    }
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
    $coll_DueNilReqIdStr = !empty($coll_DueNilReqIds) ? implode(',', $coll_DueNilReqIds) : 'NULL';
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
        $total_count = $t_od_count = $today_od_clear = $t_od_clear = $partially_paid = $unpaid = 0;
        foreach ($custList as $cust) {
            $total_count++;
            $coll = $collectionMap[$cust['req_id']] ?? null;
            $isODCustomer = false; // flag
            // Increment t_OD_count anytime cust sub_status is OD (outside switch)
            if ($cust['sub_status'] == 'OD' || isset($odReqIds[$cust['req_id']])) {
                $t_od_count++;
                $isODCustomer = true; // flag
                // echo $cust['loan_id'] . "<br>";
            }

       if (in_array($cust['req_id'], $coll_DueNilReqIds)) {
                $total_count--;
            }

            if ($isODCustomer) {
                if (
                    $coll &&
                    $coll['coll_sub_status'] == 'OD' &&
                    in_array($cust['sub_status'], ['Closed', 'Due Nil'])
                ) {
                    $collDate = date('Y-m-d', strtotime($coll['coll_date']));

                    // Case 1: Today pending clear
                    if ($collDate == $search_date) {
                        $today_od_clear++;
                    }

                    // Case 2: This month pending clear (includes today)
                    if ($collDate >= $toDate_month_start && $collDate <= $search_date) {
                        $t_od_clear++;
                        //  echo $cust['loan_id'] . "<br>";
                    }
                } elseif (
                    $coll &&
                    $cust['sub_status'] == 'OD' &&
                    date('Y-m-d', strtotime($coll['coll_date'])) >= $toDate_month_start &&
                    date('Y-m-d', strtotime($coll['coll_date'])) <= $search_date
                ) {

                    // Case 3: Partially paid
                    $partially_paid++;
                    //  echo $cust['loan_id'] . "<br>";
                }
                if (
                    !$coll ||
                    date('Y-m-d', strtotime($coll['coll_date'])) < $toDate_month_start ||
                    date('Y-m-d', strtotime($coll['coll_date'])) > $search_date
                ) {
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
            't_od_count' => $t_od_count,
            'today_od_clear' => $today_od_clear,
            't_od_clear' => $t_od_clear,
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
    't_od_count' => 0,
    'today_od_clear' => 0,
    't_od_clear' => 0,
    'partially_paid' => 0,
    'unpaid' => 0
];

foreach ($data as $row) {
    $grand_total['total_count']       += $row['total_count'];
    $grand_total['t_od_count']   += $row['t_od_count'];
    $grand_total['today_od_clear'] += $row['today_od_clear'];
    $grand_total['t_od_clear']   += $row['t_od_clear'];
    $grand_total['partially_paid']    += $row['partially_paid'];
    $grand_total['unpaid']            += $row['unpaid'];
}

// Append totals to the end
$data[] = $grand_total;

echo json_encode(["data" => $data]);
