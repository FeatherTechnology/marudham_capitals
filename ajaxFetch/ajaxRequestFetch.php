<?php
session_start();
include('../ajaxconfig.php');
include('../moneyFormatIndia.php');

/* ---------------- USER CONTEXT ---------------- */
$userid = $_SESSION['userid'] ?? 0;
$request_list_access = $_SESSION['request_list_access'] ?? 0;

if ($userid) {
    $stmt = $connect->prepare("SELECT role FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $login_user_type = $stmt->fetchColumn();
    $stmt->closeCursor();
}

/* ---------------- DATATABLE COLUMN MAP ---------------- */
$column = [
    'rc.req_id',
    'rc.dor',
    'rc.cus_id',
    'cr.autogen_cus_id',
    'rc.cus_name',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'rc.mobile1',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'rc.sub_category',
    'rc.loan_amt',
    'rc.user_type',
    'rc.user_name',
    'rc.agent_id',
    'rc.responsible',
    'rc.cus_data',
    'rc.cus_status',
    'rc.req_id'
];

/* ---------------- BASE QUERY ---------------- */
$query = "SELECT DISTINCT
    rc.req_id,
    rc.dor,
    rc.cus_id,
    rc.cus_name,
    rc.mobile1,
    rc.sub_category,
    rc.loan_amt,
    rc.user_type,
    rc.user_name,
    rc.agent_id,
    rc.responsible,
    rc.cus_data,
    rc.cus_status,

    cr.autogen_cus_id,
    a.area_name,
    sa.sub_area_name,
    agm.group_name,
    bc.branch_name,
    alm.line_name,
    lcc.loan_category_creation_name,
    ac.ag_name AS agent_name

    FROM request_creation rc
    LEFT JOIN agent_creation ac ON ac.ag_id = rc.agent_id
    STRAIGHT_JOIN customer_register cr ON cr.cus_id = rc.cus_id
    STRAIGHT_JOIN sub_area_list_creation sa ON sa.sub_area_id = rc.sub_area
    STRAIGHT_JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sa.sub_area_id
    STRAIGHT_JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    STRAIGHT_JOIN branch_creation bc ON bc.branch_id = agm.branch_id
    STRAIGHT_JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    STRAIGHT_JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    STRAIGHT_JOIN area_list_creation a ON a.area_id = rc.area
    STRAIGHT_JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = rc.loan_category
    WHERE rc.status = 0 AND rc.cus_status < 14 AND rc.cus_status NOT IN (4,5,6,7,8,9)";

/* user-level restriction */
if (!($userid == 1 || $request_list_access == 0)) {
    $query .= " AND rc.insert_login_id = '" . intval($userid) . "'";
}

/* ---------------- SEARCH ---------------- */
if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $query .= " AND (
        rc.dor LIKE '%$search%' OR
        rc.cus_id LIKE '%$search%' OR
        cr.autogen_cus_id LIKE '%$search%' OR
        rc.cus_name LIKE '%$search%' OR
        bc.branch_name LIKE '%$search%' OR
        agm.group_name LIKE '%$search%' OR
        alm.line_name LIKE '%$search%' OR
        ac.ag_name LIKE '%$search%' OR
        a.area_name LIKE '%$search%' OR
        sa.sub_area_name LIKE '%$search%' OR
        lcc.loan_category_creation_name LIKE '%$search%' OR
        rc.sub_category LIKE '%$search%' OR
        rc.loan_amt LIKE '%$search%' OR
        rc.user_type LIKE '%$search%' OR
        rc.responsible LIKE '%$search%' OR
        rc.cus_data LIKE '%$search%'
    )";
}

/* ---------------- ORDER ---------------- */
if (isset($_POST['order'])) {
    $col = $column[$_POST['order'][0]['column']];
    $dir = $_POST['order'][0]['dir'];
    $query .= " ORDER BY $col $dir ";
}

/* ---------------- PAGINATION ---------------- */
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
}

/* ---------------- EXECUTE MAIN QUERY ---------------- */
$stmt = $connect->prepare($query . $limit);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

/* ---------------- COUNT FILTERED ---------------- */
$stmt = $connect->prepare($query);
$stmt->execute();
$recordsFiltered = $stmt->rowCount();
$stmt->closeCursor();

/* ---------------- COUNT TOTAL ---------------- */
$stmt = $connect->prepare("SELECT COUNT(*) FROM request_creation");
$stmt->execute();
$recordsTotal = $stmt->fetchColumn();
$stmt->closeCursor();

/* ---------------- DATA FORMAT ---------------- */
$data = [];
$sno = intval($_POST['start']) + 1;

foreach ($result as $row) {

    $sub = [];

    $sub[] = $sno++;
    $sub[] = date('d-m-Y', strtotime($row['dor']));
    $sub[] = $row['cus_id'];
    $sub[] = $row['autogen_cus_id'];
    $sub[] = $row['cus_name'];
    $sub[] = $row['branch_name'];
    $sub[] = $row['group_name'];
    $sub[] = $row['line_name'];
    $sub[] = $row['mobile1'];
    $sub[] = $row['area_name'];
    $sub[] = $row['sub_area_name'];
    $sub[] = $row['loan_category_creation_name'];
    $sub[] = $row['sub_category'];
    $sub[] = moneyFormatIndia($row['loan_amt']);
    $sub[] = $row['user_type'];
    $sub[] = $row['user_name'];
    $sub[] = $row['agent_name'] ?? '';
    $ag_id = $row['agent_id'];
    $sub[] = ($row['responsible'] == '0') ? 'Yes' : (!empty($ag_id) && $row['responsible'] != '0' ? 'No' : '');
    $sub[] = $row['cus_data'];

    $id = $row['req_id'];
    $cus_id = $row['cus_id'];
    $cus_status = $row['cus_status'];

    $status_messages = [
        '0' => "<button class='btn btn-outline-secondary sub_verification' value='$id' data-value='$cus_id'><span class='icon-arrow_forward'></span></button>",
        '1' => 'In Verification',
        '2' => 'In Approval',
        '3' => 'In Acknowledgement',
        '4' => 'Cancel - Request',
        '5' => 'Cancel - Verification',
        '6' => 'Cancel - Approval',
        '7' => 'Cancel - Acknowledgement',
        '10' => 'In Verification',
        '11' => 'In Verification',
        '12' => 'In Verification',
        '13' => 'In Issue',
        '14' => 'Issued'
    ];

    $sub[] = $status_messages[$cus_status] ?? 'Unknown Status';

    $action = "<div class='dropdown'>
        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
        <div class='dropdown-content'>";

    if ($cus_status == '0') {
        $action .= "<a href='request&upd=$id'>Edit Request</a>
                    <a href='' data-reqId='$id' class='cancelrequest'>Cancel Request</a>
                    <a href='' data-reqId='$id' class='revokerequest'>Revoke Request</a>";
    }

    if (in_array($cus_status, ['4', '5', '6'])) {
        $action .= "<a href='request&del=$id' class='removerequest'>Remove Request</a>";
    }

    if ($login_user_type != 2 || $userid == 1) { //role 2- Agent 
        $action .= "<a href='' data-value='$cus_id' data-value1='$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'>Customer Status</a>";
    }

    $action .= "</div></div>";
    $sub[] = $action;

    $data[] = $sub;
}

/* ---------------- RESPONSE ---------------- */
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);

