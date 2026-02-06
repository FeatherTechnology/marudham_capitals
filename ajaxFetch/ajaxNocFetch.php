<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

/* ================= USER ACCESS FILTER ================= */
$sub_area_list = '';
$colName = '';

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

    [$source, $table, $mapCol, $selCol, $filterCol] = $accessMap[$accessType];

    $ids = array_filter(array_map('intval', explode(',', $rowuser[$source] ?? '')));

    if (!$ids) {
        echo json_encode([]);
        exit;
    }

    $in = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $connect->prepare("SELECT DISTINCT $selCol FROM $table WHERE $mapCol IN ($in)");
    $stmt->execute($ids);

    $sub_area_list = implode(',', $stmt->fetchAll(PDO::FETCH_COLUMN));
    $colName = $filterCol;
}

$column = array(
    'cs.latest_date',
    'cs.latest_date',
    'cr.cus_id',
    'cr.autogen_cus_id',
    'cr.cus_name',
    'ac.area_name',
    'sa.sub_area_name',
    'bc.branch_name',
    'alm.line_name',
    'cr.mobile1',
    'ii.id',
    'ii.id',
    'ii.id'
);

//21 closed
//22 NOC given
//23 send NOC Handover
//24 NOC Handovered.
if ($userid == 1) {
    $query = "SELECT cs.latest_date, cr.cus_id, cr.autogen_cus_id, cr.customer_name, ac.area_name, sa.sub_area_name, alm.line_name, bc.branch_name, cr.mobile1
    FROM in_issue ii 
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cr.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id
    LEFT JOIN (
        SELECT cs.cus_id, MAX(cs.created_date) AS latest_date
        FROM closed_status cs
        INNER JOIN (
            SELECT DISTINCT cus_id 
            FROM in_issue 
            WHERE status = 0 
            AND cus_status IN (21,22,23)
        ) filtered_customers ON cs.cus_id = filtered_customers.cus_id
        GROUP BY cs.cus_id
    ) cs
    ON cs.cus_id = cr.cus_id
    WHERE ii.status = 0
        AND ii.cus_status IN (21,22,23) "; // Only Issued and all lines not relying on sub area
} else {
    $query = "SELECT cs.latest_date, cr.cus_id, cr.autogen_cus_id, cr.customer_name, ac.area_name, sa.sub_area_name, alm.line_name, bc.branch_name, cr.mobile1
    FROM in_issue ii 
    JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN area_list_creation ac ON cr.area_confirm_area = ac.area_id
    JOIN sub_area_list_creation sa ON cr.area_confirm_subarea = sa.sub_area_id
    JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sa.sub_area_id
    JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
    JOIN branch_creation bc ON alm.branch_id = bc.branch_id
    LEFT JOIN (
        SELECT cs.cus_id, MAX(cs.created_date) AS latest_date
        FROM closed_status cs
        INNER JOIN (
            SELECT DISTINCT cus_id 
            FROM in_issue 
            WHERE status = 0 
            AND cus_status IN (21,22,23)
        ) filtered_customers ON cs.cus_id = filtered_customers.cus_id
        GROUP BY cs.cus_id
    ) cs
    ON cs.cus_id = cr.cus_id
    WHERE ii.status = 0
        AND ii.cus_status IN (21,22,23)
        AND $colName IN ($sub_area_list) ";
}

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= " AND (cr.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cs.latest_date LIKE '%" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
            OR cr.customer_name LIKE '%" . $_POST['search'] . "%'
            OR ac.area_name LIKE '%" . $_POST['search'] . "%'
            OR sa.sub_area_name LIKE '%" . $_POST['search'] . "%'
            OR alm.line_name LIKE '%" . $_POST['search'] . "%'
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
            OR cr.mobile1 LIKE '%" . $_POST['search'] . "%' ) ";
}

$query .= 'GROUP BY ii.cus_id ';

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
}

$query1 = ($_POST['length'] != -1) ? 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'] : '';

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

// 1. Fetch all cus_status grouped by customer
$stsStmt = $connect->query("SELECT cus_id, GROUP_CONCAT(DISTINCT cus_status) AS statuses FROM in_issue WHERE cus_status BETWEEN 21 AND 23 GROUP BY cus_id");

$statusMap = [];
while ($row = $stsStmt->fetch(PDO::FETCH_ASSOC)) {
    $statusMap[$row['cus_id']] = array_map('intval', explode(',', $row['statuses']));
}

// 2. Fetch pending receive_status once
$resStmt = $connect->query("SELECT DISTINCT cus_id FROM noc WHERE receive_status = 0");

$pendingReceive = $resStmt->fetchAll(PDO::FETCH_COLUMN);
$pendingReceive = array_flip($pendingReceive); // for fast lookup

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $cus_id = $row['cus_id'];
    $allStatus = $statusMap[$cus_id] ?? [];

    $sub_array[] = $sno++;
    $sub_array[] = $row['latest_date'] ? date('d-m-Y', strtotime($row['latest_date'])) : '';
    $sub_array[] = $cus_id;
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['customer_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row["branch_name"];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['mobile1'];

    if ((in_array(21, $allStatus) || in_array(22, $allStatus)) && !in_array(23, $allStatus)) {
        $noc_status = 'NOC';
    } elseif (in_array(23, $allStatus)) {
        $noc_status = isset($pendingReceive[$cus_id]) ? 'Pending' : 'Completed';
    } else {
        $noc_status = '';
    }
    $sub_array[] = $noc_status;

    $sub_array[] = "<a href='#' data-value ='" . $cus_id . "' class='customer-status' data-toggle='modal' data-target='.customerstatus'><span class='icon-eye' style='font-size: 12px;position: relative;top: 2px;'></span></a>";

    $action  = "<div class='dropdown'>
                <button class='btn btn-outline-secondary'>
                    <i class='fa'>&#xf107;</i>
                </button>
                <div class='dropdown-content'>";

    $action .= "<a href='noc&cusidupd=$cus_id' title='Edit details'>NOC</a>";

    // Conditions
    // If any one loan is 22 → show SEND
    if (in_array(22, $allStatus)) {
        $action .= "<a href='' title='Send details' class='remove-noc' data-cusid='$cus_id'>Send</a>";
    }

    // For status 22 or 23 → show Summary + Letter
    if (in_array(22, $allStatus) || in_array(23, $allStatus)) {
        $action .= "<a href='noc&cusidupd=$cus_id'>NOC Summary & Letter</a>";
    }
    $action .= "</div></div>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
}

function count_all_data($connect)
{
    $query     = "SELECT cus_id FROM in_issue WHERE status = 0 AND cus_status IN (21,22,23) GROUP BY cus_id";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
