<?php
/* NOC Handover list based loan not customer because each document can receive and handover by different user. so don't use cus_id group by to show NOC Handovered list. */

@session_start();

include "../ajaxconfig.php";

$userid = $_SESSION["userid"] ?? 0;

$where = [];
$params = [];
$branch   = $_POST['branch'] ?? [];
$sector   = $_POST['sector'] ?? [];
$loan_cat = $_POST['loan_cat'] ?? [];

/* =========================================================
   USER ACCESS FILTER
========================================================= */

if ($userid != 1) {

    $stmt = $connect->prepare("SELECT group_id, line_id, due_followup_lines, noc_mapping_access FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rowuser) {
        echo json_encode([]);
        exit;
    }

    $accessMap = [
        1 => ['group_id', 'area_group_mapping_sub_area', 'group_map_id', 'sub_area_id', 'cr.area_confirm_subarea'],
        2 => ['line_id', 'area_line_mapping_sub_area', 'line_map_id', 'sub_area_id', 'cr.area_confirm_subarea'],
        3 => ['due_followup_lines', 'area_duefollowup_mapping_area', 'duefollowup_map_id', 'area_id', 'cr.area_confirm_area']
    ];

    $accessType = (int)$rowuser['noc_mapping_access'];

    if (!isset($accessMap[$accessType])) {
        echo json_encode([]);
        exit;
    }

    if ($accessType == 3 && !empty($sector)) {
        $condition =  "STRAIGHT_JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = ac.area_id
                       STRAIGHT_JOIN area_duefollowup_mapping adfm ON adfm.map_id = adfma.duefollowup_map_id";
    } else if ($accessType == 1  && !empty($sector)) {
        $condition =  "JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sa.sub_area_id
                       JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id";
    } else {
        $condition = "";
    }

    [$source, $table, $mapCol, $selCol, $filterCol] = $accessMap[$accessType];

    $ids = array_filter(array_map('intval', explode(',',$rowuser[$source] ?? '')));

    if (!$ids) {
        echo json_encode([]);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $connect->prepare("
        SELECT DISTINCT $selCol
        FROM $table
        WHERE $mapCol IN ($placeholders)
    ");

    $stmt->execute($ids);

    $mappedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!$mappedIds) {
        echo json_encode([]);
        exit;
    }

    $where[] = "$filterCol IN (" . implode(',', array_fill(0, count($mappedIds), '?')) . ")";
    $params = array_merge($params, $mappedIds);
}

/* =========================================================
   SEARCH
========================================================= */

$search = $_POST['search'] ?? '';

if (!empty($search)) {

    $where[] = "(
        cr.cus_id LIKE ?
        OR cr.autogen_cus_id LIKE ?
        OR ii.loan_id LIKE ?
        OR ad.doc_id LIKE ?
        OR cr.customer_name LIKE ?
        OR ac.area_name LIKE ?
        OR sa.sub_area_name LIKE ?
        OR bc.branch_name LIKE ?
        OR alm.line_name LIKE ?
        OR lcc.loan_category_creation_name LIKE ?
        OR cr.mobile1 LIKE ?
    )";

    for ($i = 0; $i < 11; $i++) {
        $params[] = "%$search%";
    }
}
/* Branch Filter */
if (!empty($branch)) {
    $branch = array_map('intval', $branch);

    $where[] = "bc.branch_id IN (" . implode(',', array_fill(0, count($branch), '?')) . ")";
    $params = array_merge($params, $branch);
}

/* Sector / Region / Zone Filter */
if (!empty($sector)) {

    $sector = array_map('intval', $sector);
    switch ($accessType) {
        // Sector
        case 1:
            $where[] = "agm.map_id IN (" . implode(',', array_fill(0, count($sector), '?')) . ")";
            break;
        // Region
        case 2:
            $where[] = "alm.map_id IN (" . implode(',', array_fill(0, count($sector), '?')) . ")";
            break;
        // Zone
        case 3:
            $where[] = "adfm.map_id IN (" . implode(',', array_fill(0, count($sector), '?')) . ")";
            break;
        default:
            $where[] = "agm.map_id IN (" . implode(',', array_fill(0, count($sector), '?')) . ")";
            break;
    }


    $params = array_merge($params, $sector);
}

/* Loan Category Filter */
if (!empty($loan_cat)) {
    $loan_cat = array_map('intval', $loan_cat);

    $where[] = "iv.loan_category IN (" . implode(',', array_fill(0, count($loan_cat), '?')) . ")";
    $params = array_merge($params, $loan_cat);
}


/* =========================================================
   WHERE
========================================================= */

$whereSql = '';

if (!empty($where)) {
    $whereSql = ' AND ' . implode(' AND ', $where);
}

/* =========================================================
   ORDER
========================================================= */

$columns = [
    'latest_date',
    'latest_date',
    'cr.cus_id',
    'cr.autogen_cus_id',
    'ii.loan_id',
    'ad.doc_id',
    'cr.customer_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'alm.line_name',
    'cr.mobile1',
    'lcc.loan_category_creation_name',
    'cs.updated_date',
    'cs.updated_date',
    'cs.updated_date',
    'cs.updated_date'
];

$orderBy = " ORDER BY latest_date DESC ";

if (isset($_POST['order'])) {

    $colIndex = (int)$_POST['order'][0]['column'];

    $dir = ($_POST['order'][0]['dir'] == 'asc') ? 'ASC' : 'DESC';

    if (isset($columns[$colIndex])) {
        $orderBy = " ORDER BY {$columns[$colIndex]} $dir ";
    }
}

/* =========================================================
   LIMIT
========================================================= */

$limit = '';

if ($_POST['length'] != -1) {

    $start = (int)$_POST['start'];
    $length = (int)$_POST['length'];

    $limit = " LIMIT $start, $length ";
}

/* =========================================================
   MAIN QUERY
========================================================= */

$query = "
SELECT
    cs.created_date AS latest_date,
    cs.req_id,
    cr.cus_id,
    cr.autogen_cus_id,
    ii.loan_id,
    ad.doc_id,
    cr.customer_name,
    ac.area_name,
    sa.sub_area_name,
    alm.line_name,
    bc.branch_name,
    cr.mobile1,
    lcc.loan_category_creation_name,

    COALESCE(n.receive_status,0) AS receive_status,
    n.receive_by,

    u.user_name AS receive_person

FROM closed_status cs

JOIN in_issue ii
    ON cs.req_id = ii.req_id

JOIN acknowlegement_documentation ad
    ON ii.req_id = ad.req_id

JOIN in_verification iv
    ON ii.req_id = iv.req_id

JOIN customer_register cr
    ON cs.cus_id = cr.cus_id

JOIN area_list_creation ac
    ON cr.area_confirm_area = ac.area_id

JOIN sub_area_list_creation sa
    ON cr.area_confirm_subarea = sa.sub_area_id

JOIN area_line_mapping_sub_area almsa
    ON almsa.sub_area_id = sa.sub_area_id

JOIN area_line_mapping alm
    ON alm.map_id = almsa.line_map_id

JOIN branch_creation bc
    ON alm.branch_id = bc.branch_id
$condition
JOIN loan_category_creation lcc
    ON lcc.loan_category_creation_id = iv.loan_category

LEFT JOIN noc n
    ON n.req_id = cs.req_id

LEFT JOIN user u
    ON u.user_id = n.receive_by

WHERE
    cs.cus_sts = 23

    $whereSql

$orderBy

$limit
";

$stmt = $connect->prepare($query);

$stmt->execute($params);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   FILTERED COUNT
========================================================= */

$countQuery = "
SELECT COUNT(*) FROM (
    SELECT cs.req_id

    FROM closed_status cs

    JOIN in_issue ii
        ON cs.req_id = ii.req_id

    JOIN acknowlegement_documentation ad
        ON ii.req_id = ad.req_id

    JOIN in_verification iv
        ON ii.req_id = iv.req_id

    JOIN customer_register cr
        ON cs.cus_id = cr.cus_id

    JOIN area_list_creation ac
        ON cr.area_confirm_area = ac.area_id

    JOIN sub_area_list_creation sa
        ON cr.area_confirm_subarea = sa.sub_area_id

    JOIN area_line_mapping_sub_area almsa
        ON almsa.sub_area_id = sa.sub_area_id

    JOIN area_line_mapping alm
        ON alm.map_id = almsa.line_map_id
    $condition
    JOIN branch_creation bc
        ON alm.branch_id = bc.branch_id

    JOIN loan_category_creation lcc
        ON lcc.loan_category_creation_id = iv.loan_category

    WHERE
        cs.cus_sts = 23

        $whereSql

) x
";

$countStmt = $connect->prepare($countQuery);

$countStmt->execute($params);

$filtered = $countStmt->fetchColumn();

/* =========================================================
   DATA
========================================================= */

$data = [];

$sno = $_POST['start'] + 1;

foreach ($result as $row) {

    $receive_status = $row['receive_status'];
    $receive_by = $row['receive_by'];

    $status = ($receive_status == 0)
        ? 'In-Receive'
        : 'Received';

    $action = "
    <div class='dropdown'>
        <button class='btn btn-outline-secondary'>
            <i class='fa'>&#xf107;</i>
        </button>
        <div class='dropdown-content'>
    ";

    if ($receive_by == $userid) {

        $action .= "
            <a href='noc_handover&cusidupd={$row['cus_id']}&reqidupd={$row['req_id']}' title='NOC handover'>
                Handover
            </a>
        ";
    } else {

        $action .= "
            <a href='' 
               title='Receive details' 
               class='receive-noc'
               data-reqid='{$row['req_id']}'>
               Receive
            </a>
        ";
    }

    $action .= "</div></div>";

    $data[] = [
        $sno++,
        $row['latest_date'] ? date('d-m-Y', strtotime($row['latest_date'])) : '',
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['loan_id'],
        $row['doc_id'],
        $row['customer_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['line_name'],
        $row['mobile1'],
        $row['loan_category_creation_name'],
        $status,
        $row['receive_person'] ?? '',
        "<a href='#'
            data-value='{$row['cus_id']}'
            class='customer-status' data-toggle='modal' data-target='.customerstatus'>
            <span class='icon-eye'></span>
        </a>",
        $action
    ];
}

/* =========================================================
   OUTPUT
========================================================= */

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsFiltered" => (int)$filtered,
    "data" => $data
]);

$connect = null;
