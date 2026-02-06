<?php
@session_start();
include('../../ajaxconfig.php');
include_once('../../api/config-file.php');

if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];
}

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
        $loan_agnt .= " AND iv.agent_id IN (" . implode(',', array_map('intval', explode(',', $rowuser['ag_id']))) . ")";
    }
}

// ---------------------- RESPONSIBLE HAVING BUILDER ----------------------
function buildResponsibleHaving($res_sts)
{
    $having = '';
    if ($res_sts !== '' && isset($res_sts)) {
        // Normalize empty-string handling in SQL by checking IS NULL OR = '' or string values
        if ($res_sts === '0') {
            // All must be 0 AND none empty/null/string -> show YES only
            $having = "HAVING 
                SUM(CASE WHEN rc.responsible = 0 THEN 1 ELSE 0 END) = SUM(CASE WHEN rc.req_id IS NOT NULL THEN 1 ELSE 0 END)
                AND SUM(CASE WHEN rc.responsible IS NULL OR rc.responsible = '' OR TRIM(rc.responsible) = '' THEN 1 ELSE 0 END) = 0
                AND SUM(CASE WHEN rc.responsible REGEXP '[^0-9]' THEN 1 ELSE 0 END) = 0";
        } elseif ($res_sts === '1') {
            // Any = 1 OR any NULL/empty OR any string -> show NO
            $having = "HAVING 
                SUM(CASE WHEN rc.responsible = 1 THEN 1 ELSE 0 END) > 0
                OR SUM(CASE WHEN rc.responsible IS NULL OR rc.responsible = '' OR TRIM(rc.responsible) = '' THEN 1 ELSE 0 END) > 0
                OR SUM(CASE WHEN rc.responsible REGEXP '[^0-9]' THEN 1 ELSE 0 END) > 0";
        }
    }
    return $having;
}

// read incoming res_sts (may be empty)
$res_sts = isset($_POST['res_sts']) ? trim($_POST['res_sts']) : '';
$having = buildResponsibleHaving($res_sts);

// ---------------------- other filters ----------------------
$current_date = date('Y-m-d');
$cus_sts = isset($_POST['cus_sts']) ? $_POST['cus_sts'] : [];
$sub_status_mapping = !empty($cus_sts) ? "'" . implode("','", $cus_sts) . "'" : '';
$sub_status_url = !empty($cus_sts) ? implode(',', $cus_sts) : '';

// Commitment Date Filter
$commdate = isset($_POST['comm_date']) && !empty($_POST['comm_date']) ? $_POST['comm_date'] : '';
$commitmentCondition = "";
if (!empty($commdate) && $commdate != 1) {
    $commitmentCondition = " AND (c1.comm_date IS NOT NULL AND c1.comm_date != '0000-00-00')";
}


if (isset($_POST['comm_date'])) {
    $comm_date = $_POST['comm_date']; // Get the comm_date from the form

    if ($comm_date == '2') { //Before Date
        $qry_cndtn = " AND cm.comm_date < '$current_date' AND (cm.comm_date IS NOT NULL OR cm.comm_date != '0000-00-00') ";
    } elseif ($comm_date == '3') { //Today
        $qry_cndtn = " AND cm.comm_date = '$current_date' ";
    } elseif ($comm_date == '4') { //After Date
        $qry_cndtn = " AND cm.comm_date > '$current_date' AND (cm.comm_date IS NOT NULL OR cm.comm_date != '0000-00-00') ";
    } elseif ($comm_date == '5') { //To Follow Date
        $qry_cndtn = " AND (cm.comm_date IS NULL OR cm.comm_date = '0000-00-00') ";
    } else {
        $qry_cndtn = "";
    }

    $loan_agnt .= $qry_cndtn;
}

$searchValue = $_POST['search'] ?? '';
$search = $searchValue != '' ? "AND (ii.cus_id LIKE '%$searchValue%' OR cr.autogen_cus_id LIKE '%$searchValue%' OR cp.cus_name LIKE '%$searchValue%' OR alc.area_name LIKE '%$searchValue%' OR salc.sub_area_name LIKE '%$searchValue%' OR cp.mobile1 LIKE '%$searchValue%' OR cs.sub_status LIKE '%$searchValue%')" : '';

$columns = ['cp.id', 'cp.cus_id', 'cr.autogen_cus_id', 'cp.cus_name', 'alc.area_name', 'salc.sub_area_name', 'bc.branch_name', 'alm.line_name', 'cp.mobile1', 'cs.sub_status', 'responsible_status', 'cp.id', 'cs.last_paid_date', 'cs.current_month_paid', 'cm.comm_err', 'cm.hint', 'cm.remark', 'cm.comm_date'];
$orderDir = $_POST['order'][0]['dir'] ?? 'ASC';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$order = "ORDER BY " . ($columns[$orderColumnIndex] ?? $columns[0]) . " $orderDir";

// ---------------------- MAIN QUERY ----------------------
$query = "SELECT
    cp.cus_id AS cp_cus_id,
    cr.autogen_cus_id,
    cp.cus_name,
    alc.area_name,
    salc.sub_area_name,
    bc.branch_name,
    alm.line_name,
    cp.mobile1,
    cs.last_paid_date,
    cs.current_month_paid,
    cm.hint,
    cm.comm_err,
    cm.comm_date,
    cm.remark,
    ii.req_id,
CASE
    -- if any responsible = 1 => NO
    WHEN SUM(CASE WHEN rc.responsible = 1 THEN 1 ELSE 0 END) > 0 THEN 'No'

    -- if any responsible is NULL or empty => NO
    WHEN SUM( CASE  WHEN rc.responsible IS NULL  OR rc.responsible = '' OR TRIM(rc.responsible) = '' THEN 1 ELSE 0  END ) > 0 THEN 'No'

    -- if any responsible is STRING (non-numeric) => NO
    WHEN SUM( CASE   WHEN rc.responsible REGEXP '[^0-9]' THEN 1 ELSE 0  END) > 0 THEN 'No'

    -- if ALL responsible = 0 => YES
    WHEN SUM( CASE WHEN rc.responsible = 0  AND rc.responsible REGEXP '^[0-9]+$' THEN 1 ELSE 0  END ) = COUNT(*) THEN 'Yes' ELSE 'No'
    END AS responsible_status

    FROM in_issue ii
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
    LEFT JOIN request_creation rc ON ii.req_id = rc.req_id AND rc.cus_status >= 14 AND rc.cus_status < 20
    JOIN customer_status cs ON cp.req_id = cs.req_id
    JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
    JOIN sub_area_list_creation salc ON cp.area_confirm_subarea = salc.sub_area_id
    JOIN area_line_mapping_area alma ON alma.area_id = alc.area_id
    JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id
    JOIN in_verification iv ON cp.req_id = iv.req_id
    LEFT JOIN acknowlegement_loan_calculation aklc ON aklc.req_id = ii.req_id AND aklc.collection_method = 4
    LEFT JOIN commitment cm ON cm.cus_id = cp.cus_id AND cm.created_date = (SELECT MAX(c1.created_date) FROM commitment c1 WHERE c1.cus_id = cp.cus_id $commitmentCondition)

    WHERE cs.payable_amnt > 0
    AND ii.status = 0
    AND ii.cus_status BETWEEN 14 AND 17
    AND cs.sub_status IN ($sub_status_mapping)
    $loan_agnt
    $search
    AND aklc.req_id IS NULL

    GROUP BY ii.cus_id, cs.cus_id
    $having
    $order";

// Pagination
$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
$length = isset($_POST['length']) ? (int)$_POST['length'] : -1;
if ($length != -1) {
    $query .= " LIMIT $start, $length";
}

// execute main query
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$sno = 1;
$data = [];
foreach ($result as $row) {
    $cus_id = $row['cp_cus_id'];
    $cus_name = $row['cus_name'];
    $area_name = $row['area_name'];
    $sub_area_name = $row['sub_area_name'];
    $branch_name = '';
    $comm_date = '';
    $hint = '';
    $comm_err = '';

    switch ($row['last_paid_date']) {
        case 1:
            $last_paid_date = '1-10';
            break;
        case 2:
            $last_paid_date = '11-15';
            break;
        case 3:
            $last_paid_date = '16-20';
            break;
        case 4:
            $last_paid_date = '21-25';
            break;
        case 5:
            $last_paid_date = '26-30';
            break;
        default:
            $last_paid_date = '';
            break;
    }

    $qry1 = $connect->query("SELECT 
        cus_id, 
        MIN(CASE 
            WHEN sub_status = 'Legal' THEN '1'
            WHEN sub_status = 'Error' THEN '2'
            WHEN sub_status = 'OD' THEN '3'
            WHEN sub_status = 'Pending' THEN '4'
            WHEN sub_status = 'Current' THEN '5'
            ELSE 6 
        END) AS status_priority
    FROM 
        customer_status
    WHERE 
        payable_amnt > 0 AND cus_id = '$cus_id'
    GROUP BY 
        cus_id
    ");

    // Check if any rows are returned
    if ($qry1->rowCount() > 0) {
        $row11 = $qry1->fetch();
        $status_priority = $row11['status_priority'];
        if ($status_priority == '1') {
            $cus_status = 'Legal';
        } else if ($status_priority == '2') {
            $cus_status = 'Error';
        } else if ($status_priority == '3') {
            $cus_status = 'OD';
        } else if ($status_priority == '4') {
            $cus_status = 'Pending';
        } else if ($status_priority == '5') {
            $cus_status = 'Current';
        } else {
            $cus_status = '';
        }
        // Close the cursor before running another query
        $qry1->closeCursor();
    }

    $branch_name = $row['branch_name'];
    $paid_status = ($row['current_month_paid'] == 1) ? 'Yes' : '';
    $hint = $row['hint'];
    $comm_err = ($row['comm_err'] == '1') ? 'Error' : (($row['comm_err'] == '2') ? 'Clear' : '');
    $comm_date = (!empty($row['comm_date']) && $row['comm_date'] != '0000-00-00')
        ? date('d-m-Y', strtotime($row['comm_date']))
        : '';

    $data[] = [
        $finalData['sno'] = $sno,
        $finalData['cus_id'] = $cus_id,
        $finalData['autogen_cus_id'] = $row['autogen_cus_id'],
        $finalData['cus_name'] = $cus_name,
        $finalData['area_name'] = $area_name,
        $finalData['sub_area_name'] = $sub_area_name,
        $finalData['branch_name'] = $branch_name,
        $finalData['line'] = $row['line_name'],
        $finalData['mobile'] = $row['mobile1'],
        $finalData['status_priority'] = $cus_status,
        $finalData['responsible_status'] = $row['responsible_status'],
        $finalData['action'] = "<a href='due_followup&upd={$row['req_id']}&cusidupd=$cus_id&cussts=$sub_status_url&cummDate=$commdate&res_sts=$res_sts' title='Edit details'><button class='btn btn-success' style='background-color:#009688;'>View Loans</button></a>",
        $finalData['last_paid_date'] = $last_paid_date,
        $finalData['paid_status'] = $paid_status,
        $finalData['hint'] = $hint,
        $finalData['remark'] = $row['remark'],
        $finalData['comm_err'] = $comm_err,
        $finalData['comm_dat'] = $comm_date
    ];
    $sno++;
}

// Step 3: Return the data in JSON format
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => getTotalRecords($connect),
    "recordsFiltered" => getFilteredRecords($connect, $data, $search, $sub_status_mapping, $loan_agnt, $commitmentCondition, $having),
    "data" => $data
]);

function getTotalRecords($connect)
{
    // Your database query to get the total number of records
    // For example:
    // $query = "SELECT COUNT(*) FROM customers";
    // Execute the query and return the result
    $query = $connect->query("SELECT COUNT(*) as total FROM (SELECT cp.cus_id as cp_cus_id FROM acknowlegement_customer_profile cp JOIN in_issue ii ON cp.cus_id = ii.cus_id where ii.status = 0 and (ii.cus_status >= 14 and ii.cus_status <= 17) GROUP BY ii.cus_id) as subquery ");
    $totals = $query->fetch()['total'];
    return $totals;
}

function getFilteredRecords($connect, $data, $search, $sub_status_mapping, $loan_agnt, $commitmentCondition, $having)
{
    // Your database query to get the total number of filtered records
    // For example:
    // $query = "SELECT COUNT(*) FROM customers WHERE ... LIKE '%$searchValue%'";
    // Execute the query and return the result
    if (count($data) > 0) {
        $query = $connect->query(" SELECT COUNT(*) as total FROM ( SELECT cp.cus_id
        FROM in_issue ii
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
    LEFT JOIN request_creation rc ON ii.req_id = rc.req_id AND rc.cus_status >= 14 AND rc.cus_status < 20
    JOIN customer_status cs ON cp.req_id = cs.req_id
    JOIN area_list_creation alc ON cp.area_confirm_area = alc.area_id
    JOIN sub_area_list_creation salc ON cp.area_confirm_subarea = salc.sub_area_id
    JOIN area_line_mapping_area alma ON alma.area_id = alc.area_id
    JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id
    JOIN in_verification iv ON cp.req_id = iv.req_id
    LEFT JOIN acknowlegement_loan_calculation aklc ON aklc.req_id = ii.req_id AND aklc.collection_method = 4
    LEFT JOIN commitment cm ON cm.cus_id = cp.cus_id AND cm.created_date = (SELECT MAX(c1.created_date) FROM commitment c1 WHERE c1.cus_id = cp.cus_id $commitmentCondition)

    WHERE cs.payable_amnt > 0
    AND ii.status = 0
    AND ii.cus_status BETWEEN 14 AND 17
    AND cs.sub_status IN ($sub_status_mapping)
    $loan_agnt
    $search
    AND aklc.req_id IS NULL

    GROUP BY ii.cus_id, cs.cus_id
    $having ) as subquery");

        $total = $query->fetch()['total'];

        return $total;
    } else {
        return 0;
    }
}

// Close the database connection
$connect = null;
