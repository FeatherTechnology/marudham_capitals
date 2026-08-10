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

$selectedVal = is_array($selectedVal) ? implode(',', $selectedVal) : $selectedVal;

$loanCatVal = $_POST['loanCatVal'] ?? '';

if (is_array($loanCatVal)) {
    $loanCatVal = implode(',', $loanCatVal);
}

$joinTable = '';
$condition = '';
$joinTable1 = '';
$condition1 = '';

if ($selectedType == '2') { //Sector
    $joinTable  = "  JOIN area_group_mapping_sub_area agmsa ON req.sub_area = agmsa.sub_area_id";
    $condition  = "AND agmsa.group_map_id IN ($selectedVal)";
} else if ($selectedType == '3') { //Region
    $joinTable = "  JOIN area_line_mapping_sub_area almsa ON req.sub_area = almsa.sub_area_id";
    $condition = "AND almsa.line_map_id IN ($selectedVal)";
} else if ($selectedType == '4') { //Zone
    $joinTable = "  JOIN area_duefollowup_mapping_area adma ON req.area = adma.area_id";
    $condition = "AND adma.duefollowup_map_id IN ($selectedVal)";
} elseif ($selectedType == '5' || $selectedType == '6') { // Department / Team
    $joinTable1 = "JOIN staff_creation sc ON sc.staff_id = u.staff_id";

    $field = ($selectedType == '5') ? 'department' : 'team';
    $condition1 = "AND sc.$field = '$selectedVal'";
}

/* =====================
   USER FILTER
===================== */

if ($user_id != 'all' && !empty($user_id)) {
    if (!is_array($user_id)) {
        $user_id = explode(',', $user_id);
    }
    $userIds = array_map('intval', $user_id);
} else {
    $stmt = $connect->prepare("SELECT DISTINCT u.user_id
    FROM request_creation r
    LEFT JOIN user u ON r.insert_login_id = u.user_id
    $joinTable1 $condition1
    WHERE r.insert_login_id != ''
    $where");
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if (empty($userIds)) {
    echo json_encode(["data" => []]);
    exit;
}

/* =====================
   DYNAMIC MAP (USER OR SECTOR) + LOAN CATS
===================== */

$placeholders = str_repeat('?,', count($userIds) - 1) . '?';
$nameMap = [];
if ($selectedType == '1' || $selectedType == '5' || $selectedType == '6' && !empty($selectedVal)) {
    // Default fallback: Fetch User names
    $stmt = $connect->prepare("
        SELECT user_id, fullname 
        FROM user 
        WHERE user_id IN ($placeholders) 
        ORDER BY fullname ASC
    ");
    $stmt->execute($userIds);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // structure: user_id => fullname
} else if ($selectedType == '2' && !empty($selectedVal)) {
    // If Sector is selected, split selectedVal into an array for placeholders
    $valArray = explode(',', $selectedVal);
    $sectorPlaceholders = str_repeat('?,', count($valArray) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT map_id, group_name 
        FROM area_group_mapping 
        WHERE map_id IN ($sectorPlaceholders) 
        ORDER BY group_name ASC
    ");
    $stmt->execute($valArray);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // structure: map_id => group_name
} else if ($selectedType == '3' && !empty($selectedVal)) {
    // If Region is selected, split selectedVal into an array for placeholders
    $valArray = explode(',', $selectedVal);
    $regionPlaceholders = str_repeat('?,', count($valArray) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT map_id, line_name 
        FROM area_line_mapping 
        WHERE map_id IN ($regionPlaceholders) 
        ORDER BY line_name ASC
    ");
    $stmt->execute($valArray);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // structure: map_id => line_name
} else if ($selectedType == '4' && !empty($selectedVal)) {
    // If Zone is selected, split selectedVal into an array for placeholders
    $valArray = explode(',', $selectedVal);
    $zonePlaceholders = str_repeat('?,', count($valArray) - 1) . '?';

    $stmt = $connect->prepare("
        SELECT map_id, duefollowup_name 
        FROM area_duefollowup_mapping 
        WHERE map_id IN ($zonePlaceholders) 
        ORDER BY duefollowup_name ASC
    ");
    $stmt->execute($valArray);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // structure: map_id => duefollowup_name

}

// Loan categories
$query = "SELECT loan_category_creation_id, loan_category_creation_name FROM loan_category_creation";

if ($selectedType !== '1') {
    $query .= " WHERE loan_category_creation_id IN ($loanCatVal)";
}

$loanCats = $connect->query($query)->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   PRELOAD CUSTOMER HISTORY (Exact Model Match)
===================== */

$historyStmt = $connect->query("SELECT req.cus_id, req.req_id,req.cus_status,csd.created_date AS closing_date,cc.closing_date AS closed_date,req.dor,cs1.sub_status,dn.due_nil_date
FROM request_creation req
LEFT JOIN closed_status csd ON csd.req_id = req.req_id
LEFT JOIN closing_customer cc ON cc.req_id = req.req_id
LEFT JOIN customer_status cs1 ON cs1.req_id = req.req_id
LEFT JOIN ( SELECT
        req_id, MAX(coll_date) AS due_nil_date
    FROM collection
    WHERE coll_sub_status = 'Due Nil'
    GROUP BY req_id
) dn ON dn.req_id = req.req_id
$joinTable
WHERE req.cus_status NOT IN (4,5,6,7,8,9)
$condition
GROUP BY req.req_id;
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
        //     if ($issue['cus_status'] == 0) {
        //     continue;
        // }
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
    //    1. DUE NIL -> reloan or CURRENT REQUEST <= DUE NIL DATE -> reloan
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
    //    4.Current Request <= Closing Date
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

function emptyTypeCounter()
{
    return ['new' => 0, 'renewal' => 0, 'reactive' => 0, 'additional' => 0, 'existing_new' => 0, 'reloan' => 0, 'total' => 0];
}
function emptyStatusCounter()
{
    return ['current' => 0, 'pending' => 0, 'od' => 0, 'error' => 0, 'legal' => 0, 'total' => 0];
}

// Select either the Sector Map ID or User ID dynamically so records group correctly
$groupSelect = "";

if ($selectedType == '1' || $selectedType == '5' || $selectedType == '6') {
    $groupSelect = ", req.insert_login_id AS target_group_id";
} elseif ($selectedType == '2') {
    $groupSelect = ", agmsa.group_map_id AS target_group_id";
} elseif ($selectedType == '3') {
    $groupSelect = ", almsa.line_map_id AS target_group_id";
} elseif ($selectedType == '4') {
    $groupSelect = ", adma.duefollowup_map_id AS target_group_id";
}

$prevQuery = "
    SELECT req.req_id, req.cus_id, req.cus_data, req.cus_status,
           req.created_date, req.updated_date,
           ii.updated_date AS issue_date, req.loan_category, cs.sub_status
           $groupSelect
    FROM request_creation req
    LEFT JOIN in_issue ii ON ii.req_id = req.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    $joinTable
    WHERE req.insert_login_id IN ($placeholders) 
    AND DATE(req.created_date) < ?
    AND NOT (
        req.cus_status IN (4,5,6,7,8,9)
        AND DATE(req.updated_date) < ?
    )
    AND NOT (
        ii.updated_date IS NOT NULL
        AND DATE(ii.updated_date) < ?
    )
    $condition
";

$stmt = $connect->prepare($prevQuery);
$stmt->execute(array_merge($userIds, [$from_date, $from_date, $from_date]));
$previousRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentQuery = "
    SELECT req.req_id, req.cus_id, req.cus_data, req.cus_status,
           req.created_date, req.updated_date, ii.updated_date AS issue_date,
           req.loan_category, cs.sub_status
           $groupSelect
    FROM request_creation req
    LEFT JOIN in_issue ii ON ii.req_id = req.req_id AND ii.cus_status >= 14
    LEFT JOIN customer_status cs ON ii.req_id = cs.req_id AND ii.cus_status >= 14
    $joinTable
    WHERE req.insert_login_id IN ($placeholders) 
    AND (DATE(req.created_date) BETWEEN ? AND ?)
    $condition
";
$stmt = $connect->prepare($currentQuery);
$stmt->execute(array_merge($userIds, [$from_date, $to_date]));
$currentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by Group ID (Sector or User ID) + CATEGORY
$prevByGroupCat = [];
foreach ($previousRecords as $r) {
    $prevByGroupCat[$r['target_group_id']][$r['loan_category']][] = $r;
}

$currentByGroupCat = [];
foreach ($currentRecords as $r) {
    $currentByGroupCat[$r['target_group_id']][$r['loan_category']][] = $r;
}

$data = [];
$sno = 1;

foreach ($nameMap as $targetId => $targetName) {
    foreach ($loanCats as $cat) {
        $cat_id = $cat['loan_category_creation_id'];
        $cat_name = $cat['loan_category_creation_name'];

        if (
            empty($prevByGroupCat[$targetId][$cat_id] ?? []) &&
            empty($currentByGroupCat[$targetId][$cat_id] ?? [])
        ) {
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
        foreach ($prevByGroupCat[$targetId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'previous', $from_date, $to_date, $historyData);
        }

        // Process current records  
        foreach ($currentByGroupCat[$targetId][$cat_id] ?? [] as $r) {
            processRecord($r, $counters, 'request', $from_date, $to_date, $historyData);
        }

        $data[] = [
            "sno" => $sno++,
            "fullname" => $targetName, // Dynamic Column: Shows Sector Name if selectedType == 2, else User's Fullname
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

function processRecord($r, &$counters, $baseCounter, $from_date, $to_date, $historyData)
{
    $type = getCustomerTypeFast($r['cus_data'], $r['created_date'], $r['cus_id'], $r['req_id'], $historyData);
    $status_date = !empty($r['updated_date']) ? date('Y-m-d', strtotime($r['updated_date'])) : '';
    $issue_date = !empty($r['issue_date']) ? date('Y-m-d', strtotime($r['issue_date'])) : '';
    $req_status = $r['cus_status'];

    // Base counter
    $counters[$baseCounter][$type]++;
    $counters[$baseCounter]['total']++;

    // Status checks
    if ($status_date >= $from_date && $status_date <= $to_date && in_array($req_status, [4, 5, 6, 7])) {
        $counters['cancel'][$type]++;
        $counters['cancel']['total']++;
    } elseif ($status_date >= $from_date && $status_date <= $to_date && in_array($req_status, [8, 9])) {
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
    "fullname" => $user_id == 'all' ? "All Total" : "Total",
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
