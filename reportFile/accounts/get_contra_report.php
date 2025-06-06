<?php
require "../../ajaxconfig.php";
@session_start();

$i = 0;
$records = array();

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = "";
if ($userid != 1) {
    $userQry = $connect->query("SELECT report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];
    if ($report_access == '1') {
        $user_based = " AND insert_login_id = '$userid' ";
    }
}

$where = "1"; // default where for base queries
if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where = " (DATE(created_date) >= '$from_date' AND DATE(created_date) <= '$to_date') ";
}
$where .= $user_based;

$column = array(
    'tdate',
    'ctype',
    'Credit',
    'Debit',
    'amt'
);

// Main data query
$query = "
SELECT created_date AS tdate, from_bank_id AS ctype, '' AS Credit, amt AS Debit, amt AS Amount FROM ct_db_cash_withdraw 
WHERE $where 

UNION ALL 

SELECT created_date AS tdate, 'Hand Cash' AS ctype, '' AS Credit, amount AS Debit, amount AS Amount FROM ct_db_bank_deposit 
WHERE $where 

UNION ALL 

SELECT created_date AS tdate, 'Hand Cash' AS ctype, amt AS Credit, '' AS Debit, amt AS Amount FROM ct_cr_bank_withdraw 
WHERE $where 

UNION ALL 

SELECT created_date AS tdate, to_bank_id AS ctype, amt AS Credit, '' AS Debit, amt AS Amount FROM ct_cr_cash_deposit 
WHERE $where 
";

// Search filter
if (!empty($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM ($query) AS sub WHERE 
        tdate LIKE '%$search%' OR 
        ctype LIKE '%$search%' OR 
        Credit LIKE '%$search%' OR 
        Debit LIKE '%$search%'";
}

// Order
if (!empty($_POST['order'])) {
    $colIndex = $_POST['order'][0]['column'];
    $colDir = $_POST['order'][0]['dir'];
    $query .= " ORDER BY " . $column[$colIndex] . " $colDir";
}

// Pagination
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
    if ($row['ctype'] != 'Hand Cash') {
        $bnameqry = $connect->query("SELECT short_name,acc_no from bank_creation where id = '" . $row['ctype'] . "' ");
        $bnamerun = $bnameqry->fetch();
        $bname = $bnamerun['short_name'] . ' - ' . substr($bnamerun['acc_no'], -5);
    } else {
        $bname = $row['ctype'];
    }
    $sub_array = array();
    $sub_array[] = $sno;
    $sub_array[] = date('d-m-Y', strtotime($row['tdate']));
    $sub_array[] =  $bname;
    $sub_array[] = $row['Credit'];
    $sub_array[] = $row['Debit'];
    $data[] = $sub_array;
    $sno++;
}

// Indian currency formatting (not used here but kept for future)
function moneyFormatIndia($num1)
{
    if ($num1 < 0) {
        $num = str_replace("-", "", $num1);
    } else {
        $num = $num1;
    }
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, -3);
        $restunits = substr($num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $i => $unit) {
            $explrestunits .= ($i == 0 ? (int)$unit : $unit) . ",";
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $num1 < 0 ? "-" . $thecash : $thecash;
}

// Count all data
function count_all_data($connect)
{
    $total = 0;
    $tables = ['ct_db_cash_withdraw', 'ct_db_bank_deposit', 'ct_cr_bank_withdraw', 'ct_cr_cash_deposit'];
    foreach ($tables as $table) {
        $stmt = $connect->query("SELECT COUNT(*) AS cnt FROM $table");
        $row = $stmt->fetch();
        $total += (int)$row['cnt'];
    }
    return $total;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close DB
$connect = null;
