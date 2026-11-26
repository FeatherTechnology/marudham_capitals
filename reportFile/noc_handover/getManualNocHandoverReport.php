<?php
include '../../ajaxconfig.php';

$column = array(
    'id',
    'aadhaar_no',
    'cus_id',
    'doc_id',
    'cus_name',
    'mobile_no',
    'area',
    'loan_category',
    'agent_name',
    'cheque_count',
    'noc_handover_date',
    'noc_status',
    'id'
);

$query = "SELECT *
        FROM manual_noc_handover_data
        WHERE status = 0";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $query .= " and (aadhaar_no LIKE '$search%' 
            OR cus_id LIKE '$search%'
            OR doc_id LIKE '$search%'
            OR cus_name LIKE '$search%' 
            OR mobile_no LIKE '$search%'
            OR area LIKE '$search%' 
            OR loan_category LIKE '$search%' 
            OR agent_name LIKE '$search%' 
            OR cheque_count LIKE '$search%' 
            OR noc_handover_date LIKE '$search%') ";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} 

$query1 = '';
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = [];
$sno = 1;

foreach ($result as $row) {
    $data[] = [
        $sno++,
        $row['aadhaar_no'],
        $row['cus_id'],
        $row['doc_id'],
        $row['cus_name'],
        $row['mobile_no'],
        $row['area'],
        $row['loan_category'],
        $row['agent_name'],
        $row['cheque_count'],
        in_array((int)$row['noc_status'], [21, 22]) ? 'NOC Pending' : 'NOC Completed',
        "<button type='button' class='btn btn-success manual-noc-handover' style='background-color:#009688;' data-id='" . $row['id'] . "'>NOC Handover</button>"
    ];
}

$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0, // ✅ safe for both table & download,
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);