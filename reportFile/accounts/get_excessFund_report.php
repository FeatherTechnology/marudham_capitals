<?php
require "../../ajaxconfig.php";
@session_start();

$records = array();
$userid = $_SESSION["userid"] ?? 0;
$report_access = '2'; // default: super admin

$user_based = "";
if ($userid != 1) {
    $userQry = $connect->query("SELECT report_access FROM user WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') {
        $user_based = " AND insert_login_id = '$userid' ";
    }
}

$where = "1"; // default condition
if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where = " (DATE(created_date) >= '$from_date') AND (DATE(created_date) <= '$to_date') ";
}

$where .= $user_based;

$column = array(
    'tdate',
    'ctype',
    'ref_code',
    'remark',
    'trans_id',
    'Amount'
);

// Wrap UNION ALL in subquery
$query = "
SELECT created_date AS tdate, bank_id AS ctype, ref_code, remark, trans_id, amt AS Debit
    FROM ct_db_exf 
    WHERE $where
";

// Search filter
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $query .= " WHERE created_date LIKE '%$search%' OR ctype LIKE '%$search%' OR ref_code LIKE '%$search%' OR trans_id LIKE '%$search%' OR amt LIKE '%$search%' ";
}

// Ordering
if (isset($_POST['order'])) {
    $order_col_index = $_POST['order'][0]['column'];
    $order_dir = $_POST['order'][0]['dir'];
    $order_col = $column[$order_col_index] ?? 'tdate';
    $query .= " ORDER BY $order_col $order_dir";
} else {
    $query .= " ORDER BY tdate DESC";
}

// Pagination
$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
}

// Execute full query
$statement = $connect->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {

    if ($row['ctype'] != 'Hand Cash') {
        $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
        $bnamerun = $bnameqry->fetch();
        $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);
    } else {
        $bname = $row['ctype'];
    }
    $sub_array = array();
    $sub_array[] = $sno++;
    $sub_array[] = date('d-m-Y', strtotime($row['tdate']));
    $sub_array[] = $bname;
    $sub_array[] = $row['ref_code'];;
    $sub_array[] = $row['remark'];
    $sub_array[] = $row['trans_id'];
    $sub_array[] = $row['Debit'];

    $data[] = $sub_array;
}

// Count all rows across all 4 tables
function count_all_data($connect)
{
    $count = 0;
    $tables = ['ct_db_exf'];
    foreach ($tables as $tbl) {
        $qry = $connect->query("SELECT COUNT(*) as cnt FROM $tbl");
        $count += $qry->fetchColumn();
    }
    return $count;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
$connect = null;
