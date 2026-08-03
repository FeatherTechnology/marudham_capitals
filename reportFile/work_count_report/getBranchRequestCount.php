<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$branch_id   = $_POST['branch_id'] ?? '0';
$type   = $_POST['type'];

$branchCondition = '';
if($branch_id !='0'){
    if($type == '2'){ //branch
        $branchCondition = " AND bc.branch_id = '$branch_id'";
    } else if($type == '3'){ //group
        $branchCondition = " AND agm.map_id = '$branch_id'";
    }
}

/* ===================== USER FILTER ===================== */

$stmt = $connect->prepare("
        SELECT DISTINCT insert_login_id 
        FROM request_creation 
        WHERE status =0
    ");
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);


if (empty($userIds)) {
    echo json_encode(["data" => []]);
    exit;
}

/* ===================== USER MAP + LOAN CATS ===================== */

$placeholders = str_repeat('?,', count($userIds) - 1) . '?';

$stmt = $connect->prepare("
    SELECT user_id, fullname 
    FROM user 
    WHERE user_id IN ($placeholders) order by fullname asc
");
$stmt->execute($userIds);
$userMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name 
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   PRELOAD CUSTOMER HISTORY (Exact Model Match)
===================== */



$historyStmt = $connect->query("SELECT req.cus_id,req.req_id,req.cus_status,csd.created_date AS closing_date,cc.closing_date AS closed_date,req.dor,cs1.sub_status,dn.due_nil_date
FROM request_creation req
LEFT JOIN closed_status csd ON csd.req_id = req.req_id
LEFT JOIN closing_customer cc ON cc.req_id = req.req_id
LEFT JOIN customer_status cs1 ON cs1.req_id = req.req_id
LEFT JOIN (SELECT
        req_id,MAX(coll_date) AS due_nil_date
    FROM collection
    WHERE coll_sub_status = 'Due Nil' GROUP BY req_id) dn ON dn.req_id = req.req_id
WHERE req.cus_status NOT IN (4,5,6,7,8,9) GROUP BY req.req_id;
");
$historyData = [];
while ($row = $historyStmt->fetch(PDO::FETCH_ASSOC)) {
    $historyData[$row['cus_id']][] = $row;
}
/* =====================
   FAST CUSTOMER TYPE (Exact Logic)
===================== */

function getCustomerTypeFast($cus_data, $reqDate, $cus_id, $req_id, $historyData)
{
    if (strtolower($cus_data) == 'new') {
        return 'new';
    }

    if (empty($historyData[$cus_id])) {
        return 'existing_new';
    }

    // Find latest previous loan
    $latestIssue = null;
    foreach ($historyData[$cus_id] as $issue) {
        // Skip current request
        if ($issue['req_id'] == $req_id) {
            continue;
        }
        // Skip future requests
        if ($issue['req_id'] > $req_id) {
            continue;
        }
        // Get latest previous request
        if ($latestIssue == null || $issue['req_id'] > $latestIssue['req_id']) {
            $latestIssue = $issue;
        }
    }
    if (!$latestIssue) {
        return 'existing_new';
    }
    $dor = date('Y-m-d', strtotime($reqDate));

    $closingDate = '';
    if (!empty($latestIssue['closing_date'])) {
        $closingDate = date('Y-m-d', strtotime($latestIssue['closing_date']));
    }
    $closedDate = '';
    if (!empty($latestIssue['closed_date'])) {
        $closedDate = date('Y-m-d', strtotime($latestIssue['closed_date']));
    }
    $dueNilDate = !empty($latestIssue['due_nil_date']) ? date('Y-m-d', strtotime($latestIssue['due_nil_date'])) : '';

    //    1. DUE NIL -> REVIVAL and CURRENT REQUEST <= DUE NIL DATE -> REVIVAL
    if ($latestIssue['sub_status'] == 'Due Nil' || (!empty($dueNilDate) &&
    $dor <= $dueNilDate)) {
        return 'reloan';
    }
  
    //    2. PREVIOUS LOAN CLOSED  Current Request > Closing Date
    if ((!empty($closingDate) && $dor >= $closedDate && $dor <= $closingDate) || $latestIssue['cus_status'] == 20) {
        return 'reloan';
    }

    //    3. ISSUED LOAN (14-19)
    if ($latestIssue['cus_status'] >= 14 && $latestIssue['cus_status'] < 20) {
        return 'additional';
    }
  //4.Current Request <= Closing Date
    if (!empty($closedDate) && $dor < $closedDate) {
        return 'additional';
    }
    //  5. NO CLOSING DATE
    if (empty($closingDate)) {
        return 'existing_new';
    }
    //    6. RENEWAL / RE-ACTIVE
    $monthEnd = date('Y-m-t', strtotime($closingDate));
    $nextMonth = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
    $reactiveDate = date('Y-m-d', strtotime($nextMonth . ' +6 months'));
    if ($reactiveDate > $dor) {
        return 'renewal';
    }
    return 'reactive';
} 


/* =====================
   COUNTERS
===================== */

function emptyTypeCounter() {
    return ['new' => 0, 'renewal' => 0, 'reactive' => 0, 'additional' => 0, 'existing_new' => 0,'reloan'=>0, 'total' => 0];
}

function emptyStatusCounter() {
    return ['current' => 0, 'pending' => 0, 'od' => 0, 'error' => 0, 'legal' => 0, 'total' => 0];
}

/* =====================
   FETCH RECORDS
===================== */

$prevQuery = "
    SELECT req.req_id, req.cus_id, req.cus_data, req.cus_status,
           req.created_date, req.updated_date,
           ii.updated_date AS issue_date, req.loan_category, req.insert_login_id, cs.sub_status
    FROM request_creation req
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = req.sub_area
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    LEFT JOIN in_issue ii ON ii.req_id = req.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    WHERE req.insert_login_id IN ($placeholders) 
    AND DATE(req.created_date) < ?
    AND NOT (
        req.cus_status IN (4,5,6,7,8,9)
        AND DATE(req.updated_date) < ?
    )
    AND NOT (
        ii.updated_date IS NOT NULL
        AND DATE(ii.updated_date) < ?
    ) $branchCondition
";

$stmt = $connect->prepare($prevQuery);
$stmt->execute(array_merge($userIds, [$from_date, $from_date, $from_date]));
$previousRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentQuery = "
    SELECT req.req_id, req.cus_id, req.cus_data, req.cus_status,
           req.created_date, req.updated_date, ii.updated_date AS issue_date,
           req.loan_category, req.insert_login_id, cs.sub_status
    FROM request_creation req
    LEFT JOIN in_issue ii ON ii.req_id = req.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = req.sub_area
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    WHERE req.insert_login_id IN ($placeholders) 
    AND DATE(req.created_date) BETWEEN ? AND ? $branchCondition
";
// echo $currentQuery;die;
$stmt = $connect->prepare($currentQuery);

$stmt->execute(array_merge($userIds, [$from_date, $to_date]));
$currentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by USER + CATEGORY
$prevByUserCat = [];
foreach ($previousRecords as $r) {
    $prevByUserCat[$r['insert_login_id']][$r['loan_category']][] = $r;
}

$currentByUserCat = [];
foreach ($currentRecords as $r) {
    $currentByUserCat[$r['insert_login_id']][$r['loan_category']][] = $r;
}

/* =====================
   PROCESS DATA (OPTIMIZED)
===================== */

$data = [];
$sno = 1;

foreach ($userMap as $userId => $userName) {
    foreach ($loanCats as $cat) {
        $cat_id = $cat['loan_category_creation_id'];
        $cat_name = $cat['loan_category_creation_name'];

        if (empty($prevByUserCat[$userId][$cat_id] ?? []) && 
            empty($currentByUserCat[$userId][$cat_id] ?? [])) {
            continue;
        }

        $counters = [
            'previous' => emptyTypeCounter(),
            'request'  => emptyTypeCounter(),
            'cancel'   => emptyTypeCounter(),
            'revoke'   => emptyTypeCounter(),
            'process'  => emptyTypeCounter(),
            'issued'   => emptyTypeCounter(),
            'status'   => emptyStatusCounter()
        ];

        // Process previous records
        foreach ($prevByUserCat[$userId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'previous', $from_date, $to_date, $historyData);
        }

        // Process current records  
        foreach ($currentByUserCat[$userId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'request', $from_date, $to_date, $historyData);
        }

        $data[] = [
            "sno" => $sno++,
            "fullname" => $userName,
            "loan_category" => $cat_name,
            "previous" => $counters['previous'],
            "request"  => $counters['request'],
            "cancel"   => $counters['cancel'],
            "revoke"   => $counters['revoke'],
            "process"  => $counters['process'],
            "issued"   => $counters['issued'],
            "status"   => $counters['status']
        ];
    }
}

/* =====================
   PROCESS RECORD FUNCTION (DRY)
===================== */

function processRecord($r, &$counters, $baseCounter, $from_date, $to_date, $historyData) {
    $type = getCustomerTypeFast($r['cus_data'], $r['created_date'], $r['cus_id'], $r['req_id'], $historyData);
    $status_date = !empty($r['updated_date']) ? date('Y-m-d', strtotime($r['updated_date'])) : '';
    $issue_date = !empty($r['issue_date']) ? date('Y-m-d', strtotime($r['issue_date'])) : '';
    $req_status = $r['cus_status'];

    // Base counter
    $counters[$baseCounter][$type]++;
    $counters[$baseCounter]['total']++;

    // Status checks
    if ($status_date >= $from_date && $status_date <= $to_date && in_array($req_status, [4,5,6,7])) {
        $counters['cancel'][$type]++;
        $counters['cancel']['total']++;
    } elseif ($status_date >= $from_date && $status_date <= $to_date && in_array($req_status, [8,9])) {
        $counters['revoke'][$type]++;
        $counters['revoke']['total']++;
    } elseif ($issue_date >= $from_date && $issue_date <= $to_date && !empty($r['sub_status'])) {
        $counters['issued'][$type]++;
        $counters['issued']['total']++;
        
        // Status breakdown FIXED
        $sub = strtolower($r['sub_status']);
        if (isset($counters['status'][$sub])) {
            $counters['status'][$sub]++;
            $counters['status']['total']++;
        }
        
    } elseif ($baseCounter === 'request' || $baseCounter === 'previous') {
        $counters['process'][$type]++;
        $counters['process']['total']++;
    }
}

/* =====================
   TOTALS
===================== */

$totals = [
    'previous' => emptyTypeCounter(),
    'request' => emptyTypeCounter(),
    'cancel' => emptyTypeCounter(),
    'revoke' => emptyTypeCounter(),
    'process' => emptyTypeCounter(),
    'issued' => emptyTypeCounter(),
    'status' => emptyStatusCounter()
];

foreach ($data as $row) {
    foreach ($totals as $key => &$totalCounter) {
        foreach ($totalCounter as $type => $v) {
            $totalCounter[$type] += $row[$key][$type] ?? 0;
        }
    }
}

$data[] = [
    "sno" => "",
    "fullname" => 'all',
    "loan_category" => "",
    "previous" => $totals['previous'],
    "request" => $totals['request'],
    "cancel" => $totals['cancel'],
    "revoke" => $totals['revoke'],
    "process" => $totals['process'],
    "issued" => $totals['issued'],
    "status" => $totals['status']
];

echo json_encode(["data" => $data]);
?>