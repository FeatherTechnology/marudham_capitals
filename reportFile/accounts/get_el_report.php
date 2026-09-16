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
    'transaction_date',
    'ctype',
    'name',     
    'Credit',
    'Debit',
    'Amount'
);

$query = "
SELECT * FROM (
    SELECT cdh.created_date AS tdate, '' AS transaction_date, 'Hand Cash' AS ctype, '' AS Credit, cdh.amt AS Debit, cdh.amt AS Amount, ndc.name, cdh.area, cdh.ident, cdh.remark 
    FROM ct_db_hel cdh
    JOIN name_detail_creation ndc ON cdh.name_id = ndc.name_id 
    WHERE DATE(cdh.created_date) BETWEEN '$from_date' AND '$to_date'" . 
    ($user_based != "" ? " AND cdh.insert_login_id = '$userid'" : "") . "

    UNION ALL 

    SELECT cdb.updated_date AS tdate, cdb.created_date AS transaction_date, cdb.bank_id AS ctype, '' AS Credit, cdb.amt AS Debit, cdb.amt AS Amount, ndc.name, cdb.area, cdb.ident, cdb.remark 
    FROM ct_db_bel cdb 
    JOIN name_detail_creation ndc ON cdb.name_id = ndc.name_id
    WHERE DATE(cdb.updated_date) BETWEEN '$from_date' AND '$to_date'" . 
    ($user_based != "" ? " AND cdb.insert_login_id = '$userid'" : "") . "

    UNION ALL 

    SELECT cch.created_date AS tdate, '' AS transaction_date, 'Hand Cash' AS ctype, cch.amt AS Credit, '' AS Debit, cch.amt AS Amount, ndc.name, cch.area, cch.ident, cch.remark 
    FROM ct_cr_hel cch
    JOIN name_detail_creation ndc ON cch.name_id = ndc.name_id
    WHERE DATE(cch.created_date) BETWEEN '$from_date' AND '$to_date'" . 
    ($user_based != "" ? " AND cch.insert_login_id = '$userid'" : "") . "

    UNION ALL 

    SELECT ccb.updated_date AS tdate, ccb.created_date AS transaction_date, ccb.bank_id AS ctype, ccb.amt AS Credit, '' AS Debit, ccb.amt AS Amount, ndc.name, ccb.area, ccb.ident, ccb.remark 
    FROM ct_cr_bel ccb
    JOIN name_detail_creation ndc ON ccb.name_id = ndc.name_id
    WHERE DATE(ccb.updated_date) BETWEEN '$from_date' AND '$to_date'" . 
    ($user_based != "" ? " AND ccb.insert_login_id = '$userid'" : "") . "
) AS sub
";


// Optional search filter
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $query .= " WHERE sub.tdate LIKE '%$search%' 
                OR sub.transaction_date LIKE '%$search%'
                OR ndc.name  LIKE '%$search%' 
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

$data = [];
$sno = 1;
foreach ($result as $row) {
    if ($row['ctype'] != 'Hand Cash') {
        $bnameqry = $connect->query("SELECT short_name, acc_no FROM bank_creation WHERE id = '" . $row['ctype'] . "' ");
        $bnamerun = $bnameqry->fetch();
        $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);
    } else {
        $bname = $row['ctype'];
    }

    $data[] = [
        $sno++,
        date('d-m-Y', strtotime($row['tdate'])),
        !empty($row['transaction_date']) ? date('d-m-Y', strtotime($row['transaction_date'])) : '',
        $row['name'],
        $row['area'],
        $row['ident'],
        $row['remark'],
        $bname,
        moneyFormatIndia($row['Credit']),
        moneyFormatIndia($row['Debit']),
        moneyFormatIndia($row['Amount']),
    ];
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
$connect = null;
