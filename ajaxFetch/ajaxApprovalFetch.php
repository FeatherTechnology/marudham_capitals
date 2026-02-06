<?php
@session_start();
include('..\ajaxconfig.php');
include('..\moneyFormatIndia.php');
include('..\user_based_sub_area_Ids.php');

$userid = $_SESSION['userid'] ?? 0;
$sub_area_list = getUserSubAreaList($connect, 'approval');

if ($userid) {
    $stmt = $connect->prepare("SELECT role FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $login_user_type = $stmt->fetchColumn();
    $stmt->closeCursor();
}

if ($userid != 1) {
    $stmt = $connect->prepare("SELECT app_loan_cat FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);
    $app_loan_cat = $rowuser['app_loan_cat'] ?? 0;
}

/* ---------------- DATATABLE COLUMN MAP ---------------- */
$column = [
    'v.req_id',
    'v.dor',
    'v.cus_id',
    'cr.autogen_cus_id',
    'v.cus_name',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'a.area_name',
    'sa.sub_area_name',
    'lcc.loan_category_creation_name',
    'v.sub_category',
    'v.loan_amt',
    'v.user_type',
    'v.user_name',
    'v.agent_id',
    'v.responsible',
    'v.cus_data',
    'v.cus_status',
    'v.status'
];

/* ---------------- BASE QUERY ---------------- */
$query = "SELECT DISTINCT
    v.dor, 
    v.cus_name,
    v.cus_id, 
    v.sub_category, 
    v.loan_amt, 
    v.user_type, 
    v.responsible, 
    v.cus_data, 
    v.req_id, 
    v.cus_status, 
    v.agent_id, 
    cr.autogen_cus_id, 
    a.area_name, 
    sa.sub_area_name, 
    agm.group_name, 
    bc.branch_name, 
    alm.line_name, 
    lcc.loan_category_creation_name,
    ac.ag_name AS agent_name,
    u.fullname AS verification_user_name,
    CASE u.role
        WHEN 1 THEN 'Director'
        WHEN 2 THEN 'Agent'
        WHEN 3 THEN 'Staff'
        ELSE ''
    END AS verification_user_type

    FROM in_verification v
    LEFT JOIN agent_creation ac ON ac.ag_id = v.agent_id
    LEFT JOIN verification_loan_calculation vlc ON vlc.req_id = v.req_id
    LEFT JOIN user u ON u.user_id = vlc.insert_login_id
    JOIN customer_register cr ON v.cus_id = cr.cus_id
    JOIN area_list_creation a ON v.area = a.area_id
    JOIN sub_area_list_creation sa ON v.sub_area = sa.sub_area_id
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sa.sub_area_id
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    JOIN branch_creation bc ON agm.branch_id = bc.branch_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = v.loan_category
    WHERE v.status = 0 and v.cus_status IN(2,3,13) "; //  //2-in approval, 3-in ack,6-cancel approval, 7-cancel_ack,13-in issue.

/* user-level restriction */
if (!($userid == 1)) {
    $query .= " AND v.sub_area IN ($sub_area_list) and v.loan_category IN ($app_loan_cat)"; //show only moved to Approval list and Approve the verification.
}

/* ---------------- SEARCH ---------------- */
if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $query .= " AND (
        v.dor LIKE '%$search%' OR
        v.cus_id LIKE '%$search%' OR
        cr.autogen_cus_id LIKE '%$search%' OR
        v.cus_name LIKE '%$search%' OR
        bc.branch_name LIKE '%$search%' OR
        agm.group_name LIKE '%$search%' OR
        alm.line_name LIKE '%$search%' OR
        ac.ag_name LIKE '%$search%' OR
        a.area_name LIKE '%$search%' OR
        sa.sub_area_name LIKE '%$search%' OR
        lcc.loan_category_creation_name LIKE '%$search%' OR
        v.sub_category LIKE '%$search%' OR
        v.loan_amt LIKE '%$search%' OR
        v.user_type LIKE '%$search%' OR
        v.responsible LIKE '%$search%' OR
        v.cus_data LIKE '%$search%'
    )";
}

/* ---------------- ORDER ---------------- */
if (isset($_POST['order'])) {
    $col    = $column[$_POST['order'][0]['column']];
    $dir    = $_POST['order'][0]['dir'];
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
$stmt = $connect->prepare("SELECT COUNT(*) FROM in_verification");
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
    $sub[] = $row["branch_name"];
    $sub[] = $row['group_name'];
    $sub[] = $row['line_name'];
    $sub[] = $row['area_name'];
    $sub[] = $row['sub_area_name'];
    $sub[] = $row["loan_category_creation_name"];
    $sub[] = $row['sub_category'];
    $sub[] = moneyFormatIndia($row['loan_amt']);
    $sub[] = $row['verification_user_type'];
    $sub[] = $row['verification_user_name'];
    $sub[] = $row['agent_name'] ?? '';
    $ag_id = $row['agent_id'];
    $sub[] = ($row['responsible'] == '0') ? 'Yes' : (!empty($ag_id) && $row['responsible'] != '0' ? 'No' : '');
    $sub[] = $row['cus_data'];

    $id         = $row['req_id'];
    $cus_id     = $row['cus_id'];
    $loan_amt   = $row['loan_amt'];
    $cus_status = $row['cus_status'];

    $statusLabels = [
        '2'     => "<button class='btn btn-outline-secondary move_acknowledgement' value='$id' data-cusid = '$cus_id' data-loan_amt = '$loan_amt'><span class='icon-arrow_forward'></span></button>",
        '3'     => 'In Acknowledgement',
        '13'    => 'In Issue',
        '6'     => 'Cancel - Approval',
        '7'     => 'Cancel - Acknowledgement',
        '14'    => 'Issued'
    ];

    $sub[]      = $statusLabels[$cus_status] ?? 'Unknown Status';
    $id         = $row['req_id'];

    $action = "<div class='dropdown'>
    <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
    <div class='dropdown-content'>";

    if ($cus_status == '2') {
        $action .= "<a href='verification&upd=$id&pge=2' class='customer_profile' value='$id' > View Verification</a>
        <a href='#' data-reqid = '$id' class='cancelapproval'>Cancel </a>";
    }

    if ($cus_status == '6') {
        $action .= "<a href='verification&del=$id'class='removeapproval'>Remove Verification</a>";
    }

    if ($login_user_type != 2 or $userid == 1) {
        $action .= "<a href='' data-value ='" . $cus_id . "' data-value1 = '$id' class='customer-status' data-toggle='modal' data-target='.customerstatus'>Customer Status</a>";
    }

    $action  .= "</div></div>";
    $sub[]    = $action;
    $data[]   = $sub;
}

/* ---------------- RESPONSE ---------------- */
echo json_encode([
    "draw"              => intval($_POST['draw']),
    "recordsTotal"      => $recordsTotal,
    "recordsFiltered"   => $recordsFiltered,
    "data"              => $data
]);