<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];

/* =====================
   USER FILTER (from in_approval)
===================== */

if ($user_id != 'all') {
    if (!is_array($user_id)) {
        $user_id = explode(',', $user_id);
    }
    $userIds = array_map('intval', $user_id);
} else {
    $stmt = $connect->prepare("
        SELECT DISTINCT insert_login_id 
        FROM in_approval 
        WHERE insert_login_id != ''
    ");
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if (empty($userIds)) {
    echo json_encode(["data" => []]);
    exit;
}

/* =====================
   SETUP
===================== */

$placeholders = str_repeat('?,', count($userIds) - 1) . '?';

// User map
$stmt = $connect->prepare("
    SELECT user_id, fullname 
    FROM user 
    WHERE user_id IN ($placeholders) ORDER BY fullname ASC
");
$stmt->execute($userIds);
$userMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Loan categories
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name 
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   HELPER FUNCTIONS
===================== */

function emptyTypeCounter() {
    return ['new' => 0, 'renewal' => 0, 'reactive' => 0, 'additional' => 0, 'existing_new' => 0, 'total' => 0];
}

function emptyStatusCounter() {
    return ['current' => 0, 'pending' => 0, 'od' => 0, 'error' => 0, 'legal' => 0, 'total' => 0];
}

function getCustomerType($cus_type, $cus_exist_type) {
    if (strtolower($cus_type) == 'new') return 'new';
    
    $existType = strtolower(trim($cus_exist_type));
    $existType = str_replace(['re-active', 'existing-new'], ['reactive', 'existing_new'], $existType);
    
    return in_array($existType, ['additional', 'renewal', 'reactive', 'existing_new']) 
        ? $existType : 'existing_new';
}

function processRecord($r, &$counters, $baseCounter, $from_date, $to_date) {
    $type = getCustomerType($r['cus_type'], $r['cus_exist_type']);
    $status_date = !empty($r['updated_date']) ? date('Y-m-d', strtotime($r['updated_date'])) : '';
    $issue_date = !empty($r['issue_date']) ? date('Y-m-d', strtotime($r['issue_date'])) : '';
    $req_status = $r['cus_status'];

    // Base counter
    $counters[$baseCounter][$type]++;
    $counters[$baseCounter]['total']++;

    // Status checks (verification statuses)
    if ($status_date >= $from_date && $status_date <= $to_date && in_array($req_status, [5,6,7])) {
        $counters['cancel'][$type]++;
        $counters['cancel']['total']++;
    } elseif ($status_date >= $from_date && $status_date <= $to_date && $req_status == 9) {
        $counters['revoke'][$type]++;
        $counters['revoke']['total']++;
    } elseif ($issue_date >= $from_date && $issue_date <= $to_date && !empty($r['sub_status'])) {
        $counters['issued'][$type]++;
        $counters['issued']['total']++;
        
        // Status breakdown
        $sub = strtolower($r['sub_status']);
        if (isset($counters['status'][$sub])) {
            $counters['status'][$sub]++;
            $counters['status']['total']++;
        }
        
    } elseif ($baseCounter === 'verification') {
        $counters['process'][$type]++;
        $counters['process']['total']++;
    }
}

/* =====================
   MAIN QUERIES (FIXED)
===================== */

// PREVIOUS RECORDS (verification before date range)
$prevQuery = "
    SELECT 
        vlc.loan_category, ia.req_id, ia.insert_login_id, cp.cus_type, cp.cus_exist_type,
        req.cus_status, req.updated_date, ii.updated_date AS issue_date, cs.sub_status,
        vlc.create_date
    FROM verification_loan_calculation vlc
    JOIN in_approval ia ON ia.req_id = vlc.req_id
    JOIN request_creation req ON req.req_id = ia.req_id
    JOIN customer_profile cp ON cp.req_id = ia.req_id
    LEFT JOIN in_issue ii ON ii.req_id = ia.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    WHERE ia.insert_login_id IN ($placeholders) 
    AND DATE(ia.created_date) < ?
    AND NOT (req.cus_status IN (5,6,7,9) AND DATE(req.updated_date) < ?)
    AND NOT (ii.updated_date IS NOT NULL AND DATE(ii.updated_date) < ?)
";

$stmt = $connect->prepare($prevQuery);
$stmt->execute(array_merge($userIds, [$from_date, $from_date, $from_date]));
$previousRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// CURRENT RECORDS (verification in date range)
$currentQuery = "
    SELECT 
        vlc.loan_category, ia.req_id, ia.insert_login_id, cp.cus_type, cp.cus_exist_type,
        req.cus_status, req.updated_date, ii.updated_date AS issue_date, cs.sub_status,
        vlc.create_date
    FROM verification_loan_calculation vlc
    JOIN in_approval ia ON ia.req_id = vlc.req_id
    JOIN request_creation req ON req.req_id = ia.req_id
    JOIN customer_profile cp ON cp.req_id = ia.req_id
    LEFT JOIN in_issue ii ON ii.req_id = ia.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    WHERE ia.insert_login_id IN ($placeholders) 
    AND DATE(ia.created_date) BETWEEN ? AND ?
";

$stmt = $connect->prepare($currentQuery);
$stmt->execute(array_merge($userIds, [$from_date, $to_date]));
$currentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   GROUP RECORDS
===================== */

$prevByUserCat = [];
foreach ($previousRecords as $r) {
    $prevByUserCat[$r['insert_login_id']][$r['loan_category']][] = $r;
}

$currentByUserCat = [];
foreach ($currentRecords as $r) {
    $currentByUserCat[$r['insert_login_id']][$r['loan_category']][] = $r;
}

/* =====================
   GENERATE REPORT
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
            'verification'  => emptyTypeCounter(),
            'cancel'   => emptyTypeCounter(),
            'revoke'   => emptyTypeCounter(),
            'process'  => emptyTypeCounter(),
            'issued'   => emptyTypeCounter(),
            'status'   => emptyStatusCounter()
        ];

        // Process records
        foreach ($prevByUserCat[$userId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'previous', $from_date, $to_date);
        }
        foreach ($currentByUserCat[$userId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'verification', $from_date, $to_date);
        }

        $data[] = [
            "sno" => $sno++,
            "fullname" => $userName,
            "loan_category" => $cat_name,
            "previous" => $counters['previous'],
            "verification"  => $counters['verification'],
            "cancel"   => $counters['cancel'],
            "revoke"   => $counters['revoke'],
            "process"  => $counters['process'],
            "issued"   => $counters['issued'],
            "status"   => $counters['status']
        ];
    }
}

/* =====================
   TOTALS
===================== */

$totals = array_fill_keys(
    ['previous', 'verification', 'cancel', 'revoke', 'process', 'issued'], 
    emptyTypeCounter()
);
$totals['status'] = emptyStatusCounter();

foreach ($data as $row) {
    foreach ($totals as $key => &$counter) {
        foreach ($counter as $type => $val) {
            $counter[$type] += $row[$key][$type] ?? 0;
        }
    }
}

$data[] = [
    "sno" => "",
    "fullname" => $user_id == 'all' ? "All Users Total" : "Total",
    "loan_category" => "",
    "previous" => $totals['previous'],
    "verification" => $totals['verification'],
    "cancel" => $totals['cancel'],
    "revoke" => $totals['revoke'],
    "process" => $totals['process'],
    "issued" => $totals['issued'],
    "status" => $totals['status']
];

echo json_encode(["data" => $data]);
?>