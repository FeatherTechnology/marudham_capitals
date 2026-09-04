<?php
session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';
$userid = $_SESSION["userid"] ?? null;
$to_date = !empty($_POST['to_date']) ? date('Y-m-d', strtotime($_POST['to_date'])) : date('Y-m-d');

// Pre-calculate date timestamps for faster loop execution
$to_date_time = strtotime($to_date);
$to_date_dt   = new DateTime($to_date);
$to_date_ym   = $to_date_dt->format('Y-m');

$params = [':to_date' => $to_date];
$user_based_req = '';

if ($userid && $userid != 1) {
    $userQry = $connect->prepare("SELECT report_access FROM USER WHERE user_id = ?");
    $userQry->execute([$userid]);
    $user = $userQry->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['report_access'] == '1') {
        $user_based_req = " AND req.insert_login_id = :userid ";
        $params[':userid'] = $userid;
    }
}

// DataTables column mapping
$column = [
    0  => 'ii.loan_id',
    1  => 'alm.line_name',
    2  => 'ii.loan_id',
    3  => 'ad.doc_id',
    4  => 'ii.updated_date',
    5  => 'lc.maturity_month',
    6  => 'cp.cus_id',
    7  => 'cr.autogen_cus_id',
    8  => 'cp.cus_name',
    9  => 'al.area_name',
    10 => 'sal.sub_area_name',
    11 => 'lcc.loan_category_creation_name',
    12 => 'lc.sub_category'
];

$where_filters = "";

if (!empty($_POST['loan_cat']) && is_array($_POST['loan_cat'])) {
    $cat_placeholders = [];
    foreach ($_POST['loan_cat'] as $idx => $cat) {
        $ph = ":cat_$idx";
        $cat_placeholders[] = $ph;
        $params[$ph] = $cat;
    }
    $where_filters .= " AND lcc.loan_category_creation_id IN (" . implode(',', $cat_placeholders) . ") ";
}

if (!empty($_POST['search'])) {
    $where_filters .= " AND (
        alm.line_name LIKE :search OR
        ii.loan_id LIKE :search OR
        ad.doc_id LIKE :search OR
        cp.cus_id LIKE :search OR
        cr.autogen_cus_id LIKE :search OR
        cp.cus_name LIKE :search OR
        al.area_name LIKE :search OR
        sal.sub_area_name LIKE :search
    )";
    $params[':search'] = '%' . $_POST['search'] . '%';
}

$order_clause = "";
$orderColumn = $_POST['order'][0]['column'] ?? null;
$orderDir = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'desc') ? 'DESC' : 'ASC';
if ($orderColumn !== null && isset($column[$orderColumn])) {
    $order_clause = " ORDER BY " . $column[$orderColumn] . " " . $orderDir;
}

// Highly-optimized Query with scoped aggregation subqueries
$main_query = "
    WITH req_scope AS (
        SELECT req.req_id 
        FROM request_creation req
        JOIN loan_issue li ON req.req_id = li.req_id 
            AND DATE(li.created_date) <= :to_date 
            AND li.balance_amount = '0'
        WHERE req.cus_status BETWEEN 14 AND 18 $user_based_req

        UNION

        SELECT cc.req_id 
        FROM closing_customer cc 
        JOIN loan_issue li ON cc.req_id = li.req_id 
        WHERE DATE(cc.closing_date) > :to_date 
          AND DATE(li.created_date) <= :to_date
    )
    SELECT 
        alm.line_name AS line,
        ii.loan_id,
        ad.doc_id,
        ii.updated_date AS loan_date,
        lc.maturity_month,
        cp.cus_id,
        cr.autogen_cus_id,
        cp.req_id,
        cp.cus_name,
        al.area_name,
        sal.sub_area_name,
        lcc.loan_category_creation_name AS loan_cat_name,
        lc.sub_category,
        ac.ag_name,
        lc.loan_amt_cal,
        lc.due_amt_cal,
        lc.principal_amt_cal,
        lc.int_amt_cal,
        lc.tot_amt_cal,
        lc.due_type,
        lc.due_period,
        COALESCE(c.due_amt_track, 0) AS due_amt_track,
        COALESCE(c.princ_amt_track, 0) AS princ_amt_track,
        COALESCE(c.int_amt_track, 0) AS int_amt_track,
        COALESCE(p.total_penalty, 0) AS penalty, 
        COALESCE(ch.total_fine, 0) AS fine, 
        COALESCE(c.penalty_track, 0) AS penalty_track, 
        COALESCE(c.fine_track, 0) AS fine_track,
        COALESCE(c.penalty_waiver, 0) AS penalty_waiver,
        COALESCE(c.fine_waiver, 0) AS fine_waiver,
        iv.cus_status,
        ack.updated_date,
        lc.due_start_from,
        lc.due_method_scheme,
        lc.due_method_calc,
        lc.maturity_month AS maturity_date
    FROM req_scope scope
    JOIN acknowlegement_loan_calculation lc ON scope.req_id = lc.req_id
    JOIN customer_register cr ON lc.cus_id_loan = cr.cus_id
    JOIN acknowlegement_customer_profile cp ON lc.req_id = cp.req_id
    JOIN acknowlegement_documentation ad ON lc.req_id = ad.req_id
    JOIN in_issue ii ON lc.req_id = ii.req_id
    JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
    JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
    JOIN area_line_mapping_sub_area almsa ON sal.sub_area_id = almsa.sub_area_id
    JOIN area_line_mapping alm ON almsa.line_map_id = alm.map_id
    JOIN in_verification iv ON lc.req_id = iv.req_id
    JOIN in_acknowledgement ack ON ack.req_id = iv.req_id
    LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
    LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id
    LEFT JOIN ( 
        SELECT 
            col.req_id, 
            SUM(col.due_amt_track) AS due_amt_track, 
            SUM(col.princ_amt_track) AS princ_amt_track, 
            SUM(col.int_amt_track) AS int_amt_track, 
            SUM(col.penalty_track) AS penalty_track, 
            SUM(col.coll_charge_track) AS fine_track, 
            SUM(col.penalty_waiver) AS penalty_waiver, 
            SUM(col.coll_charge_waiver) AS fine_waiver 
        FROM collection col
        JOIN req_scope rs ON col.req_id = rs.req_id
        WHERE DATE(col.coll_date) <= :to_date 
        GROUP BY col.req_id 
    ) c ON c.req_id = lc.req_id 
    LEFT JOIN ( 
        SELECT pc.req_id, SUM(pc.penalty) AS total_penalty 
        FROM penalty_charges pc
        JOIN req_scope rs ON pc.req_id = rs.req_id
        WHERE DATE(pc.created_date) <= :to_date 
        GROUP BY pc.req_id 
    ) p ON p.req_id = lc.req_id 
    LEFT JOIN ( 
        SELECT cc.req_id, SUM(cc.coll_charge) AS total_fine 
        FROM collection_charges cc
        JOIN req_scope rs ON cc.req_id = rs.req_id
        WHERE DATE(cc.created_date) <= :to_date 
        GROUP BY cc.req_id 
    ) ch ON ch.req_id = lc.req_id
    WHERE lc.due_type != 'Interest' $where_filters
    $order_clause
";

// Count records
$stmt = $connect->prepare($main_query);
$stmt->execute($params);
$number_filter_row = $stmt->rowCount();

// Pagination
if (!isset($_POST['download'])) {
    $start  = isset($_POST['start']) ? (int)$_POST['start'] : 0;
    $length = isset($_POST['length']) ? (int)$_POST['length'] : -1;
    if ($length != -1) {
        $main_query .= " LIMIT $start, $length";
        $stmt = $connect->prepare($main_query);
        $stmt->execute($params);
    }
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lightweight Array Construction
$data = [];
$sno = isset($_POST['start']) ? (int)$_POST['start'] + 1 : 1;

foreach ($result as $row) {
    $start_dt    = new DateTime($row['due_start_from']);
    $maturity_dt = new DateTime($row['maturity_date']);

    if ($maturity_dt < $to_date_dt) {
        $end_dt = $maturity_dt;
        $diff   = $start_dt->diff($end_dt);
        $pending_month = ($diff->y * 12) + $diff->m;
    } else {
        $end_dt = $to_date_dt;
        $diff   = $start_dt->diff($end_dt);
        $pending_month = max(0, ($diff->y * 12) + $diff->m);
    }

    $months = (($diff->y * 12) + $diff->m) + 1;

    $due_amt_cal  = (float)$row['due_amt_cal'];
    $due_amt_trck = (float)$row['due_amt_track'];
    $paid_due     = $due_amt_cal > 0 ? ($due_amt_trck / $due_amt_cal) : 0;
    
    $balance_due    = (float)$row['due_period'] - $paid_due;
    $payable_amount = max(0, ($months * $due_amt_cal) - $due_amt_trck);
    $pending_amount = max(0, ($pending_month * $due_amt_cal) - $due_amt_trck);

    $balance_amount = ($row['due_type'] !== 'Interest') ?
        ((float)$row['tot_amt_cal'] - $due_amt_trck) :
        ((float)$row['principal_amt_cal'] - (float)$row['princ_amt_track']);

    $penalty = (float)$row['penalty'] - ((float)$row['penalty_track'] + (float)$row['penalty_waiver']);
    $fine    = (float)$row['fine'] - ((float)$row['fine_track'] + (float)$row['fine_waiver']);

    // Status Determination
    $updated_time = strtotime($row['updated_date']);
    $is_monthly_or_scheme = ($row['due_method_scheme'] === '1' || $row['due_method_calc'] === 'Monthly');
    $maturity_ym = $maturity_dt->format('Y-m');
    $maturity_time = $maturity_dt->getTimestamp();

    if ($row['cus_status'] == '15' && $updated_time < $to_date_time) {
        $status = 'Error';
    } else if ($row['cus_status'] == '16' && $updated_time < $to_date_time) {
        $status = 'Legal';
    } else if ($payable_amount == 0 && $pending_amount == 0 && $balance_amount == 0) {
        $status = 'Due Nil';
    } else if (
        $payable_amount <= $due_amt_cal && 
        $pending_amount == 0 && 
        (($is_monthly_or_scheme && $maturity_ym >= $to_date_ym) || (!$is_monthly_or_scheme && $maturity_time >= $to_date_time)) && 
        $balance_amount != 0
    ) {
        $status = 'Current';
    } else if (
        $pending_amount > 0 && 
        (($is_monthly_or_scheme && $maturity_ym >= $to_date_ym) || (!$is_monthly_or_scheme && $maturity_time > $to_date_time))
    ) {
        $status = 'Pending';
    } else if (
        $balance_amount > 0 && 
        (($is_monthly_or_scheme && $maturity_ym < $to_date_ym) || (!$is_monthly_or_scheme && $maturity_time < $to_date_time))
    ) {
        $status = 'OD';
    } else {
        $status = 'No Result';
    }

    $data[] = [
        $sno++,
        $row['line'],
        $row['loan_id'],
        $row['doc_id'],
        date('d-m-Y', strtotime($row['loan_date'])),
        date('d-m-Y', strtotime($row['maturity_month'])),
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['cus_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['loan_cat_name'],
        $row['sub_category'],
        $row['ag_name'],
        moneyFormatIndia($row['loan_amt_cal']),
        moneyFormatIndia($row['due_amt_cal']),
        $row['due_period'],
        moneyFormatIndia($row['tot_amt_cal']),
        moneyFormatIndia($balance_amount),
        floor($balance_due * 100) / 100,
        moneyFormatIndia($penalty),
        moneyFormatIndia($fine),
        'Present',
        $status
    ];
}

echo json_encode([
    'draw'            => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
    'recordsFiltered' => $number_filter_row,
    'data'            => $data
]);