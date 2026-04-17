<?php
include '../../ajaxconfig.php';

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d 00:00:00', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d 23:59:59', strtotime($_POST['to_date']));
    $where .= " AND la.actions_date_time BETWEEN '$from_date' AND '$to_date'";
}

if (!empty($_POST['user_id']) && $_POST['user_id'] != 'all') {
    $where .= " AND la.user_id = '" . $_POST['user_id'] . "'";
}

$user_type = $_POST['user_type'] ?? '';

if ($user_type == '2') {
    $where .= " AND u.status = 0";
} elseif ($user_type == '3') {
    $where .= " AND u.status = 1";
}

/* ---------- Column List ---------- */
$column = [
    'la.id',
    'u.fullname',
    'u.role',
    'la.actions',
    'la.actions_date_time',
    'la.latitude',
    'la.longitude',
    'la.location'
];

/* ---------- Base Query ---------- */
$baseQuery = "FROM location_audit la
            LEFT JOIN user u ON la.user_id = u.user_id 
            WHERE $where ";

/* ---------- Search ---------- */
if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $baseQuery .= " and (u.fullname LIKE '%" . $_POST['search'] . "%' OR
                la.actions LIKE '%" . $_POST['search'] . "%' OR
                la.latitude LIKE '%" . $_POST['search'] . "%' OR
                la.longitude LIKE '%" . $_POST['search'] . "%' OR
                la.location LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

/* ---------- ORDER ---------- */
if (!empty($_POST['user_id']) && $_POST['user_id'] == 'all') {
    // Force order when "All" selected
    $orderBy = " ORDER BY u.fullname ASC, la.actions_date_time ASC";
} 
else if (isset($_POST['order'])) {
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
$totalStmt = $connect->prepare("SELECT COUNT(*) FROM location_audit");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute();
$recordsFiltered = (int) $countStmt->fetchColumn();

/* ---------- Data query ---------- */
$dataQuery = "SELECT 
        u.fullname AS location_track_user_name,
        CASE u.role
            WHEN 1 THEN 'Director'
            WHEN 2 THEN 'Agent'
            WHEN 3 THEN 'Staff'
            ELSE ''
        END AS location_track_user_type,
        la.actions,
        la.actions_date_time,
        la.latitude,
        la.longitude,
        la.location
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
    $sub_array[] = $row['location_track_user_name'];
    $sub_array[] = $row['location_track_user_type'];
    $sub_array[] = $row['actions'];
    $sub_array[] = date('d-m-Y h:i:s A', strtotime($row['actions_date_time']));
    $sub_array[] = $row['latitude'];
    $sub_array[] = $row['longitude'];
    $sub_array[] = $row['location'];

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