<?php

session_start();
include '../../ajaxconfig.php';

$where = "";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "AND (date(c.created_date) >= '" . $from_date . "') AND (date(c.created_date) <= '" . $to_date . "')";
}

$user_ids = $_POST['user_id'] ?? '';
$user_ids = preg_replace('/[^0-9,]/', '', $user_ids); // clean
$id_list = implode(',', array_filter(explode(',', $user_ids), 'is_numeric'));
if (!empty($id_list)) {
    $where .= " AND c.insert_login_id IN ($id_list) ";
}

$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];
$comm_err = [1 => 'Error', 2 => 'Clear'];
$ftype = [1 => 'Direct', 2 => 'Mobile' , 8 =>'Paid'];
$fstatus = [1 => 'Commitment', 2 => 'Unavailable', 3 => 'RNR', 4 => 'Not Reachable', 5 => 'Switch Off', 6 => 'Not in Use', 7 => 'Blocked', 8 =>'Paid'];
$per_type_arr = [1 => 'Customer', 2 => 'Guarantor', 3 => 'Family Member'];

$column = array(
    'c.id',
    'c.cus_id',
    'cr.autogen_cus_id',
    'c.created_date',
    'c.created_date',
    'alc.area_name',
    'c.ftype',
    'c.fstatus',
    'c.person_type',
    'c.person_name',
    'c.relationship',
    'c.remark',
    'c.comm_date',
    'u.role',
    'u.fullname',
    'c.hint',
    'c.comm_err'
);

$query = "SELECT 
    c.cus_id,
    cr.autogen_cus_id,
    c.created_date,
    alc.area_name,
    c.ftype,
    c.fstatus,
    c.person_type,
    c.person_name,
    c.relationship,
    c.remark,
    c.comm_date,
    u.role,
    u.fullname,
    c.hint,
    c.comm_err
FROM 
    commitment c
LEFT JOIN 
    user u ON u.user_id = c.insert_login_id
JOIN 
    acknowlegement_customer_profile cp ON c.req_id = cp.req_id
JOIN 
    customer_register cr ON cp.cus_id = cr.cus_id
JOIN 
    area_list_creation alc ON cp.area_confirm_area = alc.area_id   
WHERE 1
    $where";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (c.created_date LIKE '%" . $_POST['search'] . "%' OR
            c.cus_id LIKE '%" . $_POST['search'] . "%' OR
            cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%' OR
            alc.area_name LIKE '%" . $_POST['search'] . "%' OR
            c.ftype LIKE '%" . $_POST['search'] . "%' OR
            c.fstatus LIKE '%" . $_POST['search'] . "%' OR
            c.person_type LIKE '%" . $_POST['search'] . "%' OR
            c.person_name LIKE '%" . $_POST['search'] . "%' OR
            c.relationship LIKE '%" . $_POST['search'] . "%' OR
            c.remark LIKE '%" . $_POST['search'] . "%' OR
            c.comm_date LIKE '%" . $_POST['search'] . "%' OR
            u.role LIKE '%" . $_POST['search'] . "%' OR
            u.fullname LIKE '%" . $_POST['search'] . "%' OR
            c.hint LIKE '%" . $_POST['search'] . "%' )";
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
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['created_date']));
    $sub_array[] = date('h:i:s A', strtotime($row['created_date']));
    $sub_array[] = $row['area_name'];
    $sub_array[] = isset($ftype[$row['ftype']]) ? $ftype[$row['ftype']] : '';
    $sub_array[] = isset($fstatus[$row['fstatus']]) ? $fstatus[$row['fstatus']] : '';
    $sub_array[] = isset($per_type_arr[$row['person_type']]) ? $per_type_arr[$row['person_type']] : '';

    // Fetch person name based on person type
    $name = '';
    $relationship = '';
    if ($row['person_type'] == 1) {
        $name = getCustomer($connect, $row['cus_id']);
        $relationship = "NIL";
    } elseif ($row['person_type'] == 2) {
        $person_name = getGarentor($connect, $row['cus_id']);
        $name =  $person_name['name'];
        $relationship = $person_name['relationship'];
    } elseif ($row['person_type'] == 3) {
        $person_name = getFamilyMember($connect, $row['person_name']);
        $name =  $person_name['name'];
        $relationship = $person_name['relationship'];
    }

    $sub_array[] = $name;
    $sub_array[] = $relationship;
    $sub_array[] = $row['remark'];
    $sub_array[] = (!empty($row['comm_date']) && $row['comm_date'] != '0000-00-00') ? date('d-m-Y', strtotime($row['comm_date'])): '';
    $sub_array[] = isset($role_arr[$row['role']]) ? $role_arr[$row['role']] : '';
    $sub_array[] = $row['fullname'];
    $sub_array[] = $row['hint'];
    $sub_array[] = isset($comm_err[$row['comm_err']]) ? $comm_err[$row['comm_err']] : '';
    $data[] = $sub_array;
    $sno++;
}

function getCustomer($connect, $cus_id)
{
    $result = $connect->query("SELECT customer_name from customer_register where cus_id = '$cus_id' ");
    $cus_name = $result->fetch()['customer_name'];
    return $cus_name;
}

function getGarentor($connect, $cus_id)
{
    $query = "SELECT cp.guarentor_name, vfi.famname, vfi.relationship FROM customer_profile cp JOIN verification_family_info vfi ON cp.guarentor_name = vfi.id WHERE cp.cus_id = '$cus_id' ORDER BY cp.id DESC LIMIT 1 ";
    $result = $connect->query($query);
    $row = $result->fetch();
    $response = [
        "name" => $row['famname'],
        "relationship" => $row['relationship']
    ];
    return $response;
}

function getFamilyMember($connect, $fam_id)
{
    $result = $connect->query("SELECT id,famname,relationship FROM `verification_family_info` where id='$fam_id'");
    $row = $result->fetch();
    $fam_name = $row['famname'];
    $relationship = $row['relationship'];
    $response = array("name" => $fam_name, "relationship" => $relationship);
    return $response;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT count(id) as count FROM commitment where 1 ");
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
