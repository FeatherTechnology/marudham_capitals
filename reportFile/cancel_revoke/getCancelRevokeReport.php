<?php
session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT report_access FROM user WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') { //Report access individual.
        $user_based = "AND (req.update_login_id = '$userid' || req.insert_login_id = '$userid') ";
    }
}

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d 00:00:00', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d 23:59:59', strtotime($_POST['to_date']));
    $where = "req.updated_date BETWEEN '$from_date' AND '$to_date'";
}

$where  .= $user_based;

$cus_status = "";
$join_condition = "";
$ag_join = "req.agent_id";
$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];

// Check if type and sel_screen are selected by the user
if (isset($_POST['type']) && isset($_POST['sel_screen'])) {
    $type = $_POST['type'];
    $sel_screen = $_POST['sel_screen'];

    // Determine cus_status based on type and sel_screen
    if ($type == '1') { // '1' for Cancel
        switch ($sel_screen) {
            case '1':
                $cus_status = '4'; // Request
                break;
            case '2':
                $cus_status = '5'; // Verification
                break;
            case '3':
                $cus_status = '6'; // Approval
                break;
            case '4':
                $cus_status = '7'; // Acknowledgement
                break;
        }
    } elseif ($type == '2') { // '2' for Revoke
        switch ($sel_screen) {
            case '1':
                $cus_status = '8'; // Request
                break;
            case '2':
                $cus_status = '9'; // Verification
                break;
        }
    }
}   // Append the cus_status condition if it's set

if ($cus_status != "") {
    // Updated WHERE clause
    $where .= " AND req.cus_status = '$cus_status' ";
} else {
    $where .= " AND req.cus_status BETWEEN 4 AND 9 ";
}

$loan_amt_field = "req.loan_amt"; // default

if ($cus_status == '5' || $cus_status == '6' || $cus_status == '7' || $cus_status == '9') {
    $join_condition = "JOIN in_verification iv ON req.req_id = iv.req_id";
    $ag_join = "iv.agent_id";
    $loan_amt_field = "iv.loan_amt";
}

$statusLabels = [
    '0' => "In Request",
    '1' => 'In Verification',
    '2' => 'In Approval',
    '3' => 'In Acknowledgement',
    '4' => 'Cancel - Request',
    '5' => 'Cancel - Verification',
    '6' => 'Cancel - Approval',
    '7' => 'Cancel - Acknowledgement',
    '8' => 'Revoke - Request',
    '9' => 'Revoke - Verification',
    '10' => 'In Verification',
    '11' => 'In Verification',
    '12' => 'In Verification',
    '13' => 'In Issue',
    '14' => 'Present',
    '15' => 'Collection Error',
    '16' => 'Collection Legal',
    '17' => 'Present',
    '20' => 'Closed',
    '21' => 'NOC Pending',
    '22' => 'NOC Completed',
    '23' => 'NOC Completed',
    '24' => 'NOC Handovered',
    '25' => 'Agent Handovered'
];

$column = array(
    'req.req_id',
    'req.req_code',
    'req.dor',
    'req.cus_id',
    'cr.autogen_cus_id',
    'req.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'alm.line_name',
    'agm.group_name',
    'bc.branch_name',
    'lcc.loan_category_creation_name',
    'req.sub_category',
    'loan_amt',
    'u.role',
    'u.fullname',
    'ag.ag_name',
    'req.responsible',
    'req.cus_data',
    'req.req_id',
    'req.updated_date',
    'req.cus_status',
    'req.prompt_remark'
);

$query = "SELECT 
    req.req_code,
    req.dor,
    req.cus_id,
    cr.autogen_cus_id,
    req.cus_name,
    al.area_name,
    sal.sub_area_name,
    agm.group_name,
    alm.line_name,
    bc.branch_name,
    lcc.loan_category_creation_name,
    req.sub_category,
    $loan_amt_field AS loan_amt,
    u.role,
    u.fullname,
    ag.ag_name,
    req.responsible,
    req.cus_data,
    us.role AS cancel_by_role,
    us.fullname AS cancel_by_fullname,
    req.updated_date,
    req.cus_status,
    req.prompt_remark
FROM 
    request_creation req 
$join_condition
JOIN 
    customer_register cr ON req.cus_id = cr.cus_id
JOIN 
    area_list_creation al ON req.area = al.area_id
JOIN 
    sub_area_list_creation sal ON req.sub_area = sal.sub_area_id
JOIN 
    loan_category_creation lcc ON req.loan_category = lcc.loan_category_creation_id
LEFT JOIN 
    agent_creation ag ON $ag_join = ag.ag_id         
JOIN 
    user u ON req.insert_login_id = u.user_id
JOIN 
    user us ON req.update_login_id = us.user_id
JOIN 
    area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sal.sub_area_id
JOIN 
    area_group_mapping agm ON agm.map_id = agmsa.group_map_id
LEFT JOIN 
    branch_creation bc ON agm.branch_id = bc.branch_id
JOIN 
    area_line_mapping_sub_area almsa ON almsa.sub_area_id = sal.sub_area_id
JOIN 
    area_line_mapping alm ON alm.map_id = almsa.line_map_id
WHERE 
    $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " AND (req.cus_id LIKE '%" . $_POST['search'] . "%' OR
                cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' OR
                req.cus_name LIKE '%" . $_POST['search'] . "%' OR
                al.area_name LIKE '%" . $_POST['search'] . "%' OR
                sal.sub_area_name LIKE '%" . $_POST['search'] . "%' OR
                u.role LIKE '%" . $_POST['search'] . "%' OR
                u.fullname LIKE '%" . $_POST['search'] . "%' OR
                lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%' OR
                req.sub_category LIKE '%" . $_POST['search'] . "%' OR
                ag.ag_name LIKE '%" . $_POST['search'] . "%' OR
                req.cus_data LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
$cusIds = array_unique(array_column($result, 'cus_id'));

$historyDataMap = [];

if (!empty($cusIds)) {

    $cusIdList = implode(',', array_map('intval', $cusIds));

    $historySql = "
    SELECT
        rc.cus_id,
        rc.req_id,
        rc.dor,
        rc.cus_status,
        cs.created_date AS closed_date,
        cc.closing_date,
        cs1.sub_status,
        dn.due_nil_date
    FROM request_creation rc
    LEFT JOIN closed_status cs
        ON cs.req_id = rc.req_id
    LEFT JOIN closing_customer cc
        ON cc.req_id = rc.req_id
    LEFT JOIN customer_status cs1
        ON cs1.req_id = rc.req_id
    LEFT JOIN (
        SELECT req_id, MAX(coll_date) due_nil_date
        FROM collection
        WHERE coll_sub_status='Due Nil'
        GROUP BY req_id
    ) dn
        ON dn.req_id = rc.req_id
    WHERE rc.cus_id IN ($cusIdList)
      AND rc.cus_status NOT IN (4,5,6,7,8,9)
    ORDER BY rc.cus_id, rc.req_id DESC";

    $stmt = $connect->query($historySql);

    while ($his = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $historyDataMap[$his['cus_id']][] = $his;
    }
}
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;
    $sub_array[] = $row['req_code'];
    $sub_array[] = date('d-m-Y', strtotime($row['dor']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['loan_category_creation_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = $role_arr[$row['role']];
    $sub_array[] = $row['fullname'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = ($row['responsible'] == '0') ? 'Yes' : 'No';
    $sub_array[] = $row['cus_data'];

    $cus_id = $row['cus_id'];
    $currentDor = $row['dor'];

    $existing_type = '';
    if ($row['cus_data'] != 'New') {
        $currentDor = $row['dor'];
        $historyRows = $historyDataMap[$row['cus_id']] ?? [];
        $issue = null;
        foreach ($historyRows as $his) {

            if ($his['dor'] < $currentDor) {
                $issue = $his;       // Latest previous loan because sorted DESC
                break;
            }
        }
        if (!$issue) {
            $existing_type = 'Existing-New';
        } else {
            $status = (int)$issue['cus_status'];
            $closedDate  = $issue['closed_date'];
            $closingDate = $issue['closing_date'];
            $dueNilDate  = $issue['due_nil_date'];

            if (
                $issue['sub_status'] == 'Due Nil' ||
                (!empty($dueNilDate) && $currentDor <= $dueNilDate)
            ) {

                $existing_type = 'Reloan';
            } elseif (
                (
                    !empty($closedDate) &&
                    !empty($closingDate) &&
                    $currentDor >= $closingDate &&
                    $currentDor <= $closedDate
                ) ||
                $status == 20
            ) {

                $existing_type = 'Reloan';
            } elseif (
                !empty($closingDate) &&
                $currentDor < $closingDate
            ) {

                $existing_type = 'Additional';
            } elseif (
                $status >= 14 &&
                $status < 20
            ) {

                $existing_type = 'Additional';
            } elseif (!empty($closingDate)) {

                $monthEnd = date('Y-m-t', strtotime($closingDate));
                $nextMonth = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
                $reactiveDate = date('Y-m-d', strtotime($nextMonth . ' +6 months'));

                $existing_type = ($currentDor < $reactiveDate)
                    ? 'Renewal'
                    : 'Re-active';
            } else {

                $existing_type = 'Existing-New';
            }
        }
    }
    $sub_array[] = $existing_type;
    $sub_array[] = $role_arr[$row['cancel_by_role']];
    $sub_array[] = $row['cancel_by_fullname'];
    $sub_array[] = date('d-m-Y', strtotime($row['updated_date']));
    $sub_array[] = $statusLabels[$row['cus_status']];
    $sub_array[] = $row['prompt_remark'];

    $data[]      = $sub_array;
    $sno = $sno + 1;
}
$output = array(
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
