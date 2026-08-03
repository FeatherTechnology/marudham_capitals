<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];

$where = "";

$user_type = $_POST['user_type'] ?? '';

if ($user_type == '2') {
    $where .= " AND u.status = 0";
} elseif ($user_type == '3') {
    $where .= " AND u.status = 1";
}

$selectedType = $_POST['selectedType'] ?? '';
$selectedVal = $_POST['selectedVal'] ?? '';

if(is_array($selectedVal)) {
    $selectedVal = implode(',', $selectedVal);
}

$loanCatVal = $_POST['loanCatVal'] ?? '';

if(is_array($loanCatVal)) {
    $loanCatVal = implode(',', $loanCatVal);
}

$joinTable ='';
$condition = '';
$condtn = '';

if ($selectedType == '2') { //Sector
    $joinTable  = "  JOIN area_group_mapping_sub_area agmsa ON req.sub_area = agmsa.sub_area_id";
    $condition  = "AND agmsa.group_map_id IN ($selectedVal)";

    $condtn  = "WHERE loan_category_creation_id IN ($loanCatVal)";
} 

/* =====================
   USER FILTER (from in_acknowledgement)
===================== */

if ($user_id != 'all' && !empty($user_id)) {
    if (!is_array($user_id)) {
        $user_id = explode(',', $user_id);
    }
    $userIds = array_map('intval', $user_id);
} else {
    $stmt = $connect->prepare("SELECT DISTINCT u.user_id
        FROM in_acknowledgement iak
        LEFT JOIN user u ON iak.insert_login_id = u.user_id
        WHERE iak.insert_login_id != ''
        $where
    ");
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if (empty($userIds)) {
    echo json_encode(["data" => []]);
    exit;
}

/* =====================
   SETUP MAPS & CONFIGURATIONS
===================== */

$placeholders = str_repeat('?,', count($userIds) - 1) . '?';
$nameMap = [];

if ($selectedType == '2' && !empty($selectedVal)) {
    $valArray = explode(',', $selectedVal);
    $sectorPlaceholders = str_repeat('?,', count($valArray) - 1) . '?';
    
    $stmt = $connect->prepare("
        SELECT map_id, group_name 
        FROM area_group_mapping 
        WHERE map_id IN ($sectorPlaceholders) 
        ORDER BY group_name ASC
    ");
    $stmt->execute($valArray);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} else {
    $stmt = $connect->prepare("
        SELECT user_id, fullname 
        FROM user 
        WHERE user_id IN ($placeholders) 
        ORDER BY fullname ASC
    ");
    $stmt->execute($userIds);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Loan categories
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name 
    FROM loan_category_creation $condtn
")->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   HELPER FUNCTIONS
===================== */

function emptyTypeCounter() {
    return ['new' => 0, 'renewal' => 0, 'reactive' => 0, 'additional' => 0, 'existing_new' => 0, 'reloan'=>0,'total' => 0];
}

function emptyStatusCounter() {
    return ['current' => 0, 'pending' => 0, 'od' => 0, 'error' => 0, 'legal' => 0, 'total' => 0];
}

function getCustomerType($cus_type, $cus_exist_type) {
    if (strtolower($cus_type) == 'new') {
        return 'new';
    }
    $existType = strtolower(trim($cus_exist_type));
    $existType = str_replace(  ['re-active', 'existing-new'],  ['reactive', 'existing_new'],  $existType);
    return in_array($existType, [
        'additional',
        'renewal',
        'reactive',
        'existing_new',
        'reloan'
    ]) ? $existType : 'existing_new';
}

function processRecord($r, &$counters, $baseCounter, $from_date, $to_date) {
    $type = getCustomerType($r['cus_type'], $r['cus_exist_type']);
    $status_date = !empty($r['updated_date']) ? date('Y-m-d', strtotime($r['updated_date'])) : '';
    $issue_date = !empty($r['issue_date']) ? date('Y-m-d', strtotime($r['issue_date'])) : '';
    $req_status = $r['cus_status'];

    // Base counter
    $counters[$baseCounter][$type]++;
    $counters[$baseCounter]['total']++;

    // Status checks
    if ($status_date >= $from_date && $status_date <= $to_date && in_array($req_status, [5,6,7])) {
        $counters['cancel'][$type]++;
        $counters['cancel']['total']++;
    } elseif ($issue_date >= $from_date && $issue_date <= $to_date && !empty($r['sub_status'])) {
        $counters['issued'][$type]++;
        $counters['issued']['total']++;
        
        // Status breakdown
        $sub = strtolower($r['sub_status']);
        if (isset($counters['status'][$sub])) {
            $counters['status'][$sub]++;
            $counters['status']['total']++;
        }
    } elseif ($baseCounter === 'approval' || $baseCounter === 'previous') {
        $counters['process'][$type]++;
        $counters['process']['total']++;
    }
}

// Dynamic field mapping for group assignment
$groupSelect = ($selectedType == '2') ? ", agmsa.group_map_id AS target_group_id" : ", ia.insert_login_id AS target_group_id";
// PREVIOUS RECORDS
$prevQuery = "
    SELECT 
        vlc.loan_category, ia.req_id, ia.insert_login_id, cp.cus_type, cp.cus_exist_type,
        req.cus_status, req.updated_date, ii.updated_date AS issue_date, cs.sub_status,
        vlc.create_date $groupSelect
    FROM verification_loan_calculation vlc
    JOIN in_acknowledgement ia ON ia.req_id = vlc.req_id
    JOIN request_creation req ON req.req_id = ia.req_id
    JOIN customer_profile cp ON cp.req_id = ia.req_id
    LEFT JOIN in_issue ii ON ii.req_id = ia.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    $joinTable
    WHERE ia.insert_login_id IN ($placeholders) 
    AND DATE(ia.inserted_date) < ?
    AND NOT (req.cus_status IN (5,6,7,9) AND DATE(req.updated_date) < ?)
    AND NOT (ii.updated_date IS NOT NULL AND DATE(ii.updated_date) < ?)
    $condition
";

$stmt = $connect->prepare($prevQuery);
$stmt->execute(array_merge($userIds, [$from_date, $from_date, $from_date]));
$previousRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// CURRENT RECORDS
$currentQuery = "
    SELECT 
        vlc.loan_category, ia.req_id, ia.insert_login_id, cp.cus_type, cp.cus_exist_type,
        req.cus_status, req.updated_date, ii.updated_date AS issue_date, cs.sub_status,
        vlc.create_date $groupSelect
    FROM verification_loan_calculation vlc
    JOIN in_acknowledgement ia ON ia.req_id = vlc.req_id
    JOIN request_creation req ON req.req_id = ia.req_id
    JOIN customer_profile cp ON cp.req_id = ia.req_id
    LEFT JOIN in_issue ii ON ii.req_id = ia.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    $joinTable
    WHERE ia.insert_login_id IN ($placeholders) 
    AND (DATE(ia.inserted_date) BETWEEN ? AND ?)
    $condition
";

$stmt = $connect->prepare($currentQuery);
$stmt->execute(array_merge($userIds, [$from_date, $to_date]));
$currentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   GROUP RECORDS
===================== */

$prevByUserCat = [];
foreach ($previousRecords as $r) {
    $prevByUserCat[$r['target_group_id']][$r['loan_category']][] = $r;
}

$currentByUserCat = [];
foreach ($currentRecords as $r) {
    $currentByUserCat[$r['target_group_id']][$r['loan_category']][] = $r;
}

/* =====================
   GENERATE REPORT
===================== */

$data = [];
$sno = 1;

foreach ($nameMap as $targetId => $targetName) {
    foreach ($loanCats as $cat) {
        $cat_id = $cat['loan_category_creation_id'];
        $cat_name = $cat['loan_category_creation_name'];

        if (empty($prevByUserCat[$targetId][$cat_id] ?? []) && 
            empty($currentByUserCat[$targetId][$cat_id] ?? [])) {
            continue;
        }

        $counters = [
            'previous' => emptyTypeCounter(),
            'approval'  => emptyTypeCounter(),
            'cancel'   => emptyTypeCounter(),
            'process'  => emptyTypeCounter(),
            'issued'   => emptyTypeCounter(),
            'status'   => emptyStatusCounter()
        ];

        // Process records
        foreach ($prevByUserCat[$targetId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'previous', $from_date, $to_date);
        }
        foreach ($currentByUserCat[$targetId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'approval', $from_date, $to_date);
        }

        $data[] = [
            "sno" => $sno++,
            "fullname" => $targetName,
            "loan_category" => $cat_name,
            "previous" => $counters['previous'],
            "approval"  => $counters['approval'],
            "cancel"   => $counters['cancel'],
            "process"  => $counters['process'],
            "issued"   => $counters['issued'],
            "status"   => $counters['status']
        ];
    }
}


$totals = array_fill_keys(
    ['previous', 'approval', 'cancel', 'process', 'issued'], 
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
    "approval" => $totals['approval'],
    "cancel" => $totals['cancel'],
    "process" => $totals['process'],
    "issued" => $totals['issued'],
    "status" => $totals['status']
];

echo json_encode(["data" => $data]);
?>