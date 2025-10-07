<?php

include '../../ajaxconfig.php';
session_start();
$user_id = $_SESSION['userid'];

$to_date = date('Y-m-d', strtotime($_POST['to_date']));

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
    DATE(bs.trans_date) <= '$to_date'
    AND (
        bs.clr_status = '0' OR (bs.clr_status = '1' AND bs.updated_date > '$to_date 23:59:59')
    )";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (bs.bank_name LIKE '%" . $_POST['search'] . "%' OR
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
    $sub_array[] = date('d-m-Y', strtotime($row['trans_date']));
    $sub_array[] = $row['narration'] ?? '';
    $sub_array[] = $row['trans_id'];
    $sub_array[] = moneyFormatIndia($row['credit'] ?? '');
    $sub_array[] = moneyFormatIndia($row['debit'] ?? '');
    $sub_array[] = moneyFormatIndia($row['balance'] ?? '');
    $sub_array[] = 'Unclear';

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
function moneyFormatIndia($num)
{
    // Handle empty or non-numeric input
    if ($num === null || $num === '' || !is_numeric($num)) {
        return '0';
    }

    $num = round($num, 2); // optional, limit to 2 decimals if needed
    $numParts = explode('.', $num);
    $integerPart = $numParts[0];
    $decimalPart = isset($numParts[1]) ? '.' . $numParts[1] : '';

    // Handle numbers less than 1000 directly
    if (strlen($integerPart) <= 3) {
        return $integerPart . $decimalPart;
    }

    // Split last 3 digits
    $lastThree = substr($integerPart, -3);
    $restUnits = substr($integerPart, 0, -3);

    // Add leading zero if odd length
    $restUnits = (strlen($restUnits) % 2 == 1) ? '0' . $restUnits : $restUnits;
    $expUnits = str_split($restUnits, 2);

    $formatted = '';
    foreach ($expUnits as $i => $unit) {
        // remove leading zero for first group
        $formatted .= ($i == 0 ? (int)$unit : $unit) . ',';
    }

    return $formatted . $lastThree . $decimalPart;
}


// Close the database connection
$connect = null;
