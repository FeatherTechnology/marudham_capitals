<?php
session_start();
include '../../ajaxconfig.php';

$userid = $_SESSION['userid'] ?? 0;
$draw   = intval($_POST['draw'] ?? 1);

$month_start = date('Y-m-01');
$taluk = isset($_POST['taluk']) ? $_POST['taluk'] : null;
$loan_cat = isset($_POST['loan_cat']) ? $_POST['loan_cat'] : null;


/* ================== USER FILTER ================== */
$user_filter = '';
if ($userid != 1) {
    $userQry = $connect->query("SELECT line_id, report_access FROM USER WHERE user_id = $userid");
    $userRow = $userQry->fetch(PDO::FETCH_ASSOC);

    if ($userRow && $userRow['report_access'] == '1') {
        $line_ids = explode(',', $userRow['line_id']);
        $sub_area_ids = [];
        foreach ($line_ids as $line) {
            $subQry = $connect->query("SELECT sub_area_id FROM area_line_mapping WHERE map_id = $line");
            $subRow = $subQry->fetch(PDO::FETCH_ASSOC);
            if (!empty($subRow['sub_area_id'])) {
                $sub_area_ids = array_merge($sub_area_ids, explode(',', $subRow['sub_area_id']));
            }
        }
        $sub_area_ids = array_unique(array_filter($sub_area_ids));
        if (!empty($sub_area_ids)) {
            $user_filter = " AND cp.area_confirm_subarea IN (" . implode(',', $sub_area_ids) . ")
                             AND coll.insert_login_id = '$userid' ";
        }
    }
}

/* ================== Eligible req_ids filter================== */
$qry = "
        SELECT req.req_id
        FROM request_creation req
        JOIN acknowlegement_customer_profile cp ON req.req_id = cp.req_id
        JOIN acknowlegement_loan_calculation lc ON req.req_id = lc.req_id
        JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        JOIN customer_status cs ON req.req_id = cs.req_id
        JOIN loan_issue li ON req.req_id = li.req_id
            AND DATE(li.created_date) < :month_start
            AND li.balance_amount = 0
        LEFT JOIN ( 
            SELECT req_id, MAX(created_date) AS last_collection_date  FROM collection  GROUP BY req_id ) coll ON req.req_id = coll.req_id
                WHERE req.cus_status BETWEEN 14 AND 18
                AND ( cs.sub_status != 'Due Nil'OR (cs.sub_status = 'Due Nil' AND coll.last_collection_date > :month_start)
        )
        AND (:taluk = 0 OR al.taluk = :taluk)
        AND (:loan_cat = 0 OR lc.loan_category = :loan_cat)

        UNION

        SELECT li.req_id
        FROM loan_issue li
        JOIN acknowlegement_loan_calculation lc ON li.req_id = lc.req_id
        JOIN acknowlegement_customer_profile cp ON li.req_id = cp.req_id
        JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        JOIN closing_customer cc ON li.req_id = cc.req_id

        LEFT JOIN (
                SELECT req_id, MAX(coll_date) AS max_coll_date FROM collection WHERE coll_date <= :month_start GROUP BY req_id
            ) lc2 ON li.req_id = lc2.req_id

        LEFT JOIN collection c  ON lc2.req_id = c.req_id
        AND lc2.max_coll_date = c.coll_date

        WHERE DATE(cc.closing_date) >= :month_start
              AND DATE(li.created_date) <= :month_start
              AND (c.req_id IS NULL OR (c.bal_amt - c.due_amt_track) > 0)
              AND li.balance_amount = 0
              AND (:taluk = 0 OR al.taluk = :taluk)
              AND (:loan_cat = 0 OR lc.loan_category = :loan_cat) ";


    
    $stmtReq = $connect->prepare($qry);
    $stmtReq->bindValue(':month_start', $month_start);
    $stmtReq->bindValue(':taluk', $taluk);
    $stmtReq->bindValue(':loan_cat', $loan_cat);
    $stmtReq->execute();

    $eligible_req_ids = $stmtReq->fetchAll(PDO::FETCH_COLUMN);

    if (empty($eligible_req_ids)) {
        $eligible_req_ids = [0]; // prevent empty IN()
    }


/* ================== MAIN QUERY ================== */
$sql = " SELECT
            al.area_id,
            al.area_name,
            al.taluk,
            GROUP_CONCAT(DISTINCT alm.line_name) AS line_names,
            GROUP_CONCAT(DISTINCT agm.group_name) AS group_names,
            ii.req_id,
            ii.cus_id,
            ii.loan_id,
            ii.updated_date,
            iv.cus_status,
            lc.due_start_from,
            lc.maturity_month,
            lc.due_amt_cal,
            lc.tot_amt_cal,
            lc.due_method_calc,
            lc.due_method_scheme,
            IFNULL(c.total_due_amt,0) AS total_due_amt
        FROM acknowlegement_loan_calculation lc
        JOIN acknowlegement_customer_profile cp ON lc.req_id = cp.req_id
        JOIN in_issue ii ON lc.req_id = ii.req_id
        JOIN in_verification iv ON lc.req_id = iv.req_id
        JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        LEFT JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)
        LEFT JOIN area_group_mapping agm ON FIND_IN_SET(al.area_id, agm.area_id)
        LEFT JOIN (
            SELECT req_id, SUM(due_amt_track) AS total_due_amt
            FROM collection
            WHERE DATE(coll_date) < '$month_start'
            GROUP BY req_id
        ) c ON lc.req_id = c.req_id
        WHERE lc.req_id IN (" . implode(',', $eligible_req_ids) . ") $user_filter
        GROUP BY lc.req_id
        ORDER BY al.area_id
        ";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ================== AREA AGGREGATION ================== */
$area_data = [];
$sno = 1;

foreach ($rows as $row) {
    $area_id = $row['area_id'];

    if (!isset($area_data[$area_id])) {
        $area_data[$area_id] = [
            'area_name' => $row['area_name'],
            'taluk' => $row['taluk'],
            'line_names' => $row['line_names'],
            'group_names' => $row['group_names'],
            'loan_ids' => [],
            'customer_ids' => [],
            'Current' => 0,
            'Pending' => 0,
            'OD' => 0,
            'Error' => 0,
            'Legal' => 0,
            'Due Nil' => 0
        ];
    }


    // Track unique IDs
    if (!in_array($row['req_id'], $area_data[$area_id]['loan_ids'])) {
        $area_data[$area_id]['loan_ids'][] = $row['req_id'];
    }
    if (!in_array($row['cus_id'], $area_data[$area_id]['customer_ids'])) {
        $area_data[$area_id]['customer_ids'][] = $row['cus_id'];
    }

    // Status calculations
    $loan_start  = strtotime($row['due_start_from']);
    $loan_end    = strtotime($row['maturity_month']);
    $search_date = strtotime($month_start);

    if ($loan_end < $search_date) {
        $months = (date('Y', $loan_end) - date('Y', $loan_start)) * 12 +
                  (date('m', $loan_end) - date('m', $loan_start)) + 1;
        $pending_month = $months;
        if ($row['due_method_calc'] == 'Monthly' || $row['due_method_scheme'] == '1') {
            if (date('Y-m', $search_date) == date('Y-m', $loan_end)) $pending_month--;
        }
    } else {
        $months = (date('Y', $search_date) - date('Y', $loan_start)) * 12 +
                  (date('m', $search_date) - date('m', $loan_start)) + 1;
        if ($row['due_method_calc'] != 'Monthly' && $row['due_method_scheme'] != '1') {
            if (date('d', $loan_start) < date('d', $search_date)) $months++;
        }
        $pending_month =  $months - 1;
    }

    $balance_amount = $row['tot_amt_cal'] - $row['total_due_amt'];
    $payable_amount = ($months * $row['due_amt_cal']) - $row['total_due_amt'];
    $pending_amount = ($pending_month * $row['due_amt_cal']) - $row['total_due_amt'];

    // ---- amounts ----
    $payable_amount = max(0, $payable_amount);
    $pending_amount = max(0, $pending_amount);

    // ---- STATUS DECISION (SAME AS DETAIL REPORT) ----
    if ($row['cus_status'] == '15' && strtotime($row['updated_date']) < strtotime($month_start)) {
        $area_data[$area_id]['Error']++;
    }

    else if ($row['cus_status'] == '16' && strtotime($row['updated_date']) < strtotime($month_start)) {
        $area_data[$area_id]['Legal']++;

    }else if ( $payable_amount <= $row['due_amt_cal'] && $pending_amount == 0
        && ( ( ($row['due_method_scheme'] === '1' || $row['due_method_calc'] === 'Monthly') && date('Y-m', strtotime($row['maturity_month'])) >= date('Y-m', strtotime($month_start)))
            || ( ($row['due_method_scheme'] != '1' || $row['due_method_calc'] != 'Monthly')&& strtotime($row['maturity_month']) >= strtotime($month_start))) && $balance_amount != 0) {

        $area_data[$area_id]['Current']++;
    }

    else if ( $pending_amount > 0
        && ( ( ($row['due_method_scheme'] === '1' || $row['due_method_calc'] === 'Monthly') && date('Y-m', strtotime($row['maturity_month'])) >= date('Y-m', strtotime($month_start)))
            || (($row['due_method_scheme'] != '1' || $row['due_method_calc'] != 'Monthly') && strtotime($row['maturity_month']) > strtotime($month_start)))) {

        $area_data[$area_id]['Pending']++;
    }
    else if ( $balance_amount > 0 && (
            (($row['due_method_scheme'] === '1' || $row['due_method_calc'] === 'Monthly')&& date('Y-m', strtotime($row['maturity_month'])) < date('Y-m', strtotime($month_start)))
            || ( ($row['due_method_scheme'] != '1' || $row['due_method_calc'] != 'Monthly')&& strtotime($row['maturity_month']) < strtotime($month_start)))) {

        $area_data[$area_id]['OD']++;
    }

}

/* ==================
   RESPONSE
================== */
$data = [];
foreach ($area_data as $area) {
    $data[] = [
        "sno" => $sno++,
        "area_name" => $area['area_name'],
        "taluk" => $area['taluk'],
        "line_names" => $area['line_names'],
        "group_names" => $area['group_names'],
        "customer_count" => count($area['customer_ids']),
        "loan_count" => count($area['loan_ids']),
        "Current" => $area['Current'],
        "Pending" => $area['Pending'],
        "OD" => $area['OD'],
        "Error" => $area['Error'],
        "Legal" => $area['Legal'],
        "Due Nil" => $area['Due Nil']
    ];
}
$recordsTotal = count($data);   // ✅ total area rows
$searchValue = trim($_POST['search'] ?? '');

if ($searchValue !== '') {
    $searchValue = strtolower($searchValue);

    $data = array_filter($data, function ($row) use ($searchValue) {
        return
            strpos(strtolower($row['area_name']), $searchValue) !== false ||
            strpos(strtolower($row['taluk']), $searchValue) !== false ||
            strpos(strtolower($row['line_names']), $searchValue) !== false ||
            strpos(strtolower($row['group_names']), $searchValue) !== false;
    });

    // update filtered count
    $recordsFiltered = count($data);
} else {
    $recordsFiltered = $recordsTotal;
}


$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);

if ($length != -1) {
    $data = array_slice($data, $start, $length);
}

function count_all_data($connect)
{
    $query = $connect->query("
        SELECT COUNT(area_id) AS count_result
        FROM area_list_creation
        WHERE area_enable = 0
          AND status = 0
    ");
    $statement = $query->fetch(PDO::FETCH_ASSOC);
    return (int)$statement['count_result'];
}


echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => array_values($data)
]);




