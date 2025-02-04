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

$query = "SELECT sc.*,stc.staff_type_name,c.company_name 
FROM staff_creation sc
JOIN staff_type_creation stc ON stc.staff_type_id = sc.staff_type and stc.status = 0
JOIN company_creation c ON c.company_id =  sc.company_id WHERE 1 ";
if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "AND
            (sc.staff_code LIKE '%" . $_POST['search'] . "%'
            OR sc.staff_name LIKE '%" . $_POST['search'] . "%'
            OR stc.staff_type_name LIKE '%" . $_POST['search'] . "%'
            OR sc.place LIKE '%" . $_POST['search'] . "%'
            OR c.company_name LIKE '%" . $_POST['search'] . "%'
            OR sc.department LIKE '%" . $_POST['search'] . "%'
            OR sc.team LIKE '%" . $_POST['search'] . "%'
            OR sc.designation LIKE '%" . $_POST['search'] . "%') ";
}
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno;

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

    $action = "<a href='staff_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
    <a href='staff_creation&del=$id' title='Edit details' class='delete_staff'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM staff_creation";
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
