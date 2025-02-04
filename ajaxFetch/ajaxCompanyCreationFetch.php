<?php
@session_start();
include('..\ajaxconfig.php');

$column = array(
    'company_id',
    'company_name',
    'place',
    'taluk',
    'district',
    'mobile',
    'status',
);

$query = "SELECT * FROM company_creation ";
if (isset($_POST['search']) && $_POST['search'] != "") {

    if ($_POST['search'] == "Active") {
        $query .= "WHERE status=0 ";
    } else if ($_POST['search'] == "Inactive") {
        $query .= "WHERE status=1 ";
    } else {
        $query .= "WHERE
        company_id LIKE  '%" . $_POST['search'] . "%'
        OR company_name LIKE '%" . $_POST['search'] . "%'
        OR district LIKE '%" . $_POST['search'] . "%'
        OR taluk LIKE '%" . $_POST['search'] . "%'
        OR place LIKE '%" . $_POST['search'] . "%'
        OR mobile LIKE '%" . $_POST['search'] . "%'
        OR status LIKE '%" . $_POST['search'] . "%' ";
    }
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

    $sub_array[] = $row['company_name'];
    $sub_array[] = $row['place'];
    $sub_array[] = $row['taluk'];
    $sub_array[] = $row['district'];
    $sub_array[] = $row['mobile'];

    $status      = $row['status'];


    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id          = $row['company_id'];

    $action = "<a href='company_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM company_creation";
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
