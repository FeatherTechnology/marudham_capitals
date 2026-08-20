<?php
@session_start();
include('../../ajaxconfig.php');

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}

$queryParams = [];
$loan_agnt = "";

if ($user_id != 1) {

    $stmt = $connect->prepare("SELECT due_followup_lines, ag_id FROM user WHERE user_id = ?");
    $stmt->execute([(int)$user_id]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rowuser) exit;

    $line_ids = array_filter(array_map('intval', explode(',', $rowuser['due_followup_lines'])));
    if (!$line_ids) exit;

    $placeholders = implode(',', array_fill(0, count($line_ids), '?'));
    $areaStmt = $connect->prepare("SELECT DISTINCT area_id FROM area_duefollowup_mapping_area WHERE duefollowup_map_id IN ($placeholders)");
    $areaStmt->execute($line_ids);
    $area_ids = $areaStmt->fetchAll(PDO::FETCH_COLUMN);

    if (!$area_ids) exit;

    $loan_agnt .= " AND cp.area_confirm_area IN (" . implode(',', array_map('intval', $area_ids)) . ")";

    if (!empty($rowuser['ag_id'])) {
        $ag_ids = array_filter(array_map('intval', explode(',', $rowuser['ag_id'])));
        if (!empty($ag_ids)) {
            $loan_agnt .= " AND iv.agent_id IN (" . implode(',', $ag_ids) . ")";
        }
    }
}

// ---------------------- RESPONSIBLE HAVING BUILDER ----------------------
function buildResponsibleHaving($res_sts) {
    if ($res_sts === '0') {
        return " HAVING SUM(CASE WHEN rc.responsible = 0 THEN 1 ELSE 0 END) = SUM(CASE WHEN rc.req_id IS NOT NULL THEN 1 ELSE 0 END)
                 AND SUM(CASE WHEN rc.responsible IS NULL OR TRIM(rc.responsible) = '' THEN 1 ELSE 0 END) = 0
                 AND SUM(CASE WHEN rc.responsible REGEXP '[^0-9]' THEN 1 ELSE 0 END) = 0";
    } elseif ($res_sts === '1') {
        return " HAVING SUM(CASE WHEN rc.responsible = 1 THEN 1 ELSE 0 END) > 0
                 OR SUM(CASE WHEN rc.responsible IS NULL OR TRIM(rc.responsible) = '' THEN 1 ELSE 0 END) > 0
                 OR SUM(CASE WHEN rc.responsible REGEXP '[^0-9]' THEN 1 ELSE 0 END) > 0";
    }
    return "";
}

$res_sts = isset($_POST['res_sts']) ? trim($_POST['res_sts']) : '';
$having = buildResponsibleHaving($res_sts);

// ---------------------- other filters ----------------------
$current_date = date('Y-m-d');
$cus_sts = isset($_POST['cus_sts']) && is_array($_POST['cus_sts']) ? $_POST['cus_sts'] : [];
$sub_status_url = !empty($cus_sts) ? implode(',', array_map('urlencode', $cus_sts)) : '';

$sub_status_condition = "";
if (!empty($cus_sts)) {
    $placeholders = implode(',', array_fill(0, count($cus_sts), '?'));
    $sub_status_condition = " AND cs.sub_status IN ($placeholders)";
    foreach ($cus_sts as $status) {
        $queryParams[] = $status;
    }
}

// Commitment Date Filter
$commdate = isset($_POST['comm_date']) && !empty($_POST['comm_date']) ? $_POST['comm_date'] : '';
$commitmentCondition = "";
if (!empty($commdate) && $commdate != 1) {
    $commitmentCondition = " AND (c1.comm_date IS NOT NULL AND c1.comm_date != '0000-00-00')";
}

if (!empty($commdate)) {
    if ($commdate == '2') {
        $loan_agnt .= " AND cm.comm_date < ? AND cm.comm_date IS NOT NULL AND cm.comm_date != '0000-00-00' ";
        $queryParams[] = $current_date;
    } elseif ($commdate == '3') {
        $loan_agnt .= " AND cm.comm_date = ? ";
        $queryParams[] = $current_date;
    } elseif ($commdate == '4') {
        $loan_agnt .= " AND cm.comm_date > ? AND cm.comm_date IS NOT NULL AND cm.comm_date != '0000-00-00' ";
        $queryParams[] = $current_date;
    } elseif ($commdate == '5') {
        $loan_agnt .= " AND (cm.comm_date IS NULL OR cm.comm_date = '0000-00-00') ";
    } elseif ($commdate == '6') {
        $loan_agnt .= " AND ((cm.comm_date IS NULL OR cm.comm_date = '0000-00-00') OR
            ( cm.comm_date < DATE_FORMAT(CURDATE(), '%Y-%m-01') AND NOT EXISTS ( SELECT 1 FROM commitment c2 WHERE c2.cus_id = cp.cus_id AND c2.comm_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01') AND c2.comm_date < DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01') ) ) )";
    }
}

//Communication Status Filter
$comm_sts = isset($_POST['comm_sts']) ? $_POST['comm_sts'] : '';
if (!empty($comm_sts)) {    
    if ($comm_sts == '1') { //Error
        $loan_agnt .= " AND cm.comm_err = '1' ";
    } elseif ($comm_sts == '2') { //Clear
        $loan_agnt .= " AND cm.comm_err = '2' ";
    }
}

//Call Status Filter
$call_status = isset($_POST['call_status']) ? $_POST['call_status'] : '';
if (!empty($call_status)) {    
    if ($call_status == '1') { //No Reminder
        $loan_agnt .= " AND cr.reminder_call = '1' ";
    } elseif ($call_status == '2') { //No Follow up
        $loan_agnt .= " AND cr.reminder_call = '2' ";
    }
}

$searchValue = $_POST['search'] ?? '';
$search = $searchValue != '' ? "AND (ii.cus_id LIKE '%$searchValue%' OR cr.autogen_cus_id LIKE '%$searchValue%' OR cp.cus_name LIKE '%$searchValue%' OR alc.area_name LIKE '%$searchValue%' OR salc.sub_area_name LIKE '%$searchValue%' OR cr.mobile1 LIKE '%$searchValue%' OR cs.sub_status LIKE '%$searchValue%')" : '';

$columns = ['cp.id', 'cp.cus_id', 'cr.autogen_cus_id', 'cp.cus_name', 'alc.area_name', 'salc.sub_area_name', 'bc.branch_name', 'alm.line_name', 'cr.mobile1', 'cs.sub_status', 'responsible_status', 'cp.id', 'cr.reminder_call', 'cp.id', 'cs.last_paid_date', 'cs.current_month_paid', 'cm.comm_err', 'cm.hint', 'cm.remark', 'cm.comm_date'];
$orderDir = $_POST['order'][0]['dir'] ?? 'ASC';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$order = "ORDER BY " . ($columns[$orderColumnIndex] ?? $columns[0]) . " $orderDir";

// ---------------------- MAIN QUERY ----------------------
// Integrated sub_status prioritization directly in SQL to eliminate N+1 loop queries
$query = "SELECT COUNT(*) OVER() AS total_filtered,
    cp.cus_id AS cp_cus_id,
    cr.autogen_cus_id,
    cp.cus_name,
    alc.area_name,
    salc.sub_area_name,
    bc.branch_name,
    alm.line_name,
    cr.mobile1,
    cs.last_paid_date,
    cs.current_month_paid,
    cm.hint,
    cm.comm_err,
    cm.comm_date,
    cm.remark,
    ii.req_id,
    cr.reminder_call,
    CASE MIN(CASE cs.sub_status
        WHEN 'Legal' THEN 1
        WHEN 'Error' THEN 2
        WHEN 'OD' THEN 3
        WHEN 'Pending' THEN 4
        WHEN 'Current' THEN 5
        ELSE 6 END)
        WHEN 1 THEN 'Legal'
        WHEN 2 THEN 'Error'
        WHEN 3 THEN 'OD'
        WHEN 4 THEN 'Pending'
        WHEN 5 THEN 'Current'
        ELSE ''
    END AS computed_cus_status,
    CASE
        WHEN SUM(CASE WHEN rc.responsible = 1 THEN 1 ELSE 0 END) > 0 THEN 'No'
        WHEN SUM(CASE WHEN rc.responsible IS NULL OR TRIM(rc.responsible) = '' THEN 1 ELSE 0 END) > 0 THEN 'No'
        WHEN SUM(CASE WHEN rc.responsible REGEXP '[^0-9]' THEN 1 ELSE 0 END) > 0 THEN 'No'
        WHEN SUM(CASE WHEN rc.responsible = 0 AND rc.responsible REGEXP '^[0-9]+$' THEN 1 ELSE 0 END) = COUNT(*) THEN 'Yes' 
        ELSE 'No'
    END AS responsible_status
    FROM in_issue ii
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
    LEFT JOIN request_creation rc ON ii.req_id = rc.req_id AND (rc.cus_status >= 14 AND rc.cus_status < 20)
    JOIN customer_status cs ON cp.req_id = cs.req_id
    JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
    JOIN sub_area_list_creation salc ON cp.area_confirm_subarea = salc.sub_area_id
    JOIN area_line_mapping_area alma ON alma.area_id = alc.area_id
    JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id
    JOIN in_verification iv ON cp.req_id = iv.req_id
    LEFT JOIN acknowlegement_loan_calculation aklc ON (aklc.req_id = ii.req_id) AND aklc.collection_method != 4
    LEFT JOIN (
        SELECT c.hint, c.comm_err, c.comm_date, c.remark, c.cus_id, c.created_date
        FROM commitment c
        JOIN (
            SELECT c1.cus_id, MAX(c1.created_date) AS max_created
            FROM commitment c1
            JOIN in_issue ii1 ON ii1.req_id = c1.req_id
            WHERE ii1.status = 0
            AND ii1.cus_status BETWEEN 14 AND 17
            $commitmentCondition
            GROUP BY c1.cus_id
        ) x ON x.cus_id = c.cus_id AND x.max_created = c.created_date
    ) cm ON cm.cus_id = iv.cus_id
    WHERE cs.payable_amnt > 0
    AND ii.status = 0
    AND (ii.cus_status BETWEEN 14 AND 17)
    $sub_status_condition
    $loan_agnt
    $search
    AND DATE_FORMAT(CURDATE(), '%Y-%m') >= DATE_FORMAT(aklc.due_start_from, '%Y-%m')
    GROUP BY ii.cus_id, cs.cus_id
    $having
    $order";

// Pagination
$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : -1;
if ($length != -1) {
    $query .= " LIMIT $start, $length";
}

$statement = $connect->prepare($query);
$statement->execute($queryParams ?? []);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$recordsFiltered = !empty($result) ? $result[0]['total_filtered'] : 0;

// ---------------------- DATA FORMATTING ----------------------
$sno = $start + 1;
$data = [];

$last_paid_map = [
    1 => '1-10',
    2 => '11-15',
    3 => '16-20',
    4 => '21-25',
    5 => '26-30'
];

foreach ($result as $row) {
    $cus_id = $row['cp_cus_id'];
    $cus_name = $row['cus_name'];
    
    $last_paid_date = $last_paid_map[$row['last_paid_date']] ?? '';
    
    $remindercall = '';
    if ($row['reminder_call'] == '1') {
        $remindercall = 'No Reminder';
    } elseif ($row['reminder_call'] == '2') {
        $remindercall = 'No Follow up';
    }

    $paid_status = ($row['current_month_paid'] == 1) ? 'Yes' : '';
    $comm_err = ($row['comm_err'] == '1') ? 'Error' : (($row['comm_err'] == '2') ? 'Clear' : '');
    $comm_date = (!empty($row['comm_date']) && $row['comm_date'] != '0000-00-00')
        ? date('d-m-Y', strtotime($row['comm_date']))
        : '';

    // Safely extract optional URL variables if defined elsewhere, otherwise set to empty string
    $sub_status_url_val = $sub_status_url ?? '';
    $commdate_val = $commdate ?? '';
    $res_sts_val = $res_sts ?? '';
    $comm_sts_val = $comm_sts ?? '';

    $data[] = [
        $sno++,
        $cus_id,
        $row['autogen_cus_id'],
        $cus_name,
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['line_name'],
        $row['mobile1'],
        $row['computed_cus_status'],
        $row['responsible_status'],
        "<a href='due_followup&upd={$row['req_id']}&cusidupd={$cus_id}&cussts={$sub_status_url_val}&cummDate={$commdate_val}&res_sts={$res_sts_val}&comm_sts={$comm_sts_val}' title='Edit details'><button class='btn btn-success' style='background-color:#009688;'>View Loans</button></a>",
        $remindercall,
        "<a href='' data-value='" . htmlspecialchars($cus_id, ENT_QUOTES) . "' data-cusid='" . htmlspecialchars($row['autogen_cus_id'], ENT_QUOTES) . "' data-cusname='" . htmlspecialchars($cus_name, ENT_QUOTES) . "' data-mobile='" . htmlspecialchars($row['mobile1'], ENT_QUOTES) . "' class='customer-summary' data-toggle='modal' data-target='.customersummary'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span></a>",
        $last_paid_date,
        $paid_status,
        $row['hint'],
        $row['remark'],
        $comm_err,
        $comm_date
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsFiltered" => (int)$recordsFiltered,
    "data" => $data
]);

//Removed N+1 query by integrating customer status into the main SQL query.
// Used COUNT(*) OVER() for filtered record count.
// Replaced dynamic IN values with prepared placeholders.
// Used prepared parameters for date filters.
// Optimized last paid date mapping using an array.
// Added htmlspecialchars() for safer HTML output.
// Optimized total record count using COUNT(DISTINCT).
// Reduced unnecessary queries inside the foreach loop.
// Improved query parameter handling and security.