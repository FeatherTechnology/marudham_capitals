<?php
include('../../ajaxconfig.php');

$type = $_POST['type'];
$user_id = ($_POST['user_id'] != '') ? $where = " and ia.insert_login_id = '" . $_POST['user_id'] . "' " : $where = ''; //for user based

$doc_charge = 0;
$proc_charge = 0;

if ($type == 'today') {
    // >13 means entries moved to collection from issue

    $qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal from in_acknowledgement ia
                        JOIN acknowlegement_loan_calculation alc ON ia.req_id = alc.req_id   
                        where DATE(ia.updated_date) = CURRENT_DATE and ia.cus_status > 13 $where ");
    $row = $qry->fetch();
    $response['doc_charge'] = $row['doc_charge_cal'];
    $response['proc_charge'] = $row['proc_fee_cal'];
} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal from in_acknowledgement ia
                                JOIN acknowlegement_loan_calculation alc ON ia.req_id = alc.req_id  
                                where (DATE(ia.updated_date) >= DATE('$from_date') && DATE(ia.updated_date) <= DATE('$to_date')) and ia.cus_status > 13 $where ");
    $row = $qry->fetch();
    $response['doc_charge'] = $row['doc_charge_cal'];
    $response['proc_charge'] = $row['proc_fee_cal'];
} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $qry = $connect->query("SELECT COALESCE(SUM(alc.doc_charge_cal), 0) AS doc_charge_cal, COALESCE(SUM(proc_fee_cal),0) AS proc_fee_cal from in_acknowledgement ia
                                    JOIN acknowlegement_loan_calculation alc ON ia.req_id = alc.req_id  
                                    where (MONTH(ia.updated_date) = '$month' && YEAR(ia.updated_date) = '$year') and ia.cus_status > 13 $where ");
    $row = $qry->fetch();
    $response['doc_charge'] = $row['doc_charge_cal'];
    $response['proc_charge'] = $row['proc_fee_cal'];
}
$response['doc_charge'] = moneyFormatIndia($response['doc_charge']);
$response['proc_charge'] = moneyFormatIndia($response['proc_charge']);

echo json_encode($response);


//Format number in Indian Format
function moneyFormatIndia($num)
{
    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $explrestunits = "";
    if (strlen((string)$num) > 3) {
        $lastthree = substr((string)$num, -3);
        $restunits = substr((string)$num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $index => $value) {
            if ($index == 0) {
                $explrestunits .= (int)$value . ",";
            } else {
                $explrestunits .= $value . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    return $isNegative ? "-" . $thecash : $thecash;
}

// Close the database connection
$connect = null;
