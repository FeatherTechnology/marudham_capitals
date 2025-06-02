<?php

session_start();
include '../../ajaxconfig.php';

$userid = $_SESSION["userid"] ?? null;
$report_access = '2'; //if super Admin login use need to show overall.

$sub_area_list = '';
$user_based = '';

if ($userid && $userid != 1) {
    $userQry = $connect->query("SELECT line_id, report_access FROM USER WHERE user_id = $userid");
    $user = $userQry->fetch();
    $report_access = $user['report_access'];

    if ($report_access =='1') {
        $line_id = explode(',', $user['line_id']);
        $sub_area_list = [];
        foreach ($line_id as $line) {
            $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping WHERE map_id = $line");
            while ($row = $lineQry->fetch()) {
                $sub_area_list = array_merge($sub_area_list, explode(',', $row['sub_area_id']));
            }
        }
        $sub_area_list = implode(',', array_unique($sub_area_list));

        $user_based = " AND cp.area_confirm_subarea IN ($sub_area_list) AND req.insert_login_id = '$userid' ";
        
    }
}

$where = "";
$li_where = "";
if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = " WHERE (date(coll_date) <= '$to_date')";
    $li_where  = "AND date(li.created_date) <= date('$to_date') AND balance_amount = '0' ";
}else{
    $to_date = date('Y-m-d');
}

$column = [
    'lc.loan_cal_id',
    'alm.line_name',
    'ii.loan_id',
    'ad.doc_id',
    'ii.updated_date',
    'lc.maturity_month',
    'cp.cus_id',
    'cp.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'lc.loan_cal_id',
    'lc.sub_category',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
    'lc.loan_cal_id',
];


$qry = "SELECT req.req_id FROM request_creation req
    JOIN acknowlegement_customer_profile cp ON req.req_id = cp.req_id
    JOIN loan_issue li ON req.req_id = li.req_id $li_where
    WHERE req.cus_status BETWEEN 14 AND 18  $user_based

    UNION

    SELECT cc.req_id FROM closing_customer cc JOIN loan_issue li ON cc.req_id = li.req_id WHERE date(cc.closing_date) > date('$to_date') AND date(li.created_date) <= date('$to_date')  ";

$run = $connect->query($qry);
$req_id_list = [];
while ($row = $run->fetch()) {
    $req_id_list[] = $row['req_id'];
}
$req_id_list = implode(',', $req_id_list);

$query = "SELECT 
            alm.line_name AS line,
            ii.loan_id,
            ad.doc_id,
            ii.updated_date AS loan_date,
            lc.maturity_month,
            cp.cus_id,
            cp.req_id,
            cp.cus_name,
            al.area_name,
            sal.sub_area_name,
            lcc.loan_category_creation_name AS loan_cat_name,
            lc.sub_category,
            ac.ag_name,
            lc.loan_amt_cal,
            lc.due_amt_cal,
            lc.principal_amt_cal,
            lc.int_amt_cal,
            lc.tot_amt_cal,
            lc.due_type,
            lc.due_period,
            c.due_amt_track,
            c.princ_amt_track,
            c.penalty, 
            c.fine, 
            c.penalty_track, 
            c.fine_track,
            c.penalty_waiver,
            c.fine_waiver,
            ack.updated_date,
            iv.cus_status,
            lc.due_start_from,
            lc.due_method_scheme,
            lc.due_method_calc,
            lc.maturity_month AS maturity_date
        FROM 
            acknowlegement_loan_calculation lc
        JOIN 
            acknowlegement_customer_profile cp ON lc.req_id = cp.req_id
        JOIN 
            acknowlegement_documentation ad ON lc.req_id = ad.req_id
        JOIN 
            in_issue ii ON lc.req_id = ii.req_id
        JOIN 
            loan_issue li ON lc.req_id = li.req_id 
        JOIN 
            area_list_creation al ON cp.area_confirm_area = al.area_id
        JOIN 
            sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
        JOIN 
            area_line_mapping alm ON FIND_IN_SET(sal.sub_area_id, alm.sub_area_id)
        JOIN 
            in_verification iv ON lc.req_id = iv.req_id
        JOIN 
            in_acknowledgement ack ON ack.req_id = iv.req_id
        LEFT JOIN 
            loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
        LEFT JOIN 
            agent_creation ac ON iv.agent_id = ac.ag_id
        LEFT JOIN (
    SELECT 
        c.req_id, 
        SUM(c.due_amt_track) AS due_amt_track, 
        SUM(c.princ_amt_track) AS princ_amt_track, 
        SUM(c.int_amt_track) AS int_amt_track,
        SUM(c.penalty_track) AS penalty_track, 
        SUM(c.coll_charge_track) AS fine_track,
        SUM(c.penalty_waiver) AS penalty_waiver,
        SUM(c.coll_charge_waiver) AS fine_waiver,
        COALESCE(p.total_penalty, 0) AS penalty,
        COALESCE(ch.total_fine, 0) AS fine

    FROM  collection c
    LEFT JOIN (
        SELECT req_id, SUM(penalty) AS total_penalty
        FROM   penalty_charges 
        WHERE DATE(created_date) <= '$to_date' GROUP BY req_id) p ON p.req_id = c.req_id
    LEFT JOIN (
        SELECT req_id, SUM(coll_charge) AS total_fine
        FROM collection_charges 
        WHERE DATE(created_date) <= '$to_date'
        GROUP BY req_id ) ch ON ch.req_id = c.req_id
    $where
    GROUP BY c.req_id ) c ON c.req_id = iv.req_id
        WHERE lc.req_id IN ($req_id_list) ";

if(isset($_POST['loan_cat'])){
    $loan_cat_str = "'" . implode("','", $_POST['loan_cat']) . "'";
    $query .= " AND lcc.loan_category_creation_id IN ($loan_cat_str)";
}

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (
        alm.line_name LIKE '%$search%' OR
        ii.loan_id LIKE '%$search%' OR
        ad.doc_id LIKE '%$search%' OR
        ii.updated_date LIKE '%$search%' OR
        lc.maturity_month LIKE '%$search%' OR
        cp.cus_id LIKE '%$search%' OR
        cp.cus_name LIKE '%$search%' OR
        al.area_name LIKE '%$search%' OR
        sal.sub_area_name LIKE '%$search%'
    )";
}

    $query .= " GROUP BY lc.req_id";
    
$orderColumn = $_POST['order'][0]['column'] ?? null;
$orderDir = $_POST['order'][0]['dir'] ?? 'ASC';
if ($orderColumn !== null) {
    $query .= " ORDER BY " . $column[$orderColumn] . " " . $orderDir;
}

$statement = $connect->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();

$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? -1;
if ($length != -1) {
    $query .= " LIMIT $start, $length";
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);


$data = [];
$sno = 1;

foreach ($result as $row) {
    $sub_array = [];

    if (strtotime($row['maturity_date']) < strtotime($to_date)) {
        $end = strtotime($row['maturity_date']);
        $start = strtotime($row['due_start_from']);
        $months = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)) + 1;

        $pending_month = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start));
        
    } else {
        $start = strtotime($row['due_start_from']);
        $end = strtotime($to_date);
        $months = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)) + 1;
        $pending_month = max(0, (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)));

    }

    $paid_due = $row['due_amt_track'] / $row['due_amt_cal'];
    $balance_due = (float)$row['due_period'] - $paid_due;
    $payable_amount = ($months * $row['due_amt_cal'] ) - $row['due_amt_track'];
    $pending_amount = ($pending_month * $row['due_amt_cal'] ) - $row['due_amt_track'];    
    $balance_amount = ($row['due_type'] != 'Interest') ?
        intVal($row['tot_amt_cal']) - intVal($row['due_amt_track']) :
        intVal($row['principal_amt_cal']) - intVal($row['princ_amt_track']);

        $due_period = intVal($row['due_period']);

        if ($due_period > 0) {
            $princ_amt = intVal($row['principal_amt_cal']) / $due_period;
            $int_amt = intVal($row['int_amt_cal']) / $due_period;
        } else {
            $princ_amt = 0;  // Or any default value
            $int_amt = 0;    // Or any default value
        }
       
    $balance_principal= $princ_amt * $balance_due;
    $balance_intrest= $int_amt * $balance_due;

    $penalty = intval($row['penalty']) - (intval($row['penalty_track']) + intval($row['penalty_waiver']));

    $fine = intval($row['fine']) - (intval($row['fine_track']) + intval($row['fine_waiver']));

    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = $row['doc_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_month']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = moneyFormatIndia($row['loan_amt_cal']);
    $sub_array[] = moneyFormatIndia($row['due_amt_cal']);
    $sub_array[] = $row['due_period'];
    $sub_array[] = moneyFormatIndia($row['tot_amt_cal']);
    $sub_array[] = moneyFormatIndia($balance_amount);
    $sub_array[] = round($balance_principal, 1);;
    $sub_array[] = round($balance_intrest, 1);;
    $sub_array[] = floor($balance_due *100) / 100;
    $sub_array[] = moneyFormatIndia($penalty);
    $sub_array[] = moneyFormatIndia($fine);
    $sub_array[] = 'Present';
    $payable_amount = max(0, $payable_amount);
    $pending_amount = max(0, $pending_amount);
    if($row['cus_status'] =='15' && strtotime($row['updated_date']) < strtotime($to_date)){
        $sub_array[] = 'Error';
    }
    else if($row['cus_status'] =='16' && strtotime($row['updated_date'])< strtotime($to_date)){
        $sub_array[] = 'Legal';
    }
    else if($payable_amount == 0  && $pending_amount == 0  && $balance_amount == 0){
        $sub_array[] = 'Due Nil';
    }
    else if($payable_amount <= $row['due_amt_cal'] && $pending_amount == 0  &&  ((($row['due_method_scheme'] === '1' || $row['due_method_calc'] ==='Monthly') && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', strtotime($to_date))) ||(($row['due_method_scheme'] != '1'|| $row['due_method_calc'] !='Monthly') && strtotime($row['maturity_date']) >= strtotime($to_date))) && $balance_amount != 0 ){
        $sub_array[] = 'Current';
    }
    else if($pending_amount > 0 &&  (
            (($row['due_method_scheme'] === '1' || $row['due_method_calc'] ==='Monthly') && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', strtotime($to_date))) || (($row['due_method_scheme'] != '1'|| $row['due_method_calc'] !='Monthly') && strtotime($row['maturity_date']) > strtotime($to_date))
        )){
        $sub_array[] = 'Pending';
    }
    else if (
    (
        ($balance_amount  > 0) &&((($row['due_method_scheme'] === '1' || $row['due_method_calc'] ==='Monthly') && date('Y-m', strtotime($row['maturity_date'])) < date('Y-m', strtotime($to_date))) ||(($row['due_method_scheme'] != '1'|| $row['due_method_calc'] !='Monthly') && strtotime($row['maturity_date']) < strtotime($to_date)))
    )) 
    {
    $sub_array[] = 'OD';
    }
    else {
        $sub_array[] = 'No Result';
    }
    $data[] = $sub_array;
    $sno++;
}

function count_all_data($connect)
{
    $query = "SELECT req_id FROM request_creation WHERE cus_status BETWEEN 14 AND 18";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = [
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data,
];

echo json_encode($output);

function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3);
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

