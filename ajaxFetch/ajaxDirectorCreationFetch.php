<?php
@session_start();
include('..\ajaxconfig.php');

$column = array(
    'd.dir_id',
    'c.company_name',
    'd.dir_code',
    'd.dir_name',
    'd.dir_type',
    'd.place',
    'd.taluk',
    'd.district',
    'd.mobile_no',
    'd.status',
    'd.dir_id'
);

$query = "SELECT d.*,c.company_name FROM director_creation d
JOIN company_creation c ON c.company_id = d.company_id WHERE 1 ";
if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "and
        (d.dir_code LIKE '%" . $_POST['search'] . "%'
        OR d.dir_name LIKE '%" . $_POST['search'] . "%'
        OR c.company_name LIKE '%" . $_POST['search'] . "%'
        OR d.place LIKE '%" . $_POST['search'] . "%'
        OR d.taluk LIKE '%" . $_POST['search'] . "%'
        OR d.district LIKE '%" . $_POST['search'] . "%'
        OR d.mobile_no LIKE '%" . $_POST['search'] . "%') ";
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
    $sub_array[] = $row["company_name"];
    $sub_array[] = $row['dir_code'];
    $sub_array[] = $row['dir_name'];

    if ($row['dir_type'] == '1') {
        $sub_array[] = 'Director';
    } else if ($row['dir_type'] == '2') {
        $sub_array[] = 'Executive Director';
    }

    $sub_array[] = $row['place'];
    $sub_array[] = $row['taluk'];
    $sub_array[] = $row['district'];
    $sub_array[] = $row['mobile_no'];

    $status      = $row['status'];

    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id          = $row['dir_id'];

    $action = "<a href='director_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
    <a href='director_creation&del=$id' title='Edit details' class='delete_dir'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM director_creation";
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
