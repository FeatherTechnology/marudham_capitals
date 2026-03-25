<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];

/* ===================== USER FILTER ===================== */

if ($user_id != 'all') {
    if (!is_array($user_id)) {
        $user_id = explode(',', $user_id);
    }
    $userIds = array_map('intval', $user_id);
} else {
    $stmt = $connect->prepare("
       SELECT DISTINCT insert_login_id  FROM `document_track` WHERE insert_login_id != '' ");
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if (empty($userIds)) {
    echo json_encode(["data" => []]);
    exit;
}

/* =====================  USER MAP + LOAN CATS ===================== */

$placeholders = str_repeat('?,', count($userIds) - 1) . '?';

$stmt = $connect->prepare(" SELECT user_id, fullname FROM user WHERE user_id IN ($placeholders) order by fullname asc ");
$stmt->execute($userIds);
$userMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$loanCats = $connect->query("  SELECT loan_category_creation_id, loan_category_creation_name FROM loan_category_creation ")->fetchAll(PDO::FETCH_ASSOC);

// Initialize grand totals
$grandTotals = [
    'new' => 0,
    'additional' => 0,
    'renewal' => 0,
    'reactive' => 0,
    'existing_new' => 0,
    'total_count' => 0,
    'current' => 0,
    'pending' => 0,
    'od' => 0,
    'error' => 0,
    'legal' => 0,
    'status_total' => 0
];

/* =====================  COUNTERS ===================== */

function getCustomerType($cus_type, $cus_exist_type) {
    if (strtolower($cus_type) == 'new') return 'new';
    
    $existType = strtolower(trim($cus_exist_type));
    $existType = str_replace(['re-active', 'existing-new'], ['reactive', 'existing_new'], $existType);
    
    return in_array($existType, ['additional', 'renewal', 'reactive', 'existing_new']) 
        ? $existType : 'existing_new';
}
function emptyTypeCounter() {
    return ['new' => 0, 'renewal' => 0, 'reactive' => 0, 'additional' => 0, 'existing_new' => 0, 'total' => 0];
}

function emptyStatusCounter() {
    return ['current' => 0, 'pending' => 0, 'od' => 0, 'error' => 0, 'legal' => 0, 'total' => 0];
}


$currentQuery = "
     SELECT li.req_id, li.cus_id, li.agent_id, dt.insert_login_id,cp.cus_type, cp.cus_exist_type, ac.ag_name,li.created_date,alc.loan_category,cs.sub_status
        FROM loan_issue li
        JOIN acknowlegement_loan_calculation alc ON alc.req_id = li.req_id
        LEFT JOIN customer_status cs ON li.req_id = cs.req_id
        LEFT JOIN agent_creation ac ON ac.ag_id = li.agent_id
        LEFT JOIN document_track dt ON dt.req_id = li.req_id
        LEFT JOIN customer_profile cp ON cp.req_id = li.req_id
        WHERE dt.insert_login_id IN ($placeholders) 
    AND DATE(li.created_date) BETWEEN ? AND ? 
";
// echo $currentQuery;die;
$stmt = $connect->prepare($currentQuery);
$stmt->execute(array_merge($userIds, [$from_date, $to_date]));
$currentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);


$currentByUserCat = [];
foreach ($currentRecords as $r) {
    $currentByUserCat[$r['insert_login_id']][$r['agent_id']][$r['loan_category']][] = $r;
}

/* =====================  PROCESS DATA (OPTIMIZED) ===================== */
$data = [];
$sno = 1;

foreach ($userMap as $userId => $userName) {

    if (empty($currentByUserCat[$userId])) continue;

    foreach ($currentByUserCat[$userId] as $agentId => $catData) {

        foreach ($loanCats as $cat) {
            $cat_id = $cat['loan_category_creation_id'];
            $cat_name = $cat['loan_category_creation_name'];

            if (empty($catData[$cat_id] ?? [])) continue;

            $counters = [
                'issued'   => emptyTypeCounter(),
                'status'   => emptyStatusCounter()
            ];

            // Process current records  
            foreach ($catData[$cat_id] as $r){
                processRecord($r, $counters);
            }

            // Prepare the row
            $row = [
                "sno" => $sno++,
                "fullname" => $userName,
                "agent_name" => $catData[$cat_id][0]['ag_name'],
                "loan_category" => $cat_name,

                "new" => $counters['issued']['new'],
                "additional" => $counters['issued']['additional'],
                "renewal" => $counters['issued']['renewal'],
                "reactive" => $counters['issued']['reactive'],
                "existing_new" => $counters['issued']['existing_new'],
                "total_count" => $counters['issued']['total'],

                "current" => $counters['status']['current'],
                "pending" => $counters['status']['pending'],
                "od" => $counters['status']['od'],
                "error" => $counters['status']['error'],
                "legal" => $counters['status']['legal'],
                "status_total" => $counters['status']['total']
            ];

            // Accumulate grand totals
            foreach ($grandTotals as $key => &$val) {
                if (isset($row[$key])) $val += $row[$key];
            }
            unset($val); // break reference

            // Push the row
            $data[] = $row;
        }
    }
}

/* ===================== PROCESS RECORD FUNCTION (DRY) ===================== */

function processRecord($r, &$counters) {
    
    $type = getCustomerType($r['cus_type'], $r['cus_exist_type']);
    
    $counters['issued'][$type]++;
    $counters['issued']['total']++;
        
        // Status breakdown FIXED
    $sub = strtolower($r['sub_status']);
    if (isset($counters['status'][$sub])) {
        $counters['status'][$sub]++;
        $counters['status']['total']++;
    }
        
    
}
/* =====================  TOTALS ===================== */

$totals = [
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
    "fullname" => $user_id == 'all' ? "All Users Total" : "Total",
    "agent_name" => "",
    "loan_category" => "",

    "new" => $grandTotals['new'],
    "additional" => $grandTotals['additional'],
    "renewal" => $grandTotals['renewal'],
    "reactive" => $grandTotals['reactive'],
    "existing_new" => $grandTotals['existing_new'],
    "total_count" => $grandTotals['total_count'],

    "current" => $grandTotals['current'],
    "pending" => $grandTotals['pending'],
    "od" => $grandTotals['od'],
    "error" => $grandTotals['error'],
    "legal" => $grandTotals['legal'],
    "status_total" => $grandTotals['status_total']
];

echo json_encode(["data" => $data]);
?>