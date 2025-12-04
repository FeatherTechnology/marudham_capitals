<?php
session_start();
include "../../ajaxconfig.php";

$response = [];

// Datatable parameters
$searchValue = $_POST['search']['value'] ?? '';
$orderColumnIndex = $_POST['order'][0]['column'] ?? 1; // default Area
$orderDir = $_POST['order'][0]['dir'] ?? 'asc';
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;

// Orderable columns
$orderableColumns = [
    "",                 // S.No (not used)
    "area_name",
    "taluk",
    "line_names",
    "group_names",
    "customer_count",
    "loan_count",
    "current",
    "pending",
    "od",
    "error",
    "legal"
];


// If Loan Category column clicked (Current/Pending/OD/Error/Legal), sort by Area
if (in_array($orderColumnIndex, [7,8,9,10,11])) {
    $orderColumn = "area_name";
} else {
    $orderColumn = $orderableColumns[$orderColumnIndex] ?? "area_name";
}

// Get POST values
$taluk = $_POST['taluk'] ?? '';
$loan_cat = $_POST['loan_cat'] ?? 0;

// User ID
$userid = $_SESSION["userid"] ?? 0;
$report_access = ($userid != 1) ? $connect->query("SELECT report_access FROM USER WHERE user_id = $userid")->fetch()['report_access'] : 0;

if ($taluk != '') {
    $stmt = $connect->prepare("CALL get_area_loan_count_report(:taluk, :loan_cat, :user_id, :report_access)");
    $stmt->bindValue(':taluk', $taluk);
    $stmt->bindValue(':loan_cat', $loan_cat, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userid, PDO::PARAM_INT);
    $stmt->bindValue(':report_access', $report_access, PDO::PARAM_INT);
    $stmt->execute();
    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    while ($stmt->nextRowset()) {}
}

// Search filter
if ($searchValue != '') {
    $response = array_filter($response, function ($row) use ($searchValue) {
        return stripos($row['area_name'], $searchValue) !== false
            || stripos($row['taluk'], $searchValue) !== false;
    });
}

// Sorting
usort($response, function ($a, $b) use ($orderColumn, $orderDir) {
    return $orderDir === "asc" ? $a[$orderColumn] <=> $b[$orderColumn] : $b[$orderColumn] <=> $a[$orderColumn];
});

// Pagination
$totalRecords = count($response);
$response = array_slice($response, $start, $length);

// Output
$output = [
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => array_values($response)
];

echo json_encode($output);
exit;
?>
