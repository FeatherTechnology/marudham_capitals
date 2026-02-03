<?php
include('../ajaxconfig.php');

/* ---------- Column mapping (DataTables order) ---------- */
$columns = [
    'agm.map_id',
    'agm.group_name',
    'c.company_name',
    'b.branch_name',
    'a.area_names',
    's.sub_area_names',
    'agm.status',
    'agm.status'
];

/* ---------- Base FROM + JOIN ---------- */
$baseQuery = "
    FROM area_group_mapping agm
    JOIN company_creation c ON agm.company_id = c.company_id
    JOIN branch_creation b ON agm.branch_id = b.branch_id
    LEFT JOIN (
        SELECT agma.group_map_id,
               GROUP_CONCAT(alc.area_name ORDER BY alc.area_id SEPARATOR ', ') AS area_names
        FROM area_group_mapping_area agma
        JOIN area_list_creation alc ON agma.area_id = alc.area_id
        GROUP BY agma.group_map_id
    ) a ON agm.map_id = a.group_map_id
    LEFT JOIN (
        SELECT agmsa.group_map_id,
               GROUP_CONCAT(salc.sub_area_name ORDER BY salc.sub_area_id SEPARATOR ', ') AS sub_area_names
        FROM area_group_mapping_sub_area agmsa
        JOIN sub_area_list_creation salc ON agmsa.sub_area_id = salc.sub_area_id
        GROUP BY agmsa.group_map_id
    ) s ON agm.map_id = s.group_map_id
    WHERE 1=1
";

/* ---------- Search ---------- */
$params = [];
if (!empty($_POST['search']['value'])) {
    $search = '%' . $_POST['search']['value'] . '%';
    $baseQuery .= "
        AND (
            agm.group_name LIKE :search
            OR c.company_name LIKE :search
            OR b.branch_name LIKE :search
            OR a.area_names LIKE :search
            OR s.sub_area_names LIKE :search
        )
    ";
    $params[':search'] = $search;
}

/* ---------- ORDER ---------- */
$orderBy = '';
if (isset($_POST['order'][0]['column'])) {
    $colIndex = (int) $_POST['order'][0]['column'];
    $dir = ($_POST['order'][0]['dir'] === 'desc') ? 'DESC' : 'ASC';

    if (isset($columns[$colIndex])) {
        $orderBy = " ORDER BY {$columns[$colIndex]} $dir ";
    }
}

/* ---------- Pagination ---------- */
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT :start, :length ";
}

/* ---------- Total records ---------- */
$totalStmt = $connect->prepare("SELECT COUNT(*) FROM area_group_mapping");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute($params);
$recordsFiltered = (int) $countStmt->fetchColumn();

/* ---------- Data query ---------- */
$dataQuery = "
    SELECT 
        agm.map_id,
        agm.group_name,
        c.company_name,
        b.branch_name,
        a.area_names,
        s.sub_area_names,
        agm.status
    $baseQuery
    $orderBy
    $limit
";

$dataStmt = $connect->prepare($dataQuery);

/* Bind search */
foreach ($params as $key => $val) {
    $dataStmt->bindValue($key, $val);
}

/* Bind limit */
if ($_POST['length'] != -1) {
    $dataStmt->bindValue(':start', (int) $_POST['start'], PDO::PARAM_INT);
    $dataStmt->bindValue(':length', (int) $_POST['length'], PDO::PARAM_INT);
}

$dataStmt->execute();
$rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------- Build response ---------- */
$data = [];
$sno = $_POST['start'] + 1;

foreach ($rows as $row) {

    $statusBadge = ($row['status'] == 1)
        ? "<span class='kt-badge kt-badge--danger kt-badge--pill'>Inactive</span>"
        : "<span class='kt-badge kt-badge--success kt-badge--pill'>Active</span>";

    $id = $row['map_id'];

    $action = "
        <a href='area_mapping&upd=$id&type=group'><span class='icon-border_color' title='Edit details'></span></a>
        <a href='area_mapping&del=$id&type=group' class='delete_area_mapping' title='Delete details'><span class='icon-trash-2'></span></a>
    ";

    $data[] = [
        $sno++,
        $row['group_name'],
        $row['company_name'],
        $row['branch_name'],
        $row['area_names'],
        $row['sub_area_names'],
        $statusBadge,
        $action
    ];
}

/* ---------- Output ---------- */
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
