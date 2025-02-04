<?php
@session_start();
include('..\ajaxconfig.php');

$column = array(
    'ac.ag_id',
    'c.company_name',
    'ac.ag_name',
    'agc.agent_group_name',
    'ac.place',
    'ac.district',
    'ac.ag_id',
    'ac.sub_category',
    'ac.status',
    'ac.ag_id',
);

$query = "SELECT ac.*,c.company_name,agc.agent_group_name,
    (SELECT GROUP_CONCAT(lcc.loan_category_creation_name SEPARATOR ', ') FROM loan_category_creation lcc WHERE FIND_IN_SET(lcc.loan_category_creation_id,ac.loan_category) and lcc.status = 0 ) AS loan_category
    FROM agent_creation ac 
    JOIN company_creation c ON c.company_id = ac.company_id and c.status = 0
    JOIN agent_group_creation agc ON agc.agent_group_id = ac.ag_group_id and agc.status = 0
    WHERE 1 ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= " AND
                (ag_name LIKE '%" . $_POST['search'] . "%'
                OR c.company_name LIKE '%" . $_POST['search'] . "%'
                OR agc.agent_group_name LIKE '%" . $_POST['search'] . "%'
                OR (SELECT GROUP_CONCAT(lcc.loan_category_creation_name SEPARATOR ', ') FROM loan_category_creation lcc 
                WHERE FIND_IN_SET(lcc.loan_category_creation_id,ac.loan_category) and lcc.status = 0) LIKE '%" . $_POST['search'] . "%'
                OR ac.place LIKE '%" . $_POST['search'] . "%'
                OR ac.district LIKE '%" . $_POST['search'] . "%'
                OR ac.loan_category LIKE '%" . $_POST['search'] . "%'
                OR ac.sub_category LIKE '%" . $_POST['search'] . "%') ";
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
    $sub_array[] = $row['ag_name'];
    $sub_array[] = $row['agent_group_name'];

    $sub_array[] = $row['place'];
    $sub_array[] = $row['district'];

    $sub_array[] = $row['loan_category'];

    $sub_array[] = $row["sub_category"];

    $status      = $row['status'];

    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id          = $row['ag_id'];

    $action = "<a href='agent_creation&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
    <a href='agent_creation&del=$id' title='Edit details' class='delete_ag'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM agent_creation";
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
