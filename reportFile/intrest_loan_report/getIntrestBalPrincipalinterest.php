<?php

session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

$userid = $_SESSION["userid"] ?? null;
$report_access = '2'; //if super Admin login use need to show overall.

$sub_area_list = '';
$user_based = '';

if ($userid && $userid != 1) {
    $userQry = $connect->query("SELECT line_id, report_access FROM USER WHERE user_id = $userid");
    $user = $userQry->fetch();
    $report_access = $user['report_access'];

    if ($report_access == '1') {
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
} else {
    $to_date = date('Y-m-d');
}

$column = [
    'ii.loan_id',
     'ag.group_name',
    'alm.line_name',
     'adm.duefollowup_name',
    'ii.loan_id',
    'ad.doc_id',
    'ii.updated_date',
    'lc.maturity_month',
    'cp.cus_id',
    'cr.autogen_cus_id',
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


$qry = "SELECT req.req_id 
    FROM request_creation req
    JOIN acknowlegement_customer_profile cp ON req.req_id = cp.req_id
    JOIN loan_issue li ON req.req_id = li.req_id $li_where
    WHERE req.cus_status BETWEEN 14 AND 18  $user_based

    UNION

    SELECT cc.req_id 
    FROM closing_customer cc 
    JOIN loan_issue li ON cc.req_id = li.req_id 
    WHERE date(cc.closing_date) > date('$to_date') AND date(li.created_date) <= date('$to_date')  ";

$run = $connect->query($qry);
$req_id_list = [];
while ($row = $run->fetch()) {
    $req_id_list[] = $row['req_id'];
}
$req_id_list = implode(',', $req_id_list);

$query = "SELECT 
            lc.req_id AS req_id,    
            ag.group_name,
            alm.line_name AS line,
            adm.duefollowup_name,
            ii.loan_id,
            ad.doc_id,
            ii.updated_date AS loan_date,
            lc.maturity_month,
            cp.cus_id,
            cr.autogen_cus_id,
            cp.req_id,
            cp.cus_name,
            al.area_name,
            sal.sub_area_name,
            lcc.loan_category_creation_name AS loan_cat_name,
            lc.sub_category,
            ac.ag_name,
            lc.loan_amt_cal,
            lc.int_amt_cal,
            lc.due_type,
            lc.due_period,
            c.int_amt_track,
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
            lc.calc_method,
            lc.int_rate,
            lc.maturity_month AS maturity_date
        FROM 
            acknowlegement_loan_calculation lc
        JOIN 
            acknowlegement_customer_profile cp ON lc.req_id = cp.req_id
        JOIN 
            customer_register cr ON cp.cus_id = cr.cus_id
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
            area_group_mapping ag ON FIND_IN_SET(sal.sub_area_id, ag.sub_area_id)
        JOIN 
            area_line_mapping alm ON FIND_IN_SET(sal.sub_area_id, alm.sub_area_id)
        JOIN 
            area_duefollowup_mapping adm ON FIND_IN_SET(al.area_id, adm.area_id)
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
                SUM(c.int_amt_track) AS int_amt_track, 
                SUM(c.princ_amt_track) AS princ_amt_track, 
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
        WHERE lc.req_id IN ($req_id_list) AND lc.due_type = 'Interest' AND balance_amount = '0' ";

if (isset($_POST['loan_cat'])) {
    $loan_cat_str = "'" . implode("','", $_POST['loan_cat']) . "'";
    $query .= " AND lcc.loan_category_creation_id IN ($loan_cat_str)";
}

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (
         ag.group_name LIKE '%$search%' OR
        alm.line_name LIKE '%$search%' OR
        adm.duefollowup_name LIKE '%$search%' OR
        ii.loan_id LIKE '%$search%' OR
        ad.doc_id LIKE '%$search%' OR
        ii.updated_date LIKE '%$search%' OR
        lc.maturity_month LIKE '%$search%' OR
        cp.cus_id LIKE '%$search%' OR
        cr.autogen_cus_id LIKE '%$search%' OR
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

    $paid_due = $row['int_amt_track'] / $row['int_amt_cal'];
    $payable_amount = ($months * $row['int_amt_cal']) - $row['int_amt_track'];
    $pending_amount = ($pending_month * $row['int_amt_cal']) - $row['int_amt_track'];
    $balance_amount =  intVal($row['loan_amt_cal']) - intVal($row['princ_amt_track']);
    $penalty = intval($row['penalty']) - (intval($row['penalty_track']) + intval($row['penalty_waiver']));
    $fine = intval($row['fine']) - (intval($row['fine_track']) + intval($row['fine_waiver']));

    $req_id = $row['req_id'];

    // Prepare loan_arr and response for calculation
    $loan_arr = [
        'loan_date' => $row['loan_date'],
        'calculate_method' => $row['calc_method'],
        'int_rate' => $row['int_rate']
    ];

    $response = [
        'calculate_method' => $row['calc_method'],
        'due_amt' => floatval($row['int_amt_cal'])
    ];

    // Pass the report date to override today in payableCalculation
    $payable_interest = payableCalculation($connect, $loan_arr, $response, $req_id, $to_date);

    // Interest already paid
    $interest_paid = getPaidInterest($connect, $req_id, $to_date);

    // Pending interest
    $pending_interest = ceilAmount($payable_interest) - $interest_paid;
    if ($pending_interest < 0) $pending_interest = 0;

    $sub_array[] = $sno;
         $sub_array[] = $row['group_name'];
    $sub_array[] = $row['line'];
    $sub_array[] = $row['duefollowup_name'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = $row['doc_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_month']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = moneyFormatIndia($row['loan_amt_cal']);
    $sub_array[] = $row['due_period'];
    $sub_array[] = moneyFormatIndia($balance_amount);
    $sub_array[] = moneyFormatIndia($balance_amount);
    $sub_array[] = moneyFormatIndia($pending_interest);
    $sub_array[] = moneyFormatIndia($penalty);
    $sub_array[] = moneyFormatIndia($fine);
    $sub_array[] = 'Present';
    $payable_amount = max(0, $payable_amount);
    $pending_amount = max(0, $pending_amount);

    if ($row['cus_status'] == '15' && strtotime($row['updated_date']) < strtotime($to_date)) {
        $sub_array[] = 'Error';
    } else if ($row['cus_status'] == '16' && strtotime($row['updated_date']) < strtotime($to_date)) {
        $sub_array[] = 'Legal';
    } else if ($payable_amount == 0  && $pending_amount == 0  && $balance_amount == 0) {
        $sub_array[] = 'Due Nil';
    } else if ($payable_amount <= $row['int_amt_cal'] && $pending_amount == 0  &&  ((($row['calc_method'] === 'Monthly') && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', strtotime($to_date))) || (($row['calc_method'] != 'Monthly') && strtotime($row['maturity_date']) >= strtotime($to_date))) && $balance_amount != 0) {
        $sub_array[] = 'Current';
    } else if ($pending_amount > 0 &&  (
        (($row['calc_method'] === 'Monthly') && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', strtotime($to_date))) || (($row['calc_method'] != 'Monthly') && strtotime($row['maturity_date']) > strtotime($to_date))
    )) {
        $sub_array[] = 'Pending';
    } else if (
        (
            ($balance_amount  > 0) && ((($row['calc_method'] === 'Monthly') && date('Y-m', strtotime($row['maturity_date'])) < date('Y-m', strtotime($to_date))) || (($row['calc_method'] != 'Monthly') && strtotime($row['maturity_date']) < strtotime($to_date)))
        )
    ) {
        $sub_array[] = 'OD';
    } else {
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

function payableCalculation($connect, $loan_arr, $response, $req_id , $to_date = null)
{
    $issued_date = new DateTime(date('Y-m-d', strtotime($loan_arr['loan_date'])));
    $cur_date = new DateTime($to_date ?? date('Y-m-d'));

    $result = 0;
    if ($response['calculate_method'] == "Monthly") {
        $last_month = clone $cur_date;
        $last_month->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_month->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date; // Due to mutation in function

            $result += dueAmtCalculation($connect, $start, $end_date, $response['due_amt'], $loan_arr, 'payable', $req_id);

            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
    } elseif ($response['calculate_method'] == "Days") {
        $last_date = clone $cur_date;
        $last_date->modify('-1 month'); // Last month same date
        $st_date = clone $issued_date;

        while ($st_date->format('Y-m') <= $last_date->format('Y-m')) {
            $end_date = clone $st_date;
            $end_date->modify('last day of this month');
            $start = clone $st_date;

            $result += dueAmtCalculation($connect, $start, $end_date, $response['due_amt'], $loan_arr, 'payable', $req_id);
            $st_date->modify('+1 month');
            $st_date->modify('first day of this month');
        }
    }
    return $result;
}

function dueAmtCalculation($connect, $start_date, $end_date, $due_amt, $loan_arr, $status, $req_id)
{
    $start = new DateTime($start_date->format('Y-m-d'));
    $end = new DateTime($end_date->format('Y-m-d'));

    $calculate_method = $loan_arr['calculate_method'];
    $int_rate = $loan_arr['int_rate'];
    $result = 0;
    $monthly_Interest_data = [];

    $loanRow = $connect->query("SELECT loan_amt FROM acknowlegement_loan_calculation WHERE req_id = '$req_id'")->fetch(PDO::FETCH_ASSOC);
    $default_balance = $loanRow['loan_amt'];

    $collections = $connect->query("SELECT princ_amt_track, principal_waiver, coll_date FROM collection 
        WHERE req_id = '$req_id' AND (princ_amt_track != '' OR principal_waiver != '') ORDER BY coll_date ASC")->fetchAll();

    if (!empty($collections)) {

        // <---------------------------------------------------------------- IF COLLECTIONS EXIST ------------------------------------------------------------>

        $collection_index = 0;
        $current_balance = $default_balance;

        while ($start <= $end) {
            $today_str = $start->format('Y-m-d');
            $month_key = $start->format('Y-m-01');
            $paid_principal_today = 0;
            $paid_principal_waiver = 0;

            while ($collection_index < count($collections)) {
                $collection = $collections[$collection_index];
                $coll_date = (new DateTime($collection['coll_date']))->format('Y-m-d');
                if ($coll_date == $today_str) {
                    $paid_principal_today += (float)$collection['princ_amt_track'];
                    $paid_principal_waiver += (float)$collection['principal_waiver'];
                    $collection_index++;
                } else {
                    break;
                }
            }

            $current_balance = max(0, $current_balance - ($paid_principal_today + $paid_principal_waiver));

            $Interest_today = calculateNewInterestAmt($int_rate, $current_balance, $calculate_method);

            if ($calculate_method === 'Days') {
                $result += $Interest_today;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $Interest_today;
            } else {
                $days_in_month = (int)$start->format('t');
                $daily_Interest = $Interest_today / $days_in_month;
                $result += $daily_Interest;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $daily_Interest;
            }

            $start->modify('+1 day');
        }
    } else {
        $monthly_Interest_data = [];

        if ($calculate_method == 'Monthly') {
            while ($start->format('Y-m') <= $end->format('Y-m')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $due_amt / intval($start->format('t'));

                if ($status != 'pending') {
                    if ($start->format('m') != $end->format('m')) {
                        $new_end_date = clone $start;
                        $new_end_date->modify('last day of this month');
                        $cur_result = (($start->diff($new_end_date))->days + 1) * $dueperday;
                    } else {
                        $cur_result = (($start->diff($end))->days + 1) * $dueperday;
                    }
                } else {
                    $new_end = clone $start;
                    $new_end->modify("last day of this month");
                    $cur_result = (($start->diff($new_end))->days + 1) * $dueperday;
                }

                $result += $cur_result;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $cur_result;
                $start->modify('+1 month');
                $start->modify('first day of this month');
            }
        } else if ($calculate_method == 'Days') {
            while ($start->format('Y-m-d') <= $end->format('Y-m-d')) {
                $month_key = $start->format('Y-m-d');
                $dueperday = $due_amt;
                $result += $dueperday;
                $monthly_Interest_data[$month_key] = ($monthly_Interest_data[$month_key] ?? 0) + $dueperday;

                $start->modify('+1 day');
            }
        }
    }

    return $result;
}

function calculateNewInterestAmt($int_rate, $balance, $calculate_method)
{
    if ($calculate_method == 'Monthly') {
        $int = $balance * ($int_rate / 100);
    } else if ($calculate_method == 'Days') {
        $int = ($balance * ($int_rate / 100) / 30);
    }

    $curInterest = ceil($int / 5) * 5; //to increase Interest to nearest multiple of 5
    if ($curInterest < $int) {
        $curInterest += 5;
    }
    $response = $curInterest;

    return $response;
}

function getPaidInterest($connect, $req_id , $to_date)
{
    $qry = $connect->query("SELECT COALESCE(SUM(int_amt_track), 0) + COALESCE(SUM(interest_waiver), 0) AS int_paid FROM `collection` WHERE req_id = '$req_id' and (int_amt_track != '' and int_amt_track IS NOT NULL OR interest_waiver != '' and interest_waiver IS NOT NULL) AND DATE(coll_date) <= DATE('$to_date') ");
    $int_paid = $qry->fetch()['int_paid'];
    return intVal($int_paid);
}

function ceilAmount($amt)
{
    $cur_amt = ceil($amt / 5) * 5; //ceil will set the number to nearest upper integer//i.e ceil(121/5)*5 = 125
    if ($cur_amt < $amt) {
        $cur_amt += 5;
    }
    return $cur_amt;
}