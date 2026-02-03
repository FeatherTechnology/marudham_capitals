<?php
include '../ajaxconfig.php';

/* ---------- Column mapping (DataTables order) ---------- */
$columns = [
    'adm.map_id',
    'adm.duefollowup_name',
    'c.company_name',
    'b.branch_name',
    'a.area_names',
    'adm.status',
    'adm.status'
];

/* ---------- Base FROM + JOIN ---------- */
$baseQuery = "
    FROM area_duefollowup_mapping adm
    JOIN company_creation c ON adm.company_id = c.company_id
    JOIN branch_creation b ON adm.branch_id = b.branch_id
    LEFT JOIN (
        SELECT adma.duefollowup_map_id,
               GROUP_CONCAT(alc.area_name ORDER BY alc.area_id SEPARATOR ', ') AS area_names
        FROM area_duefollowup_mapping_area adma
        JOIN area_list_creation alc ON adma.area_id = alc.area_id
        GROUP BY adma.duefollowup_map_id
    ) a ON adm.map_id = a.duefollowup_map_id
    WHERE 1=1
";

/* ---------- Search ---------- */
$params = [];
if (!empty($_POST['search']['value'])) {
    $search = '%' . $_POST['search']['value'] . '%';
    $baseQuery .= "
        AND (
            adm.duefollowup_name LIKE :search
            OR c.company_name LIKE :search
            OR b.branch_name LIKE :search
            OR a.area_names LIKE :search
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
$totalStmt = $connect->prepare("SELECT COUNT(*) FROM area_duefollowup_mapping");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute($params);
$recordsFiltered = (int) $countStmt->fetchColumn();


/* ---------- Data query ---------- */
$dataQuery = "
    SELECT 
        adm.map_id,
        adm.duefollowup_name,
        c.company_name,
        b.branch_name,
        a.area_names,
        adm.status
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
        <a href='area_mapping&upd=$id&type=duefollowup' title='Edit details'><span class='icon-border_color'></span></a>
        <a href='area_mapping&del=$id&type=duefollowup' class='delete_area_mapping' title='Delete details'><span class='icon-trash-2'></span></a>
    ";

    $data[] = [
        $sno++,
        $row['duefollowup_name'],
        $row['company_name'],
        $row['branch_name'],
        $row['area_names'],
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