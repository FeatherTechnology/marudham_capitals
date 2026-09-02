<?php
@session_start();
include('../../ajaxconfig.php');

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}

$queryParams = [];
$loan_agnt = "";
$cus_sts = isset($_POST['cus_sts']) && is_array($_POST['cus_sts']) ? $_POST['cus_sts'] : [];
$sub_status_url = !empty($cus_sts) ? implode(',', array_map('urlencode', $cus_sts)) : '';

$branch_id =  '';
$line_id = '';
$followup_id = '';

if ($_POST['branch_id']) {
    $branch_id  = "AND bc.branch_id ='".$_POST['branch_id']."' ";
}
if ($_POST['line_id']) {
    $line_id = "AND alm.map_id ='".$_POST['line_id']."' ";
}
if ($_POST['followup_id']) {
    $followup_id = "AND adfm.map_id ='".$_POST['followup_id']."' ";
}

$sub_status_condition = "";
if (!empty($cus_sts)) {
    $placeholders = implode(',', array_fill(0, count($cus_sts), '?'));
    $sub_status_condition = " AND cs.sub_status IN ($placeholders)";
    foreach ($cus_sts as $status) {
        $queryParams[] = $status;
    }
}

if ($user_id != 1) {
    $stmt = $connect->prepare("SELECT due_followup_lines FROM user WHERE user_id = ?");
    $stmt->execute([(int)$user_id]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$rowuser) {
        exit;
    }
    $line_ids = array_filter(array_map('intval', explode(',', $rowuser['due_followup_lines'] ?? '')),
        fn($id) => $id > 0
    );
    if (empty($line_ids)) {
        exit;
    }
    $placeholders = implode(',', array_fill(0, count($line_ids), '?'));
    $loan_agnt .= " AND adfma.duefollowup_map_id IN ($placeholders)";
    $queryParams = array_merge($queryParams, $line_ids);
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

// Commitment Date Filter
$commdate = isset($_POST['comm_date']) && !empty($_POST['comm_date']) ? $_POST['comm_date'] : '';
$commitmentCondition = "";
if (!empty($commdate) && $commdate != 1) {
    $commitmentCondition = " AND (c.comm_date IS NOT NULL AND c.comm_date != '0000-00-00')";
}

// $cmCondition / $cmParams hold filters on the derived comm_date / comm_err columns —
// applied in the OUTER query against alias t.comm_date / t.comm_err (real columns, not JSON)
$cmCondition = "";
$cmParams = [];

if (!empty($commdate)) {
    if ($commdate == '2') {
        $cmCondition .= " AND t.comm_date < ? AND t.comm_date IS NOT NULL AND t.comm_date != '0000-00-00' ";
        $cmParams[] = $current_date;
    } elseif ($commdate == '3') {
        $cmCondition .= " AND t.comm_date = ? ";
        $cmParams[] = $current_date;
    } elseif ($commdate == '4') {
        $cmCondition .= " AND t.comm_date > ? AND t.comm_date IS NOT NULL AND t.comm_date != '0000-00-00' ";
        $cmParams[] = $current_date;
    } elseif ($commdate == '5') {
        $cmCondition .= " AND (t.comm_date IS NULL OR t.comm_date = '0000-00-00') ";
    } elseif ($commdate == '6') {
        $cmCondition .= " AND ((t.comm_date IS NULL OR t.comm_date = '0000-00-00') OR
            ( t.comm_date < DATE_FORMAT(CURDATE(), '%Y-%m-01') AND NOT EXISTS ( SELECT 1 FROM commitment c2 WHERE c2.cus_id = t.cp_cus_id AND c2.comm_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01') AND c2.comm_date < DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01') ) ) )";
    }
}

// Communication Status Filter (single block — no duplicate)
$comm_sts = isset($_POST['comm_sts']) ? $_POST['comm_sts'] : '';
if (!empty($comm_sts)) {
    if ($comm_sts == '1') { // Error
        $cmCondition .= " AND t.comm_err = '1' ";
    } elseif ($comm_sts == '2') { // Clear
        $cmCondition .= " AND t.comm_err = '2' ";
    }
}

// Call Status Filter — stays in the INNER query; cr is available there
$call_status = isset($_POST['call_status']) ? $_POST['call_status'] : '';
if (!empty($call_status)) {
    if ($call_status == '1') { // No Reminder
        $loan_agnt .= " AND cr.reminder_call = '1' ";
    } elseif ($call_status == '2') { // No Follow up
        $loan_agnt .= " AND cr.reminder_call = '2' ";
    }
}

// Search filter — parameterized (fixes SQL injection from raw concatenation)
$searchValue = $_POST['search'] ?? '';
$search = '';
if ($searchValue !== '') {
    $search = "AND (ii.cus_id LIKE ? OR cr.autogen_cus_id LIKE ? OR cp.cus_name LIKE ? OR cr.mobile1  LIKE ? OR cr.mobile2  LIKE ? OR cs.sub_status LIKE ?)";
    $likeVal = '%' . $searchValue . '%';
    $queryParams[] = $likeVal;
    $queryParams[] = $likeVal;
    $queryParams[] = $likeVal;
    $queryParams[] = $likeVal;
    $queryParams[] = $likeVal;
    $queryParams[] = $likeVal;
}

// Sort columns — t.comm_date / t.comm_err are REAL columns now (not JSON-extracted)
$columns = [
    't.cp_cus_id', 't.cp_cus_id', 't.autogen_cus_id', 't.cus_name', 't.mobile1','t.mobile2',
    't.cp_cus_id', 't.reminder_call', 't.cp_cus_id', 't.last_paid_date', 't.current_month_paid',
    't.comm_err', 't.cm_display', 't.cm_display', 't.comm_date'
];
$orderDir = $_POST['order'][0]['dir'] ?? 'ASC';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$order = "ORDER BY " . ($columns[$orderColumnIndex] ?? $columns[0]) . " $orderDir";

// ---------------------- Date condition (sargable rewrite) ----------------------
$dateCondition = "AND aklc.due_start_from < DATE_FORMAT(CURDATE() + INTERVAL 1 MONTH, '%Y-%m-01')";

// ---------------------- Reusable commitment correlation ----------------------
$commitmentCorrelation = "c.cus_id = cp.cus_id
    AND ii1.status = 0
    AND ii1.cus_status BETWEEN 14 AND 17
    $commitmentCondition";

$commitmentSubquery = "commitment c
        JOIN in_issue ii1 ON ii1.req_id = c.req_id
        WHERE $commitmentCorrelation
        ORDER BY c.created_date DESC LIMIT 1";

// ---------------------- INNER QUERY ----------------------
$innerQuery = "SELECT
    cp.cus_id AS cp_cus_id,
    cr.autogen_cus_id,
    cp.cus_name,
    cr.mobile1,
    cr.mobile2,
    cs.last_paid_date,
    cs.current_month_paid,
    ii.req_id,
    cr.reminder_call,
    cm.hint,
    cm.remark,
    cm.comm_date,
    cm.comm_err
    FROM in_issue ii
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
    LEFT JOIN request_creation rc ON ii.req_id = rc.req_id AND (rc.cus_status >= 14 AND rc.cus_status < 20)
    JOIN customer_status cs ON cp.req_id = cs.req_id
    JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = cp.area_confirm_area
    JOIN area_duefollowup_mapping adfm ON adfm.map_id = adfma.duefollowup_map_id
    JOIN acknowlegement_loan_calculation aklc ON (aklc.req_id = ii.req_id) AND aklc.collection_method != 4
    LEFT JOIN commitment cm ON cm.id = (
        SELECT c.id FROM $commitmentSubquery
    )
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = cr.area_confirm_subarea
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id
    WHERE cs.payable_amnt > 0
    AND ii.status = 0
    AND (ii.cus_status BETWEEN 14 AND 17)
    $sub_status_condition
    $loan_agnt
    $search
    $dateCondition
    $branch_id 
    $line_id
    $followup_id
    GROUP BY ii.cus_id, cs.cus_id
    $having";
// ---------------------- OUTER QUERY ----------------------
$query = "SELECT COUNT(*) OVER() AS total_filtered, t.*
    FROM ($innerQuery) t
    WHERE 1=1
    $cmCondition
    $order";

// $cmParams' placeholders live in the outer query, which runs after the inner
// query text closes — so they must be appended LAST, after all inner params.
$queryParams = array_merge($queryParams, $cmParams);

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

    $sub_status_url_val = $sub_status_url ?? '';
    $commdate_val = $commdate ?? '';
    $res_sts_val = $res_sts ?? '';
    $comm_sts_val = $comm_sts ?? '';

    $data[] = [
        $sno++,
        $cus_id,
        $row['autogen_cus_id'],
        $cus_name,
        $row['mobile1'],
        $row['mobile2'],
        "<a href='due_followup&upd={$row['req_id']}&cusidupd={$cus_id}&cussts={$sub_status_url_val}&cummDate={$commdate_val}&res_sts={$res_sts_val}&comm_sts={$comm_sts_val}' title='Edit details'><button class='btn btn-success' style='background-color:#009688;'>View Loans</button></a>",
        "<a href='#'class='personal-info'data-toggle='modal'data-target='#personalInfoModal'data-cusid='" . $cus_id . "'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></a>",
        "<a href='' data-value='" . $cus_id . "' data-cusid='" . $row['autogen_cus_id'] . "' data-cusname='" . $cus_name . "' data-mobile='" . $row['mobile1'] . "' class='customer-summary' data-toggle='modal' data-target='.customersummary'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span></a>",
        $last_paid_date,
        $paid_status,
        $row['hint'],
        $row['remark'],
        $comm_err,
        $remindercall,
        $comm_date
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsFiltered" => (int)$recordsFiltered,
    "data" => $data
]);