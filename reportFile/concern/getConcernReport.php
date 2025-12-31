<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}

$user_based = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') { //Report access individual.
        $user_based = "AND cc.insert_user_id = '$userid' ";
    }
}

$from_date = date('Y-m-d', strtotime($_POST['from_date']));
$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$where  = "(date(cc.created_date) >= '" . $from_date . "') and (date(cc.created_date) <= '" . $to_date . "') ";


$where  .= $user_based;
$raising_arr = [1 => 'Myself', 3 => 'Agent', 4 => 'Customer'];
$concern_status = [0 => 'In Progress', 1 => 'Resolved', 2 => 'Removed'];
$loc_arr = [1 => 'Office', 2 => 'On Spot', 3 => 'Customer Spot'];
$comm_arr = [1 => 'Phone', 2 => 'Direct'];

$column = array(
    'cc.id',
    'cc.com_code',
    'cc.com_date',
     'u.fullname',
    'cc.raising_for',
    'cc.raising_for',
    'cc.cus_name',
    'cs.concern_subject',
    'cc.to_dept_name',
    'cc.com_remark',
    'sc.staff_name',
    'scs.staff_name',
    'cc.solution_date',
    'cc.communication',
    'cc.uploads',
    'cc.location',
    'cc.sol_participants',
    'cc.solution_remark',
    'cc.status'
);

$query = "SELECT cc.id, cc.com_code,cc.com_date,cc.raising_for,cc.self_name,cc.cus_name,cs.concern_subject,cc.com_remark,sc.staff_name, cc.status,cc.solution_date,cc.communication,cc.location, cc.sol_participants,cc.solution_remark,cc.uploads ,cc.self_code,cc.cus_id,ag.ag_name,cc.to_dept_name,u.fullname,ag.ag_code,cc.pass_to,scs.staff_name as pass_staff FROM concern_creation cc LEFT JOIN concern_subject cs ON cc.com_sub = cs.concern_sub_id LEFT JOIN agent_creation ag ON cc.ag_name = ag.ag_id LEFT JOIN staff_creation sc ON cc.staff_assign_to = sc.staff_id LEFT JOIN staff_creation scs ON cc.pass_to = scs.staff_id 
  LEFT JOIN user u ON cc.insert_user_id = u.user_id
WHERE $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .=     " AND (cc.com_code LIKE '%" . $search . "%'
        OR cc.com_date LIKE '%" . $search . "%'
        OR cc.cus_name LIKE '%" . $search . "%'
        OR cc.self_name LIKE '%" . $search . "%'
        OR cs.concern_subject LIKE '%" . $search . "%'
        OR cc.solution_date LIKE '%" . $search . "%' )";
    }
}


if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}
// echo $query;die;
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

    $sub_array[] = $sno++;
    $sub_array[] = isset($row['com_code']) ? $row['com_code'] : '';
    $sub_array[] = isset($row['com_date']) ? date('d-m-Y', strtotime($row['com_date'])) : '';
     $sub_array[] = $row['fullname'];
    $sub_array[] = isset($raising_arr[$row['raising_for']]) ? $raising_arr[$row['raising_for']] : '';
    if ($row['raising_for'] == 1) {
        $sub_array[] = isset($row['self_code']) ? $row['self_code'] : '';
        $sub_array[] = isset($row['self_name']) ? $row['self_name'] : '';
    } else if ($row['raising_for'] == 3) {
        $sub_array[] = isset($row['ag_code']) ? $row['ag_code'] : '';
        $sub_array[] = isset($row['ag_name']) ? $row['ag_name'] : '';
    } else if ($row['raising_for'] == 4) {
        $sub_array[] = isset($row['cus_id']) ? $row['cus_id'] : '';
        $sub_array[] = isset($row['cus_name']) ? $row['cus_name'] : '';
    }
    $sub_array[] = isset($row['concern_subject']) ? $row['concern_subject'] : '';
    $sub_array[] = isset($row['to_dept_name']) ? $row['to_dept_name'] : '';
    $sub_array[] = isset($row['com_remark']) ? $row['com_remark'] : '';
    $sub_array[] = isset($row['staff_name']) ? $row['staff_name'] : '';
    $sub_array[] = isset($row['pass_staff']) ? $row['pass_staff'] : '';
    $sub_array[] = (!empty($row['solution_date']) && $row['solution_date'] != '0000-00-00')
    ? date('d-m-Y', strtotime($row['solution_date']))
    : '';

    $sub_array[] = isset($comm_arr[$row['communication']]) ? $comm_arr[$row['communication']] : '';
    if (!empty($row['uploads'])) {
        $filePath = 'uploads/concern/' . $row['uploads'];
        $sub_array[] = '<a href="' . $filePath . '" target="_blank">' . $row['uploads'] . '</a>';
    } else {
        $sub_array[] = '';
    }
    $sub_array[] = isset($loc_arr[$row['location']]) ? $loc_arr[$row['location']] : '';
    $sub_array[] = isset($row['sol_participants']) ? $row['sol_participants'] : '';
    $sub_array[] = isset($row['solution_remark']) ? $row['solution_remark'] : '';
    $sub_array[] = isset($concern_status[$row['status']]) ? $concern_status[$row['status']] : '';

    $data[]      = $sub_array;
}
$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function count_all_data($connect)
{
    $query     = "SELECT id FROM concern_creation ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

// Close the database connection
$connect = null;
