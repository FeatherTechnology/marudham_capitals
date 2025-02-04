<?php

session_start();
include '../../ajaxconfig.php';

$where = "";
$li_where = "";
if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = " WHERE (date(coll_date) <= '$to_date')";
    $li_where  = "AND date(li.created_date) <= date('$to_date') AND balance_amount = '0'";
}else{
    $to_date = date('Y-m-d');
}

$userid = $_SESSION["userid"] ?? null;

$sub_area_list = '';
if ($userid && $userid != 1) {
    $userQry = $connect->query("SELECT group_id, line_id FROM USER WHERE user_id = $userid");
    $user = $userQry->fetch();
    if ($user) {
        $line_id = explode(',', $user['line_id']);
        $sub_area_list = [];
        foreach ($line_id as $line) {
            $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping WHERE map_id = $line");
            while ($row = $lineQry->fetch()) {
                $sub_area_list = array_merge($sub_area_list, explode(',', $row['sub_area_id']));
            }
        }
        $sub_area_list = implode(',', array_unique($sub_area_list));
    }
}

$statusObj = [
    '14' => 'Current',
    '15' => 'Error',
    '16' => 'Legal',
    '17' => 'Current',
    '20' => 'Closed',
    '21' => 'NOC'
];

$column = [
    'lc.loan_cal_id',
    'cp.area_line',
    'ii.loan_id',
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
    WHERE req.cus_status BETWEEN 14 AND 18  AND cp.area_confirm_subarea IN ($sub_area_list)

    UNION

    SELECT cc.req_id FROM closing_customer cc JOIN loan_issue li ON cc.req_id = li.req_id WHERE date(cc.closing_date) > date('$to_date') AND date(li.created_date) <= date('$to_date')  ";

$run = $connect->query($qry);
$req_id_list = [];
while ($row = $run->fetch()) {
    $req_id_list[] = $row['req_id'];
}
$req_id_list = implode(',', $req_id_list);

$query = " SELECT 
            cp.area_line AS line,
            ii.loan_id,
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
            c.int_amt_track,
            req.cus_status
        FROM 
            acknowlegement_loan_calculation lc
        JOIN 
            acknowlegement_customer_profile cp ON lc.req_id = cp.req_id
        JOIN 
            in_issue ii ON lc.req_id = ii.req_id
        JOIN 
            loan_issue li ON lc.req_id = li.req_id 
        JOIN 
            area_list_creation al ON cp.area_confirm_area = al.area_id
        JOIN 
            sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
        JOIN 
            request_creation req ON lc.req_id = req.req_id
        LEFT JOIN 
            loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
        LEFT JOIN 
            agent_creation ac ON req.agent_id = ac.ag_id
        LEFT JOIN (
            SELECT 
                req_id, 
                SUM(due_amt_track) AS due_amt_track, 
                SUM(princ_amt_track) AS princ_amt_track, 
                SUM(int_amt_track) AS int_amt_track 
            FROM 
                collection $where
            GROUP BY 
                req_id
        ) c ON c.req_id = req.req_id
        WHERE lc.req_id IN ($req_id_list) ";

if (isset($_POST['search']) && $_POST['search'] != "") {
    $search = $_POST['search'];
    $query .= " AND (
        cp.area_line LIKE '%$search%' OR
        ii.loan_id LIKE '%$search%' OR
        ii.updated_date LIKE '%$search%' OR
        lc.maturity_month LIKE '%$search%' OR
        cp.cus_id LIKE '%$search%' OR
        cp.cus_name LIKE '%$search%' OR
        al.area_name LIKE '%$search%' OR
        sal.sub_area_name LIKE '%$search%'
    )";
}

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
    $balance_amt = ($row['due_type'] != 'Interest') ?
        intVal($row['tot_amt_cal']) - intVal($row['due_amt_track']) :
        intVal($row['principal_amt_cal']) - intVal($row['princ_amt_track']);

    $princ_amt = intVal($row['principal_amt_cal']) / $row['due_period'];
    $int_amt = intVal($row['int_amt_cal']) / $row['due_period'];
    $response = calculatePrincipalAndInterest($princ_amt, $int_amt, $balance_amt);

    if (intVal($response['principal_paid']) > intVal($row['loan_amt_cal'])) {
        $diff = intVal($response['principal_paid']) - intVal($row['loan_amt_cal']);
        $response['interest_paid'] += $diff;
        $response['principal_paid'] = intVal($row['loan_amt_cal']);
    }

    $bal_due = round($balance_amt / $row['due_amt_cal'], 1);

    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
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
    $sub_array[] = moneyFormatIndia($balance_amt);
    $sub_array[] = moneyFormatIndia($response['principal_paid']);
    $sub_array[] = moneyFormatIndia($response['interest_paid']);
    $sub_array[] = $bal_due;
    $sub_array[] = 'Present';
    $sub_array[] = $statusObj[$row['cus_status']];
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

function calculatePrincipalAndInterest($principal, $interest, $paidAmount)
{
    $principal_paid = 0;
    $interest_paid = 0;

    while ($paidAmount > 0) {
        if ($paidAmount >= $principal) {
            $principal_paid += $principal;
            $paidAmount -= $principal;
        } else {
            $principal_paid += $paidAmount;
            break;
        }

        if ($paidAmount >= $interest) {
            $interest_paid += $interest;
            $paidAmount -= $interest;
        } else {
            $interest_paid += $paidAmount;
            break;
        }
    }

    return [
        'principal_paid' => (int)$principal_paid,
        'interest_paid' => (int)$interest_paid,
    ];
}
