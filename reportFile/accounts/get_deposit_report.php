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
        $user_based = " AND insert_login_id = '$userid' "; // This will be used inside each subquery
    }
}

$from_date = '';
$to_date = '';

if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
}
$column = array(
    'tdate',
    'ctype',
    'name',     
    'Credit',
    'Debit',
    'Amount'
);

$query = "
SELECT * FROM (
    SELECT cdh.created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name 
    FROM ct_db_hdeposit cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id
    WHERE DATE(cdh.created_date) BETWEEN '$from_date' AND '$to_date'" . ($user_based != "" ? " AND cdh.insert_login_id = '$userid'" : "") . "

    UNION ALL 

    SELECT cdb.created_date AS tdate, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name 
    FROM ct_db_bdeposit cdb 
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE DATE(cdb.created_date) BETWEEN '$from_date' AND '$to_date'" . ($user_based != "" ? " AND cdb.insert_login_id = '$userid'" : "") . "

    UNION ALL 

    SELECT cch.created_date AS tdate, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name 
    FROM ct_cr_hdeposit cch 
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE DATE(cch.created_date) BETWEEN '$from_date' AND '$to_date'" . ($user_based != "" ? " AND cch.insert_login_id = '$userid'" : "") . "

    UNION ALL 

    SELECT ccb.created_date AS tdate, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name 
    FROM ct_cr_bdeposit ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE DATE(ccb.created_date) BETWEEN '$from_date' AND '$to_date'" . ($user_based != "" ? " AND ccb.insert_login_id = '$userid'" : "") . "
) AS sub
";

// Optional search filter
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $query .= " WHERE sub.tdate LIKE '%$search%' 
                OR sub.ctype LIKE '%$search%' 
                OR sub.Credit LIKE '%$search%' 
                OR sub.Debit LIKE '%$search%' 
                OR sub.Amount LIKE '%$search%' 
                OR sub.name LIKE '%$search%' ";
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
        $sub_array[] = $row['name'];
    $sub_array[] = $bname;
    $sub_array[] = $row['Credit'];
    $sub_array[] = $row['Debit'];
    $sub_array[] = $row['Amount'];

    $data[] = $sub_array;
}

// Count all rows across all 4 tables
function count_all_data($connect)
{
    $count = 0;
    $tables = ['ct_db_hinvest', 'ct_db_binvest', 'ct_cr_hinvest', 'ct_cr_binvest'];
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
