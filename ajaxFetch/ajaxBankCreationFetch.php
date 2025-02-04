<?php
@session_start();
include('..\ajaxconfig.php');

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'id',
    'bank_name',
    'acc_no',
    'branch',
    'id'
);

$query = "SELECT * from bank_creation where status = 0 ";

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "and
        (bank_name LIKE '%" . $_POST['search'] . "%'
        OR acc_no LIKE '%" . $_POST['search'] . "%'
        OR branch LIKE '%" . $_POST['search'] . "%') ";
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

    $sub_array[] = $row['bank_name'];
    $sub_array[] = $row['acc_no'];

    $sub_array[] = $row['branch'];

    $id = $row['id'];

    $action = "<a href='bank_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
    <!--<a href='bank_creation&del=$id' title='Delete details' class='delete_bank'><span class='icon-trash-2'></span></a>-->";


    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * from bank_creation where status = 0";
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
