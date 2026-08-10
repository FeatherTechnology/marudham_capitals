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
    $joinTable  = "  JOIN area_group_mapping_sub_area agmsa ON cp.area_confirm_subarea = agmsa.sub_area_id";
    $condition  = "AND agmsa.group_map_id IN ($selectedVal)";
} elseif ($selectedType == '5' || $selectedType == '6') { // Department / Team
    $joinTable1 = "JOIN staff_creation sc ON sc.staff_id = u.staff_id";

    $field = ($selectedType == '5') ? 'department' : 'team';
    $condition1 = "AND sc.$field = '$selectedVal'";
}
// else if ($selectedType == '3') { //Region
//     $joinTable = "  JOIN area_line_mapping_sub_area almsa ON cp.area_confirm_subarea = almsa.sub_area_id";
//     $condition = "AND almsa.line_map_id IN ($selectedVal)";

// } else if ($selectedType == '4') { //Zone
//     $joinTable = "  JOIN area_duefollowup_mapping_area adma ON cp.area_confirm_area = adma.area_id";
//     $condition = "AND adma.duefollowup_map_id IN ($selectedVal)";
// } 

/* ===================== USER FILTER ===================== */

if ($user_id != 'all' && !empty($user_id)) {
    if (!is_array($user_id)) {
        $user_id = explode(',', $user_id);
    }
    $userIds = array_map('intval', $user_id);
} else {
    $stmt = $connect->prepare("SELECT DISTINCT u.user_id
        FROM document_track dtk
        LEFT JOIN user u ON dtk.insert_login_id = u.user_id
        $joinTable1 $condition1
        WHERE dtk.insert_login_id != ''
        $where
    ");
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if (empty($userIds)) {
    echo json_encode(["data" => []]);
    exit;
}

/* =====================  USER MAP + LOAN CATS ===================== */

$placeholders = str_repeat('?,', count($userIds) - 1) . '?';
$nameMap = [];

if ($selectedType == '2' && !empty($selectedVal)) {
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
} else {
    // Default fallback: Fetch User names
    $stmt = $connect->prepare("
        SELECT user_id, fullname 
        FROM user 
        WHERE user_id IN ($placeholders) 
        ORDER BY fullname ASC
    ");
    $stmt->execute($userIds);
    $nameMap = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // structure: user_id => fullname
}

// Loan categories
$query = "SELECT loan_category_creation_id, loan_category_creation_name FROM loan_category_creation";

if ($selectedType !== '1') {
    $query .= " WHERE loan_category_creation_id IN ($loanCatVal)";
}

$loanCats = $connect->query($query)->fetchAll(PDO::FETCH_ASSOC);

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

function getCustomerType($cus_type, $cus_exist_type)
{
    if (strtolower($cus_type) == 'new') return 'new';

    $existType = strtolower(trim($cus_exist_type));
    $existType = str_replace(['re-active', 'existing-new'], ['reactive', 'existing_new'], $existType);

    return in_array($existType, ['additional', 'renewal', 'reactive', 'existing_new'])
        ? $existType : 'existing_new';
}
function emptyTypeCounter()
{
    return ['new' => 0, 'renewal' => 0, 'reactive' => 0, 'additional' => 0, 'existing_new' => 0, 'total' => 0];
}

function emptyStatusCounter()
{
    return ['current' => 0, 'pending' => 0, 'od' => 0, 'error' => 0, 'legal' => 0, 'total' => 0];
}

// Select either the Sector Map ID or User ID dynamically so records group correctly (Fixed Table Alias to dt)
$groupSelect = ($selectedType == '2') ? ", agmsa.group_map_id AS target_group_id" : ", dt.insert_login_id AS target_group_id";

$currentQuery = "
     SELECT li.req_id, li.cus_id, li.agent_id, dt.insert_login_id, cp.cus_type, cp.cus_exist_type, ac.ag_name, li.created_date, alc.loan_category, cs.sub_status $groupSelect
        FROM loan_issue li
        JOIN acknowlegement_loan_calculation alc ON alc.req_id = li.req_id
        LEFT JOIN customer_status cs ON li.req_id = cs.req_id
        LEFT JOIN agent_creation ac ON ac.ag_id = li.agent_id
        LEFT JOIN document_track dt ON dt.req_id = li.req_id
        LEFT JOIN customer_profile cp ON cp.req_id = li.req_id
        $joinTable
        WHERE dt.insert_login_id IN ($placeholders) 
        AND (DATE(li.created_date) BETWEEN ? AND ?)
        $condition 
";

$stmt = $connect->prepare($currentQuery);
// Correctly merged parameters to align placeholders
$stmt->execute(array_merge($userIds, [$from_date, $to_date]));
$currentRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentByUserCat = [];
foreach ($currentRecords as $r) {
    $currentByUserCat[$r['target_group_id']][$r['agent_id']][$r['loan_category']][] = $r;
}

/* =====================  PROCESS DATA (OPTIMIZED) ===================== */
$data = [];
$sno = 1;

foreach ($nameMap as $targetId => $userName) {

    if (empty($currentByUserCat[$targetId])) continue;

    foreach ($currentByUserCat[$targetId] as $agentId => $catData) {

        foreach ($loanCats as $cat) {
            $cat_id = $cat['loan_category_creation_id'];
            $cat_name = $cat['loan_category_creation_name'];

            if (empty($catData[$cat_id] ?? [])) continue;

            $counters = [
                'issued'   => emptyTypeCounter(),
                'status'   => emptyStatusCounter()
            ];

            // Process current records  
            foreach ($catData[$cat_id] as $r) {
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

function processRecord($r, &$counters)
{

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
