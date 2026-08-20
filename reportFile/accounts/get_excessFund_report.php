<?php
require "../../ajaxconfig.php";
require "../../moneyFormatIndia.php";
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
    $where = " (DATE(updated_date) >= '$from_date') AND (DATE(updated_date) <= '$to_date') ";
}

$where .= $user_based;

$column = array(
    'tdate',
    'transaction_date',
    'ctype',
    'ref_code',
    'ctype',
    'remark',
    'trans_id',
    'Debit'
);

// Wrap UNION ALL in subquery
$query = "
SELECT updated_date AS tdate, created_date AS transaction_date, bank_id AS ctype, ref_code, remark, trans_id, amt AS Debit
    FROM ct_db_exf 
    WHERE $where
";

// Search filter
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $query .= " WHERE updated_date LIKE '%$search%' OR transaction_date LIKE '%$search%' OR ctype LIKE '%$search%' OR ref_code LIKE '%$search%' OR trans_id LIKE '%$search%' OR amt LIKE '%$search%' ";
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
    $sub_array[] = date('d-m-Y', strtotime($row['transaction_date']));
    $sub_array[] = $bname;
    $sub_array[] = $row['ref_code'];
    $sub_array[] = $row['remark'];
    $sub_array[] = $row['trans_id'];
    $sub_array[] = moneyFormatIndia($row['Debit']);

    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
$connect = null;
