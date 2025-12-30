<?php

session_start();
include '../../ajaxconfig.php';

$where = "";

if (isset($_POST['selected_date']) && $_POST['selected_date'] != '') {
    $selected_date = date('Y-m-d', strtotime($_POST['selected_date']));
    $where  = "AND np.created_date >= '" . $selected_date . " 00:00:00' 
          AND np.created_date <= '" . $selected_date . " 23:59:59'";
}

$user_ids = $_POST['user_id'] ?? '';
$user_ids = preg_replace('/[^0-9,]/', '', $user_ids); // clean
$id_list = implode(',', array_filter(explode(',', $user_ids), 'is_numeric'));
if (!empty($id_list)) {
    $where .= " AND np.insert_login_id IN ($id_list) ";
}

$column = array(
    'np.id',
    'np.cus_id',
    'cp.autogen_cus_id',
    'COALESCE(cp.customer_name, ncp.cus_name)',
    'np.created_date',
    'np.created_date',
    'COALESCE(cp.mobile1, ncp.mobile)',
    'COALESCE(al.area_name, ncp.area)',
    'COALESCE(sl.sub_area_name, ncp.sub_area)',
    'bc.branch_name',
    'agm.group_name',
    'alm.line_name',
    'np.status',
    'np.remark',
    'np.follow_date',
    'u.role',
    'u.fullname',
    'np.id'
);

$query = "SELECT 
    np.cus_id, 
    cp.autogen_cus_id,
    np.created_date, 
    np.status, 
    np.remark, 
    u.role,
    u.fullname,
    COALESCE(cp.customer_name, ncp.cus_name) AS customer_name,
    COALESCE(cp.mobile1, ncp.mobile) AS mobile1,
    COALESCE(al.area_name, ncp.area) AS area_name,
    COALESCE(sl.sub_area_name, ncp.sub_area) AS sub_area_name,
    bc.branch_name, 
    agm.group_name, 
    alm.line_name, 
    np.follow_date, 
    np.orgin_table
FROM 
    new_promotion np
LEFT JOIN 
    user u ON u.user_id = np.insert_login_id
LEFT JOIN 
    customer_register cp ON np.cus_id = cp.cus_id
LEFT JOIN 
    new_cus_promo ncp ON np.cus_id = ncp.cus_id
LEFT JOIN 
    area_list_creation al ON al.area_id = COALESCE(cp.area, ncp.area)
LEFT JOIN 
    sub_area_list_creation sl ON   sl.sub_area_id = COALESCE(cp.sub_area, ncp.sub_area) 
LEFT JOIN 
    area_group_mapping agm ON FIND_IN_SET(sl.sub_area_id, agm.sub_area_id) 
LEFT JOIN 
    area_line_mapping alm ON FIND_IN_SET(sl.sub_area_id, alm.sub_area_id) 
LEFT JOIN 
    branch_creation bc ON agm.branch_id = bc.branch_id  

WHERE 1 $where";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (np.created_date LIKE '%" . $_POST['search'] . "%' OR
            np.cus_id LIKE '%" . $_POST['search'] . "%' OR
            cp.autogen_cus_id LIKE '%" . $_POST['search'] . "%' OR
            COALESCE(cp.customer_name, ncp.cus_name) LIKE '%" . $_POST['search'] . "%' OR
            COALESCE(al.area_name, ncp.area) LIKE '%" . $_POST['search'] . "%' OR
            COALESCE(sl.sub_area_name, ncp.sub_area) LIKE '%" . $_POST['search'] . "%' OR
            bc.branch_name LIKE '%" . $_POST['search'] . "%' OR
            agm.group_name LIKE '%" . $_POST['search'] . "%' OR
            alm.line_name LIKE '%" . $_POST['search'] . "%' OR
            np.status LIKE '%" . $_POST['search'] . "%' OR
            np.remark LIKE '%" . $_POST['search'] . "%' OR
            u.role LIKE '%" . $_POST['search'] . "%' OR
            u.fullname LIKE '%" . $_POST['search'] . "%' OR
            COALESCE(cp.mobile1, ncp.mobile) LIKE '%" . $_POST['search'] . "%' OR
            np.follow_date LIKE '%" . $_POST['search'] . "%' )";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
}

$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

if ($_POST['length'] != -1) {
    $statement = $connect->prepare($query . $query1);
    $statement->execute();
}
$result = $statement->fetchAll();

$data = array();
$sno = 1;
$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];
$originName = [1 => 'Renewal', 2 => 'New Promotion', 3 => 'Repromotion', 4=> 'Re-active']; 

foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['customer_name'];
    $sub_array[] = date('d-m-Y', strtotime($row['created_date']));
    $sub_array[] = date('h:i:s A', strtotime($row['created_date']));
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['status'];
    $sub_array[] = $row['remark'];
    $sub_array[] = date('d-m-Y', strtotime($row['follow_date']));
    $sub_array[] = isset($role_arr[$row['role']]) ? $role_arr[$row['role']] : '';
    $sub_array[] = $row['fullname'];
    $sub_array[] = isset($originName[$row['orgin_table']]) ? $originName[$row['orgin_table']] : '';

    $data[] = $sub_array;
    $sno++;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT count(id) as count FROM new_promotion where 1 ");
    $statement = $query->fetch();
    return $statement['count'];
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
