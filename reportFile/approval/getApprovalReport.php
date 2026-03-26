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
        $user_based = " AND ic.inserted_user = '$userid' ";
    }
}

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "ic.inserted_date >= '" . $from_date . "' and ic.inserted_date <= '" . $to_date . "' ";
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

$column = array(
    'alc.req_id',
    'req.req_code',
    'ic.inserted_date',
    'alc.cus_id_loan',
    'cr.autogen_cus_id',
    'alc.cus_name_loan',
    'al.area_name',
    'sal.sub_area_name',
    'alm.line_name',
    'agm.group_name',
    'bc.branch_name',
    'lcc.loan_category_creation_name',
    'alc.sub_category',
    'alc.loan_amt',
    'u.role',
    'u.fullname',
    'ag.ag_name',
    'ag.responsible',
    'cp.cus_type',
    'cp.cus_exist_type',
    'req.cus_status',
    'cs.sub_status'
);

$baseQuery = "FROM acknowlegement_loan_calculation alc
            LEFT JOIN in_acknowledgement ic ON ic.req_id = alc.req_id
            LEFT JOIN request_creation req ON req.req_id = alc.req_id
            LEFT JOIN user u ON u.user_id = ic.inserted_user
            JOIN customer_register cr ON alc.cus_id_loan = cr.cus_id
            JOIN area_list_creation al ON req.area = al.area_id
            JOIN sub_area_list_creation sal ON req.sub_area = sal.sub_area_id
            JOIN loan_category_creation lcc ON alc.loan_category = lcc.loan_category_creation_id
            LEFT JOIN agent_creation ag ON req.agent_id = ag.ag_id
            LEFT JOIN customer_profile cp ON alc.req_id = cp.req_id
            LEFT JOIN customer_status cs ON alc.req_id = cs.req_id
            JOIN area_group_mapping_area agma ON agma.area_id = al.area_id
            JOIN area_group_mapping agm ON agm.map_id = agma.group_map_id
            LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
            JOIN area_line_mapping_area alma ON alma.area_id = al.area_id
            JOIN area_line_mapping alm ON alm.map_id = alma.line_map_id
            WHERE $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $baseQuery .= " AND (req.req_code LIKE '%" . $_POST['search'] . "%'
                    OR ic.inserted_date LIKE '%" . $_POST['search'] . "%' 
                    OR alc.cus_id_loan LIKE '%" . $_POST['search'] . "%'
                    OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
                    OR alc.cus_name_loan LIKE '%" . $_POST['search'] . "%'
                    OR al.area_name LIKE '%" . $_POST['search'] . "%'
                    OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%'
                    OR agm.group_name LIKE '%" . $_POST['search'] . "%'
                    OR alm.line_name LIKE '%" . $_POST['search'] . "%'
                    OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
                    OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
                    OR alc.sub_category LIKE '%" . $_POST['search'] . "%'
                    OR u.role LIKE '%" . $_POST['search'] . "%'
                    OR u.fullname LIKE '%" . $_POST['search'] . "%'
                    OR ag.ag_name LIKE '%" . $_POST['search'] . "%'
                    OR ag.responsible LIKE '%" . $_POST['search'] . "%'
                    OR cp.cus_type LIKE '%" . $_POST['search'] . "%'
                    OR cp.cus_exist_type LIKE '%" . $_POST['search'] . "%'
                    OR req.cus_status LIKE '%" . $_POST['search'] . "%'
                    OR cs.sub_status LIKE '%" . $_POST['search'] . "%') ";
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
$totalStmt = $connect->prepare("SELECT COUNT(*) FROM in_acknowledgement");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute();
$recordsFiltered = (int) $countStmt->fetchColumn();

/* ---------- Data query ---------- */
$dataQuery = "SELECT 
            req.req_code,
            ic.inserted_date,
            alc.cus_id_loan,
            cr.autogen_cus_id,
            alc.cus_name_loan,
            al.area_name,
            sal.sub_area_name,
            agm.group_name,
            alm.line_name,
            bc.branch_name,
            lcc.loan_category_creation_name,
            alc.sub_category,
            alc.loan_amt,
            u.fullname AS approval_user_name,
            CASE u.role
                WHEN 1 THEN 'Director'
                WHEN 2 THEN 'Agent'
                WHEN 3 THEN 'Staff'
                ELSE ''
            END AS approval_user_type,
            ag.ag_name,
            ag.responsible,
            cp.cus_type,
            cp.cus_exist_type,
            req.cus_status,
            cs.sub_status
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
    $sub_array[] = date('d-m-Y', strtotime($row['inserted_date']));
    $sub_array[] = $row['cus_id_loan'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name_loan'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['loan_category_creation_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = $row['approval_user_type'];
    $sub_array[] = $row['approval_user_name'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = (!empty($row['ag_name'])) ? (($row['responsible'] == '0') ? 'Yes' : 'No') : '';
    $sub_array[] = $row['cus_type'];
    $sub_array[] = $row['cus_exist_type'];
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
