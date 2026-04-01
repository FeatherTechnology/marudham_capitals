<?php
include '../ajaxconfig.php';
include '../user_based_sub_area_Ids.php';
session_start();

$userid = $_SESSION['userid'] ?? 0;

// Default user flags
$ver_loan_cat                = "0";
$app_loan_cat                = "0";
$ack_loan_cat                = "0";
$verification_access         = 1;
$approval_access             = 1;
$acknowledgement_access      = 1;
$loan_issue_access           = 1;
$accounts_loan_issue_access  = 1;
$closed_access               = 1;
$noc_access                  = 1;
$noc_handover_access         = 1;
$noc_mapping_access          = 1;

if ($userid != 1) {
    $stmt = $connect->prepare("
        SELECT ver_loan_cat, app_loan_cat, ack_loan_cat,
               verification, approval, acknowledgement,
               loan_issue, accounts_loan_issue,
               closed, noc, noc_handover, noc_mapping_access
        FROM user 
        WHERE user_id = ?
    ");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);

    $ver_loan_cat                = $rowuser['ver_loan_cat'] ?? "0";
    $app_loan_cat                = $rowuser['app_loan_cat'] ?? "0";
    $ack_loan_cat                = $rowuser['ack_loan_cat'] ?? "0";
    $verification_access         = $rowuser['verification'] ?? 1;
    $approval_access             = $rowuser['approval'] ?? 1;
    $acknowledgement_access      = $rowuser['acknowledgement'] ?? 1;
    $loan_issue_access           = $rowuser['loan_issue'] ?? 1;
    $accounts_loan_issue_access  = $rowuser['accounts_loan_issue'] ?? 1;
    $closed_access               = $rowuser['closed'] ?? 1;
    $noc_access                  = $rowuser['noc'] ?? 1;
    $noc_handover_access         = $rowuser['noc_handover'] ?? 1;
    $noc_mapping_access          = $rowuser['noc_mapping_access'] ?? 1;
}

// — Reusable helper for stages in in_verification (Today: created_date for verif, updated_date for others)
function fetchStageData($connect, $whereClause, $userid, $sub_area_list, $loan_cat = null,$screen) {
    $dateField = ($screen == 'verification') ? "created_date" : "updated_date";  // verif uses created_date, others updated_date
    $todayFilter = "DATE(v.$dateField) = CURDATE()";
    
    $countQuery = "SELECT 
        COUNT(DISTINCT CASE WHEN $todayFilter THEN v.req_id END) AS today_count,
        COUNT(DISTINCT v.req_id) AS total_count
        FROM in_verification v
        WHERE $whereClause";

    if ($userid != 1) {
        $filter = " AND v.sub_area IN ($sub_area_list)";
        if ($loan_cat !== null) {
            $filter .= " AND v.loan_category IN ($loan_cat)";
        }
        $countQuery .= $filter;
    }
    $counts = $connect->query($countQuery)->fetch(PDO::FETCH_ASSOC);
    $todayCount = $counts['today_count'] ?? 0;
    $totalCount = $counts['total_count'] ?? 0;

    // Total IDs query (unchanged - for all records)
    $idQuery = "SELECT cr.autogen_cus_id
                FROM in_verification v
                JOIN customer_register cr ON v.cus_id = cr.cus_id
                WHERE $whereClause";

    if ($userid != 1) {
        $filter = " AND v.sub_area IN ($sub_area_list)";
        if ($loan_cat !== null) {
            $filter .= " AND v.loan_category IN ($loan_cat)";
        }
        $idQuery .= $filter;
    }

    $ids = [];
    $res = $connect->query($idQuery);
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $ids[] = $row['autogen_cus_id'];
    }

    return [
        'today' => (int)$todayCount,
        'total' => (int)$totalCount,
        'ids'   => implode(', ', $ids)
    ];
}

// — Reusable helper for closed/noc stages (request_creation) - always updated_date
function fetchClosedAndNocData($connect, $whereClause, $userid, $sub_area_list, $areaType = 'subarea') {
    $column = ($areaType == 'area') ? 'cp.area_confirm_area' : 'cp.area_confirm_subarea';
    $todayFilter = "DATE(req.updated_date) = CURDATE()";
    
    $countQuery = "SELECT 
        COUNT(DISTINCT CASE WHEN $todayFilter THEN req.req_id END) AS today_count,
        COUNT(DISTINCT req.req_id) AS total_count
        FROM request_creation req 
        JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id
        WHERE $whereClause";
    
    if ($userid != 1) {
        $filter = " AND $column IN ($sub_area_list)";
        $countQuery .= $filter;
    }
    $counts = $connect->query($countQuery)->fetch(PDO::FETCH_ASSOC);
    $todayCount = $counts['today_count'] ?? 0;
    $totalCount = $counts['total_count'] ?? 0;

    // Total IDs query
    $idQuery = "SELECT cr.autogen_cus_id
                FROM request_creation req
                JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id
                JOIN customer_register cr ON req.cus_id = cr.cus_id
                WHERE $whereClause";

    if ($userid != 1) {
        $filter = " AND $column IN ($sub_area_list)";
        $idQuery .= $filter;
    }

    $ids = [];
    $res = $connect->query($idQuery);
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
        $ids[] = $row['autogen_cus_id'];
    }

    return [
        'today' => (int)$todayCount,
        'total' => (int)$totalCount,
        'ids'   => implode(', ', $ids)
    ];
}

// — Helper: is user admin or access is 0?
$isAdminOrNoAccess = function($access) use ($userid) {
    return ($userid == 1) || ($access == 0);
};

// — Step 1: compute sub_area_list per stage
$sub_area_list_group = getUserSubAreaList($connect, 'group');
$sub_area_list_line  = getUserSubAreaList($connect, 'line'); // for closed

// For noc & noc_handover
$noc_sub_area_list = $sub_area_list_group;
$noc_column_type = 'subarea';
if ($noc_mapping_access == 2) {
    $noc_sub_area_list = $sub_area_list_line;
} elseif ($noc_mapping_access == 3) {
    $noc_sub_area_list = getUserSubAreaList($connect, 'followup');
    $noc_column_type = 'area';
}

// — Compute all stages
$verification_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($verification_access)) {
    $verification_data = fetchStageData(
        $connect,
        "v.cus_status IN (1,10,11,12)",
        $userid,
        $sub_area_list_group,
        $ver_loan_cat ,
        'verification'  // triggers created_date
    );
}

$approval_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($approval_access)) {
    $approval_data = fetchStageData(
        $connect,
        "v.cus_status = 2",
        $userid,
        $sub_area_list_group,
        $app_loan_cat,
        'approval'
    );
}

$acknowledgement_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($acknowledgement_access)) {
    $acknowledgement_data = fetchStageData(
        $connect,
        "v.cus_status = 3",
        $userid,
        $sub_area_list_group,
        $ack_loan_cat ,
        'acknowledgement'  // triggers created_date
    );
}

$loan_issue_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($loan_issue_access)) {
    $loan_issue_data = fetchStageData(
        $connect,
        "v.cus_status = 13 AND issue_by = 1",
        $userid,
        $sub_area_list_group,
        null,
        'loan_issue'  // triggers updated_date
    );
}

$accounts_loan_issue_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($accounts_loan_issue_access)) {
    $accounts_loan_issue_data = fetchStageData(
        $connect,
        "v.cus_status = 13 AND issue_by = 2",
        $userid,
        $sub_area_list_group,
        null ,
        'accounts_loan_issue'  // triggers updated_date
    );
}

// CLOSED: use 'line' sub_area_list
$closed_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($closed_access)) {
    $closed_data = fetchClosedAndNocData(
        $connect,
        "req.cus_status = 20",
        $userid,
        $sub_area_list_line,
        'subarea'
    );
}

// NOC
$noc_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($noc_access)) {
    $noc_data = fetchClosedAndNocData(
        $connect,
        "req.cus_status IN (21,22)",
        $userid,
        $noc_sub_area_list,
        $noc_column_type
    );
}

$noc_handover_data = ['today' => 0, 'total' => 0, 'ids' => ''];
if ($isAdminOrNoAccess($noc_handover_access)) {
    $noc_handover_data = fetchClosedAndNocData(
        $connect,
        "req.cus_status = 23",
        $userid,
        $noc_sub_area_list,
        $noc_column_type
    );
}

echo json_encode([
    // Today & Total Counts
    'verification_today'            => $verification_data['today'],
    'verification_total'            => $verification_data['total'],
    'approval_today'                => $approval_data['today'],
    'approval_total'                => $approval_data['total'],
    'acknowledgement_today'         => $acknowledgement_data['today'],
    'acknowledgement_total'         => $acknowledgement_data['total'],
    'loan_issue_today'              => $loan_issue_data['today'],
    'loan_issue_total'              => $loan_issue_data['total'],
    'accounts_loan_issue_today'     => $accounts_loan_issue_data['today'],
    'accounts_loan_issue_total'     => $accounts_loan_issue_data['total'],
    'closed_today'                  => $closed_data['today'],
    'closed_total'                  => $closed_data['total'],
    'noc_today'                     => $noc_data['today'],
    'noc_total'                     => $noc_data['total'],
    'noc_handover_today'            => $noc_handover_data['today'],
    'noc_handover_total'            => $noc_handover_data['total'],

    // IDs (unchanged)
    'verification_ids'              => $verification_data['ids'],
    'approval_ids'                  => $approval_data['ids'],
    'acknowledgement_ids'           => $acknowledgement_data['ids'],
    'loan_ids'                      => $loan_issue_data['ids'],
    'ac_loan_ids'                   => $accounts_loan_issue_data['ids'],
    'closed_ids'                    => $closed_data['ids'],          
    'noc_ids'                       => $noc_data['ids'],             
    'noc_handover_ids'              => $noc_handover_data['ids'],     

    // Access flags
    'verification_access'           => $verification_access,
    'approval_access'               => $approval_access,
    'acknowledgement_access'        => $acknowledgement_access,
    'loan_issue_access'             => $loan_issue_access,
    'accounts_loan_issue_access'    => $accounts_loan_issue_access,
    'closed_access'                 => $closed_access,
    'noc_access'                    => $noc_access,
    'noc_handover_access'           => $noc_handover_access
], JSON_NUMERIC_CHECK);
?>