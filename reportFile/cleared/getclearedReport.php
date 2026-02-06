<?php

include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';
session_start();
$user_id = $_SESSION['userid'];

$to_date = date('Y-m-d', strtotime($_POST['to_date']));
$from_date = date('Y-m-d', strtotime($_POST['from_date']));

$column = array(
    'bs.id',
    'bc.bank_name',
    'bs.trans_date',
    'bs.narration',
    'bs.trans_id',
    'bs.credit',
    'bs.debit',
    'bs.balance',
    'bs.clr_status'
);

$query = "SELECT 
    bc.bank_name,
    bs.trans_date,
    bs.narration,
    bs.trans_id,
    bs.credit,
    bs.debit,
    bs.balance,
    bs.clr_status
FROM 
    bank_stmt bs
    LEFT JOIN bank_creation bc ON bs.bank_id = bc.id
WHERE 
      DATE(bs.trans_date) BETWEEN '$from_date' AND '$to_date'
    AND bs.clr_status = '1'";
    

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (bc.bank_name LIKE '%" . $_POST['search'] . "%' OR
            bs.trans_date LIKE '%" . $_POST['search'] . "%' OR
            bs.narration LIKE '%" . $_POST['search'] . "%' OR
            bs.trans_id LIKE '%" . $_POST['search'] . "%' OR
            bs.credit LIKE '%" . $_POST['search'] . "%' OR
            bs.debit LIKE '%" . $_POST['search'] . "%' OR
            bs.clr_status LIKE '%" . $_POST['search'] . "%' ) ";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
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
    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['bank_name'];
    $sub_array[] = date('d-m-Y H:i', strtotime($row['trans_date']));
    $sub_array[] = $row['narration'] ?? '';
    $sub_array[] = $row['trans_id'];
    $sub_array[] = moneyFormatIndia($row['credit'] ?? '');
    $sub_array[] = moneyFormatIndia($row['debit'] ?? '');
    $sub_array[] = moneyFormatIndia($row['balance'] ?? '');
    $sub_array[] = 'Cleared';

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT count(id) as id_count FROM bank_stmt");
    $statement = $query->fetch();
    return $statement['id_count'];
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
