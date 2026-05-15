<?php
session_start();
include '../../ajaxconfig.php';

$userid = $_SESSION["userid"] ?? '';

$where = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') { //Report access individual.
        $where = " cc.insert_user_id = '$userid' AND ";
    }
}

$from_date = date('Y-m-d', strtotime($_POST['from_date']));
$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$where  .= " (date(cc.created_date) >= '" . $from_date . "') and (date(cc.created_date) <= '" . $to_date . "') ";

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
    'cdn.dep_name',
    'cc.com_remark',
    'us.fullname',
    'up.fullname',
    'cc.solution_date',
    'cc.communication',
    'cc.uploads',
    'cc.location',
    'cc.sol_participants',
    'cc.solution_remark',
    'cc.status'
);

$base_query = "FROM concern_creation cc 
LEFT JOIN concern_subject cs ON cc.com_sub = cs.concern_sub_id 
LEFT JOIN agent_creation ag ON cc.ag_name = ag.ag_id 
LEFT JOIN user us ON cc.staff_assign_to = us.user_id 
LEFT JOIN user up ON cc.pass_to = up.user_id 
LEFT JOIN user u ON cc.insert_user_id = u.user_id
LEFT JOIN concern_dept_name cdn ON cc.to_dept_name = cdn.id
WHERE $where ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $base_query .= " AND (cc.com_code LIKE '%" . $search . "%'
        OR cc.com_date LIKE '%" . $search . "%'
        OR cc.cus_name LIKE '%" . $search . "%'
        OR cc.self_name LIKE '%" . $search . "%'
        OR cs.concern_subject LIKE '%" . $search . "%'
        OR cc.solution_date LIKE '%" . $search . "%' )";
}

/* ---------- ORDER ---------- */
$orderBy = '';
if (isset($_POST['order'])) {
    $orderBy = " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
}

/* ---------- Pagination ---------- */
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

/* ---------- Total records ---------- */
$statement = $connect->prepare("SELECT COUNT(*) FROM concern_creation ");
$statement->execute();
$recordsTotal = (int) $statement->fetchColumn();

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $base_query");
$countStmt->execute();
$number_filter_row = (int) $countStmt->fetchColumn();

$data_query = "SELECT cc.id, cc.com_code, cc.com_date, cc.raising_for, cc.self_name, cc.cus_name, cs.concern_subject, cc.com_remark, us.fullname AS staff_name, cc.status, cc.solution_date, cc.communication, cc.location, cc.sol_participants, cc.solution_remark, cc.uploads, cc.self_code, cc.cus_id, ag.ag_name, cdn.dep_name, u.fullname, ag.ag_code, cc.pass_to, up.fullname AS pass_staff $base_query $orderBy $limit ";

$statement = $connect->prepare($data_query);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();

    $sub_array[] = $sno++;
    $sub_array[] = $row['com_code'] ?? '';
    $sub_array[] = isset($row['com_date']) ? date('d-m-Y', strtotime($row['com_date'])) : '';
    $sub_array[] = $row['fullname'];
    $sub_array[] = $raising_arr[$row['raising_for']] ?? '';

    if ($row['raising_for'] == 1) {
        $sub_array[] = $row['self_code'] ?? '';
        $sub_array[] = $row['self_name'] ?? '';
    } else if ($row['raising_for'] == 3) {
        $sub_array[] = $row['ag_code'] ?? '';
        $sub_array[] = $row['ag_name'] ?? '';
    } else if ($row['raising_for'] == 4) {
        $sub_array[] = $row['cus_id'] ?? '';
        $sub_array[] = $row['cus_name'] ?? '';
    }

    $sub_array[] = $row['concern_subject'] ?? '';
    $sub_array[] = $row['dep_name'] ?? '';     
    $sub_array[] = $row['com_remark'] ?? '';
    $sub_array[] = $row['staff_name'] ?? '';
    $sub_array[] = $row['pass_staff'] ?? '';
    $sub_array[] = (!empty($row['solution_date']) && $row['solution_date'] != '0000-00-00')
    ? date('d-m-Y', strtotime($row['solution_date']))
    : '';

    $sub_array[] = $comm_arr[$row['communication']] ?? '';
    
    if (!empty($row['uploads'])) {
        $filePath = 'uploads/concern/' . $row['uploads'];
        $sub_array[] = '<a href="' . $filePath . '" target="_blank">' . $row['uploads'] . '</a>';
    } else {
        $sub_array[] = '';
    }

    $sub_array[] = $loc_arr[$row['location']] ?? '';
    $sub_array[] = $row['sol_participants'] ?? '';
    $sub_array[] = $row['solution_remark'] ?? '';
    $sub_array[] = $concern_status[$row['status']] ?? '';

    $data[]      = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
