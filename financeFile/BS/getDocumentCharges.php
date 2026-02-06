<?php
include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $where = " and ii.insert_login_id = '" . $_POST['user_id'] . "' " : $where = ''; //for user based

$doc_charge = 0;
$proc_charge = 0;

if ($type == 'today') {
    // >13 means entries moved to collection from issue

    $qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal FROM in_issue ii
                        JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id   
                        where DATE(ii.updated_date) = CURRENT_DATE AND ii.cus_status > 13 $where ");
    $row = $qry->fetch();
    $response['doc_charge'] = $row['doc_charge_cal'];
    $response['proc_charge'] = $row['proc_fee_cal'];
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal FROM in_issue ii
                                JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id  
                                where (DATE(ii.updated_date) >= DATE('$from_date') && DATE(ii.updated_date) <= DATE('$to_date')) AND ii.cus_status > 13 $where ");
    $row = $qry->fetch();
    $response['doc_charge'] = $row['doc_charge_cal'];
    $response['proc_charge'] = $row['proc_fee_cal'];
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal FROM in_issue ii
                                    JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id  
                                    where (MONTH(ii.updated_date) = '$month' && YEAR(ii.updated_date) = '$year') AND ii.cus_status > 13 $where ");
    $row = $qry->fetch();
    $response['doc_charge'] = $row['doc_charge_cal'];
    $response['proc_charge'] = $row['proc_fee_cal'];
}
$response['doc_charge'] = moneyFormatIndia($response['doc_charge']);
$response['proc_charge'] = moneyFormatIndia($response['proc_charge']);

echo json_encode($response);

// Close the database connection
$connect = null;
