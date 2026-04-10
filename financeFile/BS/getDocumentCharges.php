<?php
include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');

$type = $_POST['type'];
$where = ($_POST['user_id'] != '') ? " AND ii.insert_login_id = '" . $_POST['user_id'] . "' " : ''; //for user based

if ($type == 'today') {
    $where .= " AND DATE(ii.updated_date) = CURRENT_DATE ";
    
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where .= " AND (DATE(ii.updated_date) >= DATE('$from_date') AND DATE(ii.updated_date) <= DATE('$to_date')) ";

} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where .= " AND (MONTH(ii.updated_date) = '$month' AND YEAR(ii.updated_date) = '$year') ";

}

// >13 means entries moved to collection from issue
$qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal FROM in_issue ii
                    JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id  
                    WHERE ii.cus_status > 13 $where ");
$row = $qry->fetch();
$response['doc_charge'] = $row['doc_charge_cal'] ?? 0;
$response['proc_charge'] = $row['proc_fee_cal'] ?? 0;

$response['doc_charge'] = moneyFormatIndia($response['doc_charge']);
$response['proc_charge'] = moneyFormatIndia($response['proc_charge']);

echo json_encode($response);

// Close the database connection
$connect = null;
