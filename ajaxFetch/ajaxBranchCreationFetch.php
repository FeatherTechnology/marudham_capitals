<?php
include('../ajaxconfig.php');
@session_start();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$column = array(
    'branch_id',
    'branch_code',
    'company_name',
    'branch_name',
    'place',
    'state',
    'district',
    'mobile_number',
    'email_id',
    'status',
    'status'
);

$query = "SELECT * FROM branch_creation WHERE 1 ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= " and
            (branch_code LIKE '%" . $_POST['search'] . "%'
            OR company_name LIKE '%" . $_POST['search'] . "%'
            OR branch_name LIKE '%" . $_POST['search'] . "%'
            OR place LIKE '%" . $_POST['search'] . "%'
            OR state LIKE '%" . $_POST['search'] . "%'
            OR district LIKE '%" . $_POST['search'] . "%'
            OR mobile_number LIKE '%" . $_POST['search'] . "%'
            OR email_id LIKE '%" . $_POST['search'] . "%') ";
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

    // Fetch Company Name
    $CompanyName = $row['company_name'];
    $getqry = "SELECT company_name FROM company_creation WHERE company_id ='" . strip_tags($CompanyName) . "' and status = 0";
    $res12 = $connect->query($getqry);
    while ($row12 = $res12->fetch()) {
        $company_name = $row12["company_name"];
    }

    $sub_array[] = $row['branch_code'];
    $sub_array[] = $company_name;
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['place'];
    $sub_array[] = $row['state'];
    $sub_array[] = $row['district'];
    $sub_array[] = $row['mobile_number'];
    $sub_array[] = $row['email_id'];
    $status      = $row['status'];
    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id   = $row['branch_id'];

    $action = "<a href='branch_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
	<a href='branch_creation&del=$id' title='Delete details' class='delete_branch'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM branch_creation";
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