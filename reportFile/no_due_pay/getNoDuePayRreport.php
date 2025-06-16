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

if (isset($_POST['from_date']) && $_POST['from_date'] != '') {
    $full_date = $_POST['from_date'] . '-01';
    $from_month = date('m', strtotime($full_date)); 
    $from_year = date('Y', strtotime($full_date)); 
    $where = " NOT EXISTS(
        SELECT 1 
        FROM collection coll 
        WHERE coll.req_id = ii.req_id 
        AND YEAR(coll.coll_date) = '" . $from_year . "' 
        AND MONTH(coll.coll_date) = '" . $from_month . "'
    ) 
    AND (
        (lc.maturity_month >= '$full_date' AND cs.bal_amnt > 0)
        OR ii.cus_status = 14
    ) AND lc.due_start_from <= '$full_date'  AND (lc.tot_amt_cal - IFNULL(col_sum.total_due_amt_tract, 0)) > 0";
}

    $where  .= $user_based;

$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];

$column = array(
    'ii.req_id',
    'alm.line_name',
    'ii.loan_id',
    'ii.updated_date',
    'lc.maturity_month',
    'ii.cus_id',
    'req.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id',
    'ii.req_id'
    
);

$query = "SELECT
    ii.req_id,
    alm.line_name AS line,
    ii.loan_id,
    ii.updated_date AS loan_date,
    ii.cus_id,
    al.area_name,
    sal.sub_area_name,
    lcc.loan_category_creation_name AS loan_cat_name,
    lc.sub_category,
    lc.due_amt_cal,
    lc.tot_amt_cal,
    lc.due_period,
    lc.due_start_from,
    lc.due_method_scheme,
    lc.due_method_calc,
    lc.maturity_month as maturity_date,
    lc.due_start_from,
    ac.ag_name,
    u.role,
    u.fullname,
    cls.closed_sts,
    cls.consider_level,
    req.cus_name,
    req.cus_status,
     ack.updated_date,
    IFNULL(col_sum.total_due_amt_tract, 0) AS total_due_amt
FROM
    in_issue ii
JOIN acknowlegement_customer_profile cp ON
    ii.req_id = cp.req_id
JOIN area_list_creation al ON
    cp.area_confirm_area = al.area_id
JOIN sub_area_list_creation sal ON
    cp.area_confirm_subarea = sal.sub_area_id
JOIN area_line_mapping alm ON
    FIND_IN_SET( sal.sub_area_id, alm.sub_area_id )
JOIN acknowlegement_loan_calculation lc ON
    ii.req_id = lc.req_id
JOIN loan_category_creation lcc ON
    lc.loan_category = lcc.loan_category_creation_id
JOIN request_creation req ON
    ii.req_id = req.req_id
LEFT JOIN agent_creation ac ON
    req.agent_id = ac.ag_id
LEFT JOIN closed_status cls ON
    req.req_id = cls.req_id
LEFT JOIN customer_status cs ON
    cls.req_id = cs.req_id
JOIN in_acknowledgement ack ON ack.req_id = req.req_id
JOIN USER u ON
    ii.insert_login_id = u.user_id
    LEFT JOIN (
    SELECT 
        req_id, 
        SUM(due_amt_track) AS total_due_amt_tract
    FROM 
        collection
    WHERE 
        STR_TO_DATE(coll_date, '%Y-%m-%d') < '$full_date'
    GROUP BY 
        req_id
) col_sum ON col_sum.req_id = ii.req_id
WHERE
    ii.cus_status >= 14
AND $where
";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (ii.loan_id LIKE '%" . $_POST['search'] . "%'
                    OR ii.updated_date LIKE '%" . $_POST['search'] . "%'
                    OR lc.maturity_month LIKE '%" . $_POST['search'] . "%'
                    OR ii.cus_id LIKE '%" . $_POST['search'] . "%'
                    OR req.cus_name LIKE '%" . $_POST['search'] . "%'
                    OR al.area_name LIKE '%" . $_POST['search'] . "%'
                    OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%'
                    OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
                    OR lc.sub_category LIKE '%" . $_POST['search'] . "%'
                    OR ac.ag_name LIKE '%" . $_POST['search'] . "%'
                    OR u.role LIKE '%" . $_POST['search'] . "%'
                    OR alm.line_name LIKE '%" . $_POST['search'] . "%'
                    OR u.fullname LIKE '%" . $_POST['search'] . "%') ";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
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
    if (strtotime($row['maturity_date']) < strtotime($to_date)) {
        $end = strtotime($row['maturity_date']);
        $start = strtotime($row['due_start_from']);
        $search_date = strtotime($to_date);
        $months = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)) + 1;
        $pending = $months;
         if (($row['due_method_calc'] == 'Monthly' || $row['due_method_scheme'] == '1')  ) {
            if(date('m', $search_date) == date('m', $end) && date('Y', $search_date) == date('Y', $end) ){
            $pending -= 1;
            }
        }
        $pending_month = $pending;
        
    } else {
        $end = strtotime($to_date);
        $start = strtotime($row['due_start_from']);
        $months = (date('Y', $end) - date('Y', $start)) * 12 + (date('m', $end) - date('m', $start)) + 1;

        if(date('m', $end)==date('m', $start)){
            $months -- ;
        }
         
        if (($row['due_method_calc'] != 'Monthly' && $row['due_method_scheme'] != '1')  ) {
            if((int)$start_date->format('d') > (int)$end_date->format('d')){
            $months += 1;
            }
        }
        $pending_month = $months - 1;

    }
    
    $balance_amount = $row['tot_amt_cal'] - $row['total_due_amt'];
    $paid_due = $row['total_due_amt'] / $row['due_amt_cal'];
    $balance_due = (float)$row['due_period'] - $paid_due;
    $payable_amount = ($months * $row['due_amt_cal'] ) - $row['total_due_amt'];
    $pending_amount = ($pending_month * $row['due_amt_cal'] ) - $row['total_due_amt'];
    $pending_due =  $pending_amount  / $row['due_amt_cal'];

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
    $sub_array[] = $role_arr[$row['role']];
    $sub_array[] = $row['fullname'];
    $sub_array[] = $row['due_amt_cal'];
    $sub_array[] = isset($payable_amount)  && $payable_amount > 0 ? moneyFormatIndia($payable_amount) : 0;
    $sub_array[] = 'Present';
    $payable_amount = max(0, $payable_amount);
    $pending_amount = max(0, $pending_amount);

    if($row['cus_status'] =='15' && strtotime($row['updated_date']) < strtotime($full_date)){
        $sub_array[] = 'Error';
    }
    else if($row['cus_status'] =='16' && strtotime($row['updated_date'])< strtotime($full_date)){
        $sub_array[] = 'Legal';
    }
    else if($payable_amount == 0  && $pending_amount == 0  && $balance_amount == 0){
        $sub_array[] = 'Due Nil';
    }
    else if($payable_amount <= $row['due_amt_cal'] && $pending_amount == 0  &&  ((($row['due_method_scheme'] === '1' || $row['due_method_calc'] ==='Monthly') && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', strtotime($full_date))) ||(($row['due_method_scheme'] != '1'|| $row['due_method_calc'] !='Monthly') && strtotime($row['maturity_date']) >= strtotime($full_date))) && $balance_amount != 0 ){
        $sub_array[] = 'Current';
    }
    else if($pending_amount > 0 &&  (
            (($row['due_method_scheme'] === '1' || $row['due_method_calc'] ==='Monthly') && date('Y-m', strtotime($row['maturity_date'])) >= date('Y-m', strtotime($full_date))) || (($row['due_method_scheme'] != '1'|| $row['due_method_calc'] !='Monthly') && strtotime($row['maturity_date']) > strtotime($full_date))
        )){
        $sub_array[] = 'Pending';
    }
    else if (
    (
        ($balance_amount  > 0) &&((($row['due_method_scheme'] === '1' || $row['due_method_calc'] ==='Monthly') && date('Y-m', strtotime($row['maturity_date'])) < date('Y-m', strtotime($full_date))) ||(($row['due_method_scheme'] != '1'|| $row['due_method_calc'] !='Monthly') && strtotime($row['maturity_date']) < strtotime($full_date)))
    )) 
    {
    $sub_array[] = 'OD';
    }
    else {
        $sub_array[] = 'No Result';
    }

    $data[]      = $sub_array;
    $sno = $sno + 1;
}
function count_all_data($connect)
{
    $query = $connect->query("SELECT count(loan_id) as count_result from in_issue WHERE cus_status >=14 ");
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
    $thecash = $thecash == 0 ? "" : $thecash;
    return $thecash;
}



// Close the database connection
$connect = null;
