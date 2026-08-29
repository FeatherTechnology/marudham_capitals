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
$condition = '';

/* =========================================================
   USER ACCESS FILTER
========================================================= */

$userAllowedIds = null;
$accessType = 0;

if ($userid != 1) {
    $stmt = $connect->prepare("SELECT group_id, line_id, due_followup_lines, noc_mapping_access FROM user WHERE user_id = ?");
    $stmt->execute([$userid]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rowuser) {
        echo json_encode([]);
        exit;
    }

    $accessType = (int) $rowuser['noc_mapping_access'];
    $accessMap = [
        1 => [
            'source' => 'group_id',
            'join'   => "INNER JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = cr.area_confirm_subarea",
            'column' => 'agmsa.group_map_id'
        ],
        2 => [
            'source' => 'line_id',
            'join'   => '',
            'column' => 'alm.map_id'
        ],
        3 => [
            'source' => 'due_followup_lines',
            'join'   => "INNER JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = cr.area_confirm_area",
            'column' => 'adfma.duefollowup_map_id'
        ]
    ];

    if (!isset($accessMap[$accessType])) {
        echo json_encode([]);
        exit;
    }

    $access = $accessMap[$accessType];
    $userAllowedIds = array_filter(array_map('intval', explode(',', $rowuser[$access['source']] ?? '')));

    if (empty($userAllowedIds)) {
        echo json_encode([]);
        exit;
    }

    $condition = $access['join'];
}

/* =========================================================
   2. SECTOR FILTERING & PERMISSION MERGE
========================================================= */
$finalFilterIds = [];

if (!empty($sector)) {
    $selectedSectors = array_map('intval', (array)$sector);

    if ($userid != 1) {
        // Safe: Intersect selected sectors with user's allowed IDs
        $finalFilterIds = array_values(array_intersect($userAllowedIds, $selectedSectors));
        
        // If user tries to access unauthorized sectors, return empty result
        if (empty($finalFilterIds)) {
            echo json_encode([]);
            exit;
        }
    } else {
        // Admin user selecting sectors directly
        $finalFilterIds = $selectedSectors;
    }
} else {
    // If sector filter is empty, fallback to user access mapping
    if ($userid != 1) {
        $finalFilterIds = $userAllowedIds;
    }
}

/* Apply single WHERE condition */
if (!empty($finalFilterIds)) {
    $columnMap = [
        1 => "agmsa.group_map_id",
        2 => "alm.map_id",
        3 => "adfma.duefollowup_map_id"
    ];
    $targetColumn = $columnMap[$accessType] ?? "agmsa.group_map_id";

    $placeholders = implode(',', array_fill(0, count($finalFilterIds), '?'));
    $where[] = "{$targetColumn} IN ($placeholders)";
    $params = array_merge($params, $finalFilterIds);
}
 
/* Branch Filter */
if (!empty($branch)) {
    $branch = array_map('intval', $branch);

    $where[] = "bc.branch_id IN (" . implode(',', array_fill(0, count($branch), '?')) . ")";
    $params = array_merge($params, $branch);
}
// Loan category Filter 
if (!empty($loan_cat)) {
    $loan_cat = array_map('intval', $loan_cat);

    $where[] = "iv.loan_category IN (" . implode(',', array_fill(0, count($loan_cat), '?')) . ")";
    $params = array_merge($params, $loan_cat);
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

$query = "
SELECT STRAIGHT_JOIN
    MAX(cs.created_date) AS latest_date,
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
    u.user_name AS receive_person,
    COUNT(*) OVER() AS filtered_count
FROM closed_status cs
INNER JOIN in_issue ii ON cs.req_id = ii.req_id
INNER JOIN acknowlegement_documentation ad ON ii.req_id = ad.req_id
INNER JOIN in_verification iv ON ii.req_id = iv.req_id
INNER JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = iv.loan_category
INNER JOIN customer_register cr ON cs.cus_id = cr.cus_id
$condition
INNER JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
INNER JOIN sub_area_list_creation sa ON cr.area_confirm_subarea = sa.sub_area_id
INNER JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
INNER JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
INNER JOIN branch_creation bc ON alm.branch_id = bc.branch_id
LEFT JOIN noc n ON n.req_id = cs.req_id AND n.cus_status = 23
LEFT JOIN user u ON u.user_id = n.receive_by
WHERE ii.cus_status = 23 $whereSql
GROUP BY ii.req_id

$orderBy

$limit
";

$stmt = $connect->prepare($query);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   FILTERED COUNT — from the window function, no second query
========================================================= */

$filtered = $result[0]['filtered_count'] ?? 0;

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
?>