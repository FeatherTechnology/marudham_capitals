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
        $user_based = " AND req.insert_login_id = '$userid' ";
    }
}

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d 00:00:00', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d 23:59:59', strtotime($_POST['to_date']));
    $where = "req.dor BETWEEN '$from_date' AND '$to_date'";
}

$where  .= $user_based;

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
];

/* ---------- Column List ---------- */
$column = [
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
    'req.loan_amt',
    'req.user_type',
    'req.user_name',
    'req.req_id',
    'req.responsible',
    'req.cus_data',
    'req.req_id',
    'req.req_id',
    'cs.sub_status'
];

/* ---------- Base Query ---------- */
$baseQuery = "FROM request_creation req 
            JOIN customer_register cr ON req.cus_id = cr.cus_id
            JOIN area_list_creation al ON req.area = al.area_id
            JOIN sub_area_list_creation sal ON req.sub_area = sal.sub_area_id
            JOIN loan_category_creation lcc ON req.loan_category = lcc.loan_category_creation_id
            LEFT JOIN agent_creation ag ON req.agent_id = ag.ag_id
            LEFT JOIN customer_status cs ON req.req_id = cs.req_id
            JOIN area_group_mapping_area agma ON agma.area_id = al.area_id
            JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
            LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
            JOIN area_line_mapping_area alma ON alma.area_id = al.area_id
            JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
            WHERE $where";

/* ---------- Search ---------- */
if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $baseQuery .= " and (req.cus_id LIKE '%" . $_POST['search'] . "%' OR
                cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' OR
                req.cus_name LIKE '%" . $_POST['search'] . "%' OR
                al.area_name LIKE '%" . $_POST['search'] . "%' OR
                sal.sub_area_name LIKE '%" . $_POST['search'] . "%' OR
                agm.group_name LIKE '%" . $_POST['search'] . "%' OR
                alm.line_name LIKE '%" . $_POST['search'] . "%' OR
                bc.branch_name LIKE '%" . $_POST['search'] . "%' OR
                lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%' OR
                req.sub_category LIKE '%" . $_POST['search'] . "%' OR
                req.user_type LIKE '%" . $_POST['search'] . "%' OR
                req.user_name LIKE '%" . $_POST['search'] . "%' OR
                req.cus_data LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

/* ---------- ORDER ---------- */
$orderBy = '';
if (isset($_POST['order'])) {
    $orderBy = " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
}

/* ---------- Pagination ---------- */
$limit = '';
if (!isset($_POST['download'])) {
    if ($_POST['length'] != -1) {
        $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
    }
}

/* ---------- Total records ---------- */
$totalStmt = $connect->prepare("SELECT COUNT(*) FROM request_creation");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute();
$recordsFiltered = (int) $countStmt->fetchColumn();

/* ---------- Data query ---------- */
$dataQuery = "SELECT 
        req.req_id,
        req.req_code,
        req.dor,
        req.cus_id,
        cr.autogen_cus_id,
        req.cus_name,
        al.area_name,
        sal.sub_area_name,
        req.sub_category,
        agm.group_name,
        alm.line_name,
        bc.branch_name,
        req.loan_amt,
        req.user_type,
        req.user_name,
        lcc.loan_category_creation_name,
        ag.ag_name,
        req.cus_data,
        cs.sub_status,
        req.cus_status,
        req.responsible
        $baseQuery
        $orderBy
        $limit
    ";

$statement = $connect->prepare($dataQuery);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $sub_array[] = $sno++;
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
    $sub_array[] = $row['user_type'];
    $sub_array[] = $row['user_name'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = (!empty($row['ag_name'])) ? (($row['responsible'] == '0') ? 'Yes' : 'No') : '';
    $sub_array[] = $row['cus_data'];

    $cus_id = $row['cus_id'];
    $dor = $row['dor'];

    if (($row['cus_data']) === 'New') {

        $existing_type = '';
    } else {

        $stmt = $connect->prepare("SELECT rc.cus_status, rc.dor, cc.closing_date
            FROM request_creation rc
            LEFT JOIN closing_customer cc ON cc.req_id = rc.req_id
            WHERE rc.cus_id = :cus_id
            AND rc.dor < :dor AND rc.cus_status NOT IN (4,5,6,7,8,9)
            ORDER BY rc.dor DESC
            LIMIT 1 
        ");

        $stmt->execute([
            ':cus_id' => $cus_id,
            ':dor' => $dor
        ]);

        $issue = $stmt->fetch();

        if (!$issue) {

            $existing_type = 'Existing New';
        } elseif ($issue['cus_status'] >= 14 && $issue['cus_status'] < 20) {

            $existing_type = 'Additional';
        } else {

            $dor = date('Y-m-d', strtotime($dor));
            $closingDate = date('Y-m-d', strtotime($issue['closing_date']));
            $monthEnd = date('Y-m-t', strtotime($issue['closing_date']));
            $nextMonth = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
            $reactiveDate = date('Y-m-d', strtotime($nextMonth . ' +6 months'));

            if ($closingDate > $dor) {

                $existing_type = 'Additional';
            } else {

                if ($reactiveDate > $dor) {
                    $existing_type = 'Renewal';
                } else {
                    $existing_type = 'Re-active';
                }
            } 
        }
    }

    $sub_array[] = $existing_type;
    $sub_array[] = $statusLabels[$row['cus_status']];
    $sub_array[] = (in_array($row['cus_status'], [14, 15, 16, 17])) ? $row['sub_status'] : '';

    $data[]      = $sub_array;
}

$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0, // ✅ safe for both table & download
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;