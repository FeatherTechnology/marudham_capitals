<?php
@session_start();
include('..\ajaxconfig.php');

$column = array(
    'sc.staff_id',
    'sc.staff_code',
    'sc.staff_name',
    'stc.staff_type_name',
    'sc.place',
    'c.company_name',
    'sc.department',
    'sc.team',
    'sc.designation',
    'sc.status',
    'sc.status'
);

$status = $_POST['staffStatus'] ?? '0';
$search = $_POST['search'] ?? '';

$params = [':status' => $status];

$base_query = "
FROM staff_creation sc
JOIN staff_type_creation stc 
    ON stc.staff_type_id = sc.staff_type 
    AND stc.status = 0
JOIN company_creation c 
    ON c.company_id = sc.company_id
WHERE sc.status = :status
";

if (!empty($search)) {

    $base_query .= " AND (
        sc.staff_code LIKE :search
        OR sc.staff_name LIKE :search
        OR stc.staff_type_name LIKE :search
        OR sc.place LIKE :search
        OR c.company_name LIKE :search
        OR sc.department LIKE :search
        OR sc.team LIKE :search
        OR sc.designation LIKE :search
    )";

    $params[':search'] = "%$search%";
}

# ---------- COUNT QUERY ----------
$count_query = "SELECT COUNT(*) $base_query";

$statement = $connect->prepare($count_query);
$statement->execute($params);
$number_filter_row = $statement->fetchColumn();


# ---------- ORDER ----------
$order_query = "";
if (isset($_POST['order'])) {
    $col = $column[$_POST['order'][0]['column']];
    $dir = $_POST['order'][0]['dir'];
    $order_query = " ORDER BY $col $dir ";
}

# ---------- LIMIT ----------
$limit_query = "";
if ($_POST['length'] != -1) {
    $limit_query = " LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
}


# ---------- MAIN QUERY ----------
$query = "
SELECT 
    sc.staff_id,
    sc.staff_code,
    sc.staff_name,
    stc.staff_type_name,
    sc.place,
    c.company_name,
    sc.department,
    sc.team,
    sc.designation,
    sc.status
$base_query
$order_query
$limit_query
";

$statement = $connect->prepare($query);
$statement->execute($params);

$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno++;

    $sub_array[] = $row['staff_code'];
    $sub_array[] = $row['staff_name'];
    $sub_array[] = $row["staff_type_name"];
    $sub_array[] = $row['place'];
    $sub_array[] = $row["company_name"];
    $sub_array[] = $row['department'];
    $sub_array[] = $row['team'];
    $sub_array[] = $row['designation'];

    $status      = $row['status'];

    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id          = $row['staff_id'];

    $action = "<a href='staff_creation&upd=$id&sts=$status' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp;";

    if ($status == 0) {
        $action .= "<a href='staff_creation&del=$id' title='Delete details' class='delete_staff'><span class='icon-trash-2'></span></a>";
    }

    $sub_array[] = $action;
    $data[]      = $sub_array;
}

function count_all_data($connect)
{
    return $connect->query("SELECT COUNT(*) FROM staff_creation")->fetchColumn();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
