<?php
@session_start();
include('..\ajaxconfig.php');

$column = array(
    'u.user_id',
    'u.role',
    'u.role_type',
    'u.fullname',
    'u.user_name',
    'c.company_name',
    'u.branch_id',
    'u.line_id',
    'u.group_id',
    'u.status',
    'u.user_id'
);

$query = "SELECT u.*,c.company_name,
    (SELECT GROUP_CONCAT(bc.branch_name SEPARATOR ', ')
    FROM branch_creation bc 
    WHERE FIND_IN_SET(bc.branch_id, u.branch_id) AND bc.status = 0) AS branch_names,
    (SELECT GROUP_CONCAT(alm.line_name SEPARATOR ', ')
    FROM area_line_mapping alm 
    WHERE FIND_IN_SET(alm.map_id, u.line_id) AND alm.status = 0) AS line_names,
    (SELECT GROUP_CONCAT(agm.group_name SEPARATOR ', ')
    FROM area_group_mapping agm 
    WHERE FIND_IN_SET(agm.map_id, u.group_id) AND agm.status = 0) AS group_names
    FROM user u
    JOIN company_creation c ON c.company_id = u.company_id
    WHERE u.user_id != 1 ";
if (isset($_POST['search']) && $_POST['search'] != "") {
    $query .= "
                and (u.role LIKE '%" . $_POST['search'] . "%'
                OR u.role_type LIKE '%" . $_POST['search'] . "%'
                OR u.fullname LIKE '%" . $_POST['search'] . "%'
                OR u.user_name LIKE '%" . $_POST['search'] . "%'
                OR c.company_name LIKE '%" . $_POST['search'] . "%'
                OR (SELECT GROUP_CONCAT(bc.branch_name SEPARATOR ', ')
                FROM branch_creation bc 
                WHERE FIND_IN_SET(bc.branch_id, u.branch_id) AND bc.status = 0) LIKE '%" . $_POST['search'] . "%'
                OR (SELECT GROUP_CONCAT(alm.line_name SEPARATOR ', ')
                FROM area_line_mapping alm 
                WHERE FIND_IN_SET(alm.map_id, u.line_id) AND alm.status = 0) LIKE '%" . $_POST['search'] . "%'
                OR (SELECT GROUP_CONCAT(agm.group_name SEPARATOR ', ')
                FROM area_group_mapping agm 
                WHERE FIND_IN_SET(agm.map_id, u.group_id) AND agm.status = 0) LIKE '%" . $_POST['search'] . "%') ";
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

    $role_id = $row['role'];
    if ($role_id == '1') {
        $sub_array[] = 'Director';
    } else if ($role_id == '2') {
        $sub_array[] = 'Agent';
    } else if ($role_id == '3') {
        $sub_array[] = 'Staff';
    }

    $role_type_id = $row['role_type'];
    if ($role_type_id == '11' and $role_id == '1') {
        $sub_array[] = 'Director';
    } else if ($role_type_id == '12' and $role_id == '1') {
        $sub_array[] = 'Executive Director';
    } else if ($role_type_id == Null and $role_id == '2') {
        $sub_array[] = 'Agent';
    } else if ($role_type_id > 0 and $role_id == '3') {
        $getQry = "SELECT * from staff_type_creation where staff_type_id = '" . $role_type_id . "' and status = 0 ";
        $res = $connect->query($getQry);
        $row1 = $res->fetch();
        $sub_array[] = $row1["staff_type_name"];
    }

    $sub_array[] = $row['fullname'];
    $sub_array[] = $row['user_name'];
    $sub_array[] = $row["company_name"];
    $sub_array[] = $row["branch_names"];
    $sub_array[] = $row["line_names"];
    $sub_array[] = $row['group_names'];

    $status      = $row['status'];

    if ($status == 1) {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
    } else {
        $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
    }
    $id          = $row['user_id'];

    $action = "<a href='manage_user&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
    <a href='manage_user&del=$id' title='Edit details' class='delete_user'><span class='icon-trash-2'></span></a>";

    $sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM user";
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