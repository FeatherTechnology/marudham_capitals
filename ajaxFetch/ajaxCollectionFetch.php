<?php
@session_start();
include('..\ajaxconfig.php');

$userid = isset($_SESSION["userid"]) ? $_SESSION["userid"] : null;

$role    = null;
$ag_id   = null;
$line_id = null;
$agent_join  = "";
$line_cndtn  = "";
$params = [];          // bound params shared by count query and data query

if ($userid != 1) { // 1 = super admin, sees everything unconditionally
    $stmt = $connect->prepare("SELECT ag_id, role, line_id FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);
    $role    = $rowuser['role']    ?? null;
    $ag_id   = $rowuser['ag_id']   ?? null;
    $line_id = $rowuser['line_id'] ?? null;

    // line_id comes from the user table (not raw POST input), so it's not a direct
    // injection vector the way $_POST values are - left as a direct IN() as in the original.
    if ($role != '2') {
        // Issued customers within the same line(s) as the user
        $line_cndtn = " AND alm.map_id IN ($line_id) ";
    } else {
        $agent_join = " INNER JOIN request_creation rc ON ii.req_id = rc.req_id ";
        $line_cndtn = " AND alm.map_id IN ($line_id)
            AND ( rc.user_type = 'Agent' OR (rc.agent_id IS NOT NULL AND rc.agent_id != '') OR rc.insert_login_id = ? )
            AND rc.agent_id = ? ";
        $params[] = $userid;
        $params[] = $ag_id;
    }
}

// Whitelisted sortable columns - index MUST match the DataTables column order on the frontend
$column = array(
    'ii.id',
    'cr.cus_id',
    'cr.autogen_cus_id',
    'cr.customer_name',
    'alc.area_name',
    'salc.sub_area_name',
    'ii.id',
    'alm.line_name',
    'cr.mobile1',
    'ii.id'
);

$cussts_join  = "";
$cussts_cndtn = "";
$action = '';

if (isset($_POST["CustomerStatus"]) && $_POST["CustomerStatus"] !== '') {
    $cussts_join  = " INNER JOIN customer_status AS cs ON cs.cus_id = ii.cus_id ";
    $cussts_cndtn = " AND cs.sub_status = ? ";
    $params[]     = $_POST["CustomerStatus"];
    $action = "&duestatus=due_nill";
}

// Shared FROM/JOIN/WHERE body - reused for both the count query and the data query
// so the filtering logic only has to be written once and can never drift between the two.
$baseQuery = "
    FROM in_issue ii
    INNER JOIN customer_register cr ON cr.cus_id = ii.cus_id
    $cussts_join
    INNER JOIN area_list_creation alc ON alc.area_id = cr.area_confirm_area
    INNER JOIN sub_area_list_creation salc ON salc.sub_area_id = cr.area_confirm_subarea
    INNER JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = salc.sub_area_id
    INNER JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    INNER JOIN branch_creation b ON b.branch_id = alm.branch_id
    $agent_join
    WHERE ii.status = 0 AND (ii.cus_status BETWEEN 14 AND 17) $line_cndtn $cussts_cndtn
";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search         = $_POST['search'];
    $searchPrefix   = $search . '%';

    $baseQuery .= " AND (cr.cus_id LIKE ?
        OR cr.autogen_cus_id LIKE ?
        OR cr.customer_name LIKE ?
        OR alc.area_name LIKE ?
        OR salc.sub_area_name LIKE ?
        OR alm.line_name LIKE ?
        OR cr.mobile1 LIKE ? ) ";

    $params[] = $searchPrefix;    // cr.cus_id: same prefix-only match as the original
    $params[] = $searchPrefix;  // cr.autogen_cus_id
    $params[] = $searchPrefix;  // cr.customer_name
    $params[] = $searchPrefix;  // alc.area_name
    $params[] = $searchPrefix;  // salc.sub_area_name
    $params[] = $searchPrefix;  // alm.line_name
    $params[] = $searchPrefix;  // cr.mobile1
}

// ---- Count query: same filters, but counts groups instead of fetching every column
// lightweight COUNT(*) over the grouped id list. ----
$countQuery = "SELECT COUNT(*) FROM (SELECT ii.cus_id $baseQuery GROUP BY ii.cus_id) AS grouped";
$countStmt = $connect->prepare($countQuery);
$countStmt->execute($params);
$number_filter_row = (int) $countStmt->fetchColumn();

// ---- Data query ----
$dataQuery = "SELECT cr.cus_id, cr.autogen_cus_id, cr.customer_name, alc.area_name, salc.sub_area_name, alm.line_name AS area_line, cr.mobile1, ii.req_id, b.branch_name $baseQuery GROUP BY ii.cus_id";

$orderCol = 'ii.id';
$orderDir = 'desc';
if (isset($_POST['order'][0]['column'])) {
    $colIdx = (int) $_POST['order'][0]['column'];
    if (array_key_exists($colIdx, $column)) {
        $orderCol = $column[$colIdx];
    }
    if (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') {
        $orderDir = 'asc';
}
}
$dataQuery .= " ORDER BY $orderCol $orderDir";

// LIMIT/OFFSET cast to int before inlining - safe because the cast itself neutralizes
// injection risk, and avoids the PDO quirk where LIMIT placeholders need PARAM_INT
// binding to work reliably with native prepared statements.
if (isset($_POST['length']) && (int) $_POST['length'] !== -1) {
    $start  = isset($_POST['start']) ? max(0, (int) $_POST['start']) : 0;
    $length = (int) $_POST['length'];
    $dataQuery .= " LIMIT $start, $length";
}

$statement = $connect->prepare($dataQuery);
$statement->execute($params);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

foreach ($result as $row) {
    $cus_id = $row['cus_id'];
    $id     = $row['req_id'];

    $data[] = [
        $sno++,
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['customer_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['area_line'],
        $row['mobile1'],
        "<a href='collection&upd=$id&cusidupd=$cus_id$action' title='Edit details' ><button class='btn btn-success' style='background-color:#009688;'>View</button></a>"
    ];
}

$output = [
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
];

echo json_encode($output);

// Close the database connection
$connect = null;