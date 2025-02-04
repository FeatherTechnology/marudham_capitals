<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'ls.scheme_id',
    'ls.scheme_name',
    'lcc.loan_category_creation_name',
    'ls.sub_category',
    'ls.due_method',
    'ls.scheme_id',
    'ls.scheme_id',
    'ls.status'
);
$query = "SELECT ls.*,lcc.loan_category_creation_name as loan_category FROM loan_scheme ls JOIN loan_category_creation lcc ON ls.loan_category = lcc.loan_category_creation_id and lcc.status = 0 WHERE ls.due_method = 'weekly' ";

if (isset($_POST['search']) && $_POST['search'] != "") {

    $query .= "
            and (ls.scheme_name LIKE '%" . $_POST['search'] . "%'
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
            OR ls.sub_category LIKE '%" . $_POST['search'] . "%'
            OR ls.due_method LIKE '%" . $_POST['search'] . "%'
            OR ls.intrest_rate LIKE '%" . $_POST['search'] . "%'
            OR ls.due_period LIKE '%" . $_POST['search'] . "%' ) ";
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

    if ($sno != "") {
        $sub_array[] = $sno;
    }
    $sub_array[] = $row["scheme_name"];

    $sub_array[] = $row['loan_category'];
    $sub_array[] = $row["sub_category"];

    //for Due method
    if ($row["due_method"] == 'monthly') {
        $sub_array[] = 'Monthly';
    } elseif ($row["due_method"] == 'weekly') {
        $sub_array[] = 'Weekly';
    } elseif ($row["due_method"] == 'daily') {
        $sub_array[] = 'Daily';
    }

    // $sub_array[] = $row["intrest_rate"] . '%';
    $sub_array[] = $row["due_period"];

    $status      = $row['status'];
    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id   = $row['scheme_id'];

    $action = "<a href='loan_scheme&upd=$id&type=weekly' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
	<a href='loan_scheme&del=$id&type=weekly' title='Delete details' class='delete_loan_scheme'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM loan_scheme";
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
