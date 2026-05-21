<?php
@session_start();
include "../ajaxconfig.php";

$userid = $_SESSION["userid"] ?? '';

$where = [];
$params = [];

/* =========================================================
   USER ACCESS
========================================================= */

if ($userid != 1) {

    $stmt = $connect->prepare("
        SELECT 
            group_id,
            line_id,
            due_followup_lines,
            noc_mapping_access
        FROM user
        WHERE user_id = ?
    ");

    $stmt->execute([$userid]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([]);
        exit;
    }

    $accessMap = [
        1 => ['group_id', 'area_group_mapping_sub_area', 'group_map_id', 'sub_area_id', 'cr.area_confirm_subarea'],
        2 => ['line_id', 'area_line_mapping_sub_area', 'line_map_id', 'sub_area_id', 'cr.area_confirm_subarea'],
        3 => ['due_followup_lines', 'area_duefollowup_mapping_area', 'duefollowup_map_id', 'area_id', 'cr.area_confirm_area']
    ];

    $accessType = (int)$user['noc_mapping_access'];

    if (!isset($accessMap[$accessType])) {
        echo json_encode([]);
        exit;
    }

    [$source, $table, $mapCol, $selCol, $filterCol] = $accessMap[$accessType];

    $ids = array_filter(array_map('intval', explode(',', $user[$source] ?? '')));

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
        OR cr.customer_name LIKE ?
        OR ac.area_name LIKE ?
        OR sa.sub_area_name LIKE ?
        OR alm.line_name LIKE ?
        OR bc.branch_name LIKE ?
        OR cr.mobile1 LIKE ?
    )";

    for ($i = 0; $i < 8; $i++) {
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
    'cr.customer_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'alm.line_name',
    'cr.mobile1',
    'ii.id',
    'ii.id',
    'ii.id'
];

$orderBy = " ORDER BY latest_date DESC ";

if (isset($_POST['order'])) {

    $colIndex = (int)$_POST['order'][0]['column'];
    $dir = $_POST['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';

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
    MAX(cs.created_date) AS latest_date,
    cr.cus_id,
    cr.autogen_cus_id,
    cr.customer_name,
    ac.area_name,
    sa.sub_area_name,
    alm.line_name,
    bc.branch_name,
    cr.mobile1,

    GROUP_CONCAT(DISTINCT ii.cus_status) AS statuses,

    MAX(
        CASE
            WHEN n.receive_status = 0 THEN 1
            ELSE 0
        END
    ) AS pending_receive

FROM in_issue ii

JOIN customer_register cr
    ON ii.cus_id = cr.cus_id

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

LEFT JOIN noc n
    ON ii.req_id = n.req_id

LEFT JOIN closed_status cs
    ON cs.cus_id = cr.cus_id

WHERE
    ii.status = 0
    AND ii.cus_status IN (21,22,23)
    AND (n.receive_status = 0 OR n.req_id IS NULL)
    $whereSql

GROUP BY ii.cus_id

$orderBy

$limit
";

$stmt = $connect->prepare($query);
$stmt->execute($params);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   FILTER COUNT
========================================================= */

$countQuery = "
SELECT COUNT(*) as total
FROM (
    SELECT ii.cus_id
    FROM in_issue ii
    LEFT JOIN noc n ON ii.req_id = n.req_id
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cr.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id

    WHERE
        ii.status = 0
        AND ii.cus_status IN (21,22,23)
        AND (n.receive_status = 0 OR n.req_id IS NULL)
        $whereSql

    GROUP BY ii.cus_id
) x
";

$countStmt = $connect->prepare($countQuery);
$countStmt->execute($params);

$filtered = $countStmt->fetchColumn();

/* =========================================================
   TOTAL COUNT
========================================================= */

$totalStmt = $connect->query("
    SELECT COUNT(DISTINCT cus_id)
    FROM in_issue
    WHERE status = 0
    AND cus_status IN (21,22,23)
");

$total = $totalStmt->fetchColumn();

/* =========================================================
   DATA
========================================================= */

$data = [];
$sno = $_POST['start'] + 1;

foreach ($result as $row) {

    $statuses = array_map('intval', explode(',', $row['statuses']));

    if (in_array(21, $statuses) || in_array(22, $statuses)) {

        $noc_status = 'NOC';

    } elseif (in_array(23, $statuses)) {

        $noc_status = $row['pending_receive'] ? 'Pending' : 'Completed';

    } else {
        $noc_status = '';
    }

    $action = "
    <div class='dropdown'>
        <button class='btn btn-outline-secondary'>
            <i class='fa'>&#xf107;</i>
        </button>
        <div class='dropdown-content'>
            <a href='noc&cusidupd={$row['cus_id']}' title='Edit details'>NOC</a>
    ";

    // Conditions
    // If any one loan is 22 → show SEND
    if (in_array(22, $statuses)) {
        $action .= "
            <a href='' title='Send details' class='remove-noc' data-cusid='{$row['cus_id']}'>
                Send
            </a>
        ";
    }

    // For status 22 or 23 → show Summary + Letter
    if (in_array(22, $statuses) || (in_array(23, $statuses) && $noc_status == 'Pending')) {

        $action .= "
            <a href='noc&cusidupd={$row['cus_id']}'>
                NOC Summary & Letter
            </a>
        ";
    }

    $action .= "</div></div>";

    $data[] = [
        $sno++,
        $row['latest_date'] ? date('d-m-Y', strtotime($row['latest_date'])) : '',
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['customer_name'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['branch_name'],
        $row['line_name'],
        $row['mobile1'],
        $noc_status,
        "<a href='#' data-value='{$row['cus_id']}' class='customer-status' data-toggle='modal' data-target='.customerstatus'>
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
    "recordsTotal" => (int)$total,
    "recordsFiltered" => (int)$filtered,
    "data" => $data
]);

// Close the database connection
$connect = null;
?>