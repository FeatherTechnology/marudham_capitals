<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //Report Access Overall
}

$user_based = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT line_id, report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $line_id = $rowuser['line_id'];
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') { //Report access individual.
        $line_id = explode(',', $line_id);
        $sub_area_list = array();
        foreach ($line_id as $line) {
            $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping WHERE map_id = $line ");
            $row_sub = $lineQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);

        $user_based = " AND cp.area_confirm_subarea IN ($sub_area_list) AND coll.insert_login_id = '$userid' ";
    }
}

$where = "1";

if (isset($_POST['to_date']) && $_POST['to_date'] != '') {
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "(date(coll.coll_date) <= '" . $to_date . "') ";
}

    $where  .= $user_based;

$statusObj = [
        '14' => 'Current',
        '15' => 'Error',
        '16' => 'Legal',
        '17' => 'Current',
        '20' => 'In Closed',
        '21' => 'Closed',
    ];

    $column = array(
        'lc.id',
        'alm.line_name',
        'ii.loan_id',
        'lc.due_start_from',
        'lc.maturity_date',
        'coll.cus_id',
        'coll.cus_name',
        'al.area_name',
        'sal.sub_area_name',
        'lcc.loan_category_creation_name',
        'lc.sub_category',
        'ac.ag_name',
        'lc.loan_amt',
        'lc.due_amt_cal',
        'lc.due_period',
        'lc.tot_amt_cal',
        'lc.id',
        'lc.id',
        'lc.id',
        'lc.id',
        'lc.id',
        'lc.id',
        'lc.id',
        'lc.id'
    );

    $query = "SELECT 
    ii.updated_date AS loan_date,
    lc.maturity_month AS maturity_date,
    coll.cus_id,
    coll.cus_name,
    lc.id,
    lc.loan_amt,
    lc.due_amt_cal,
    lc.due_period,
    lc.tot_amt_cal,
    lc.sub_category,
    alm.line_name AS line,
    ii.loan_id,
    al.area_name,
    sal.sub_area_name,
    lcc.loan_category_creation_name AS loan_cat_name,
    ac.ag_name,
    req.cus_status,
    cls.closed_sts,
    cls.consider_level,
    (SELECT SUM(c.paid_amt) FROM collection c WHERE c.req_id = coll.req_id and date(coll.coll_date)<=$to_date ) AS total_due_amt,
    coll.pending_amt AS pending,
    coll.payable_amt,  
    coll.total_paid_track,  
    coll.bal_amt,  
    coll.penalty,  
    coll.coll_charge,  
    coll.due_amt_track,  
      CASE 
    WHEN lc.maturity_month < '$to_date' 
         AND (coll.bal_amt + coll.penalty + coll.coll_charge - coll.total_paid_track) > 0  
    THEN TIMESTAMPDIFF(MONTH, lc.maturity_month, '$to_date')  
    ELSE 0
END AS od_months
FROM collection coll
JOIN acknowlegement_customer_profile cp ON coll.req_id = cp.req_id
JOIN in_issue ii ON coll.req_id = ii.req_id
JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
JOIN area_line_mapping alm ON FIND_IN_SET(sal.sub_area_id, alm.sub_area_id)
JOIN acknowlegement_loan_calculation lc ON coll.req_id = lc.req_id
JOIN request_creation req ON coll.req_id = req.req_id
JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
LEFT JOIN agent_creation ac ON req.agent_id = ac.ag_id
LEFT JOIN closed_status cls ON req.req_id = cls.req_id
WHERE $where
AND coll.coll_date = (
    SELECT c.coll_date 
    FROM collection c 
    WHERE c.req_id = coll.req_id
    ORDER BY YEAR(c.coll_date) DESC, MONTH(c.coll_date) DESC, DAY(c.coll_date) DESC
    LIMIT 1
) and req.cus_status >= 14";


        if (isset($_POST['search'])) {
            if ($_POST['search'] != "") {
                $query .= " and (ii.loan_id LIKE '%" . $_POST['search'] . "%'
                            OR ii.updated_date LIKE '%" . $_POST['search'] . "%'
                            OR coll.cus_id LIKE '%" . $_POST['search'] . "%'
                            OR coll.cus_name LIKE '%" . $_POST['search'] . "%'
                            OR al.area_name LIKE '%" . $_POST['search'] . "%'
                            OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%'
                            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%')";
                            
            }
        }
        
$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    
    $balance_amount = ($row['bal_amt'] - $row['due_amt_track']);
    $paid_due = $row['total_due_amt'] / $row['due_amt_cal'];
    $balance_due =  $row['due_period'] - $paid_due;
    $pending_amount = max( ($row['pending'] - $row['due_amt_track']),0);
    $pending_due =  $pending_amount  / $row['due_amt_cal'];
    $payable_amount =  $row['payable_amt'] - $row['total_paid_track'];
    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = moneyFormatIndia($row['due_amt_cal']);
    $sub_array[] = $row['due_period'];
    $sub_array[] = moneyFormatIndia($row['tot_amt_cal']);
    $sub_array[] = isset($balance_amount) && $balance_amount >= 0 ? moneyFormatIndia($balance_amount) : 0;
    $sub_array[] = isset($balance_due) && $balance_due >= 0 ? $balance_due : 0; ;
    $sub_array[] = moneyFormatIndia($pending_amount) ;
    $sub_array[] = isset($pending_due) && $pending_due >= 0 ? number_format($pending_due , 2, '.', ''): 0;
    $sub_array[] = isset($row['od_months']) && $row['od_months'] >= 0 ? $row['od_months'] : 0;;
    $sub_array[] = isset($payable_amount) && $payable_amount >= 0 ? moneyFormatIndia($payable_amount) : 0;

    if ($row['cus_status'] >= '20') {
        $sub_array[] = 'Closed';
        if ($row['closed_sts'] != '' && $row['closed_sts'] != NULL) {
            $rclosed = $row['closed_sts'];
            $consider_lvl = $row['consider_level'];
            if ($rclosed == '1') {
                $sub_array[] = 'Consider - ' . $consider_lvl_arr[$consider_lvl];
            } else
                    if ($rclosed == '2') {
                $sub_array[] = 'Waiting List';
            } else
                    if ($rclosed == '3') {
                $sub_array[] = 'Block List';
            }
        } else {
            $sub_array[] = $statusObj[$row['cus_status']];
        }
    } else {
        $sub_array[] = 'Present';
        $sub_array[] = $statusObj[$row['cus_status']];
    }

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT COUNT(subquery.coll_id) AS count_result FROM ( SELECT coll.coll_id FROM collection coll JOIN request_creation req ON coll.req_id = req.req_id WHERE req.cus_status = 14 GROUP BY coll.req_id ) AS subquery ");
    $statement = $query->fetch();
    return intVal($statement['count_result']);
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

    $thecash = $isNegative ? "-" . $thecash : $thecash;
    $thecash = $thecash == 0 ? "0" : $thecash;
    return $thecash;
}



// Close the database connection
$connect = null;
