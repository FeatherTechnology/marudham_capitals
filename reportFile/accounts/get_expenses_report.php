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

    $userQry = $connect->query("SELECT  report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') {
        $user_based = " AND hexp.insert_login_id = '$userid' ";
    }
}

$where = "";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
}

$column = array(
    'id', 'created_date', 'usertype', 'username', 'ref_code', 'category',
    'part', 'vou_id', 'trans_id', 'rec_per', 'remark', 'amt'
);

// Build the unified query with UNION
$query = "
    SELECT 
        hexp.id,
        hexp.created_date,
        hexp.usertype,
        hexp.username,
        '' AS ref_code,
        excat.category,
        hexp.part,
        hexp.vou_id,
        '' AS trans_id,
        hexp.rec_per,
        hexp.remark,
        hexp.amt
    FROM ct_db_hexpense hexp
    JOIN expense_category excat ON hexp.cat = excat.id
    WHERE DATE(hexp.created_date) BETWEEN '$from_date' AND '$to_date'" .
    ($user_based != "" ? " AND hexp.insert_login_id = '$userid'" : "") . "

    UNION ALL

    SELECT 
        bexp.id,
        bexp.created_date,
        bexp.usertype,
        bexp.username,
        bexp.ref_code,
        excat.category,
        bexp.part,
        bexp.vou_id,
        bexp.trans_id,
        bexp.rec_per,
        bexp.remark,
        bexp.amt
    FROM ct_db_bexpense bexp
    JOIN expense_category excat ON bexp.cat = excat.id
    WHERE DATE(bexp.created_date) BETWEEN '$from_date' AND '$to_date'" .
    ($user_based != "" ? " AND bexp.insert_login_id = '$userid'" : "");


// Handle search
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $query .= " HAVING 
        usertype LIKE '%$search%' OR
        username LIKE '%$search%' OR
        ref_code LIKE '%$search%' OR
        category LIKE '%$search%' OR
        part LIKE '%$search%' OR
        vou_id LIKE '%$search%' OR
        trans_id LIKE '%$search%' OR
        rec_per LIKE '%$search%' OR
        remark LIKE '%$search%' OR
        amt LIKE '%$search%'";
}

// Handle ordering
if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
}

// Pagination
$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

// Run query
$statement = $connect->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $sno++;
    $sub_array[] = date('d-m-Y', strtotime($row['created_date']));
    $sub_array[] = $row['usertype'];
    $sub_array[] = $row['username'];
    $sub_array[] = $row['ref_code'];    // Blank for `hexp`, filled for `bexp`
    $sub_array[] = $row['category'];
    $sub_array[] = $row['part'];
    $sub_array[] = $row['vou_id'];
    $sub_array[] = $row['trans_id'];    // Blank for `hexp`, filled for `bexp`
    $sub_array[] = $row['rec_per'];
    $sub_array[] = $row['remark'];
    $sub_array[] = $row['amt'];
    $data[] = $sub_array;
}

// Total records (for DataTable)
function count_all_data($connect)
{
    $hexp = $connect->query("SELECT COUNT(*) as count FROM ct_db_hexpense")->fetch();
    $bexp = $connect->query("SELECT COUNT(*) as count FROM ct_db_bexpense")->fetch();
    return $hexp['count'] + $bexp['count'];
}

$output = array(
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => count_all_data($connect),
    "recordsFiltered" => $number_filter_row,
    "data" => $data
);

echo json_encode($output);
