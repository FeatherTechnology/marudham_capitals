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
    $li_where  = "AND date(li.created_date) <= date('$to_date') AND balance_amount = '0' ";
} else {
    $to_date = date('Y-m-d');
    $where  = "(date(coll.coll_date) <= '" . $to_date . "') ";
    $li_where  = "AND date(li.created_date) <= date('$to_date') AND balance_amount = '0' ";
}

    $where  .= $user_based;
    $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
    $statusObj = [
        '14' => 'Current',
        '15' => 'Error',
        '16' => 'Legal',
        '17' => 'Current',
        '20' => 'In Closed',
        '21' => 'Closed',
    ];
    $column = array(
        'ii.loan_id',
        'alm.line_name',
        'ii.loan_id',
        'ii.updated_date',
        'lc.due_start_from',
        'lc.maturity_date',
        'lc.cus_id_loan',
        'lc.cus_name_loan',
        'cp.mobile1',
        'al.area_name',
        'sal.sub_area_name',
        'lcc.loan_category_creation_name',
        'lc.sub_category',
        'ac.ag_name',
        'iv.responsible',
        'vfi.famname',
        'vfi.relationship',
        'vfi.relation_Mobile',
        'lc.loan_amt',
        'lc.due_amt_cal',
        'lc.due_period',
        'lc.tot_amt_cal',
        'ii.loan_id',
        'ii.loan_id',
        'ii.loan_id',
        'ii.loan_id',
        'ii.loan_id',
        'ii.loan_id',
        'ii.loan_id',
        'ii.loan_id'
    );

$qry = " SELECT req.req_id FROM request_creation req JOIN acknowlegement_customer_profile cp ON req.req_id = cp.req_id
        JOIN customer_status cs ON req.req_id = cs.req_id
        JOIN loan_issue li ON req.req_id = li.req_id  AND DATE(li.created_date) <= DATE('$to_date')  AND balance_amount = '0'
        WHERE req.cus_status BETWEEN 14 AND 18  AND ( cs.sub_status != 'Due Nil' OR (cs.sub_status = 'Due Nil' AND cs.created_date > '$to_date') )

        UNION 

        SELECT li.req_id FROM loan_issue li JOIN closing_customer cc ON li.req_id = cc.req_id LEFT JOIN ( SELECT req_id, MAX(coll_date) AS max_coll_date FROM collection WHERE coll_date <= '$to_date' GROUP BY req_id ) AS latest_collection ON li.req_id = latest_collection.req_id LEFT JOIN collection c ON latest_collection.req_id = c.req_id AND latest_collection.max_coll_date = c.coll_date WHERE DATE(cc.closing_date) >= DATE('$to_date') AND DATE(li.created_date) <= DATE('$to_date') AND ( c.req_id IS NULL OR (c.bal_amt - c.due_amt_track) > 0 )";
   
$run = $connect->query($qry);
$req_id_list = [];
while ($row = $run->fetch()) {
    $req_id_list[] = $row['req_id'];
}
$req_id_list = implode(',', $req_id_list);


    $query = "SELECT
    ii.updated_date AS loan_date,
    lc.maturity_month AS maturity_date,
    lc.cus_id_loan,
    lc.cus_name_loan,
    lc.loan_amt,
    lc.due_amt_cal,
    lc.due_period,
    lc.tot_amt_cal,
    lc.sub_category,
    lc.due_start_from,
    lc.due_method_scheme,
    lc.due_method_calc,
    cp.mobile1,
    alm.line_name AS line,
    ii.loan_id,
    al.area_name,
    sal.sub_area_name,
    lcc.loan_category_creation_name AS loan_cat_name,
    ac.ag_name,
    iv.responsible,
    cls.closed_sts,
    cls.consider_level,
    iv.cus_status,
    ack.updated_date,
    vfi.famname,
    vfi.relationship,
    vfi.relation_Mobile,
    IFNULL(NULLIF(c.pending, ''), 0) AS pending,
    IFNULL(NULLIF(c.payable_amt, ''), 0) AS payable_amt,
    IFNULL(NULLIF(c.total_paid_track, ''), 0) AS total_paid_track,
    IFNULL(NULLIF(c.due_amt_track, ''), 0) AS due_amt_track,
    IFNULL(NULLIF(c.total_due_amt, ''), 0) AS total_due_amt,
    IFNULL(NULLIF(c.bal_amt, ''), lc.tot_amt_cal) AS bal_amt,
    IFNULL(NULLIF(c.coll_id, ''), 0) AS coll_id
FROM
    acknowlegement_loan_calculation lc
JOIN 
    acknowlegement_customer_profile cp ON lc.req_id = cp.req_id
LEFT JOIN 
    verification_family_info vfi ON cp.guarentor_name = vfi.id
JOIN 
    in_issue ii ON lc.req_id = ii.req_id
JOIN 
    loan_issue li ON lc.req_id = li.req_id
JOIN 
    area_list_creation al ON cp.area_confirm_area = al.area_id
JOIN 
    sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
JOIN 
    area_line_mapping alm ON FIND_IN_SET( sal.sub_area_id, alm.sub_area_id )
JOIN 
    in_verification iv ON lc.req_id = iv.req_id
JOIN 
    in_acknowledgement ack ON ack.req_id = iv.req_id
LEFT JOIN 
    loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
LEFT JOIN 
    agent_creation ac ON iv.agent_id = ac.ag_id
LEFT JOIN 
    closed_status cls ON iv.req_id = cls.req_id
LEFT JOIN 
    ( SELECT c.req_id,
           c.pending_amt AS pending,
           c.payable_amt,
           c.total_paid_track,
           c.due_amt_track,
           c.bal_amt,
           c.coll_id,
           ( SELECT SUM(due_amt_track) FROM collection  WHERE req_id = c.req_id AND DATE(coll_date) < '$to_date' ) AS total_due_amt
            FROM collection c
            INNER JOIN (SELECT req_id, MAX(coll_id) AS max_coll_id FROM collection WHERE req_id IN ($req_id_list)
            AND DATE(coll_date) = ( SELECT MAX(DATE(coll_date)) FROM collection c2 WHERE c2.req_id = collection.req_id AND DATE(c2.coll_date) < '$to_date'
            ) GROUP BY req_id
           ) latest
    ON c.req_id = latest.req_id AND c.coll_id = latest.max_coll_id ) c ON lc.req_id = c.req_id 
WHERE
    lc.req_id IN ($req_id_list) ";



if (isset($_POST['search'])) {
        if ($_POST['search'] != "") {
            $query .= " and (ii.loan_id LIKE '%" . $_POST['search'] . "%'
                        OR ii.updated_date LIKE '%" . $_POST['search'] . "%'
                        OR lc.due_start_from LIKE '%" . $_POST['search'] . "%'
                        OR lc.maturity_month LIKE '%" . $_POST['search'] . "%'
                        OR lc.cus_id_loan LIKE '%" . $_POST['search'] . "%'
                        OR lc.cus_name_loan LIKE '%" . $_POST['search'] . "%'
                        OR cp.mobile1 LIKE '%" . $_POST['search'] . "%'
                        OR al.area_name LIKE '%" . $_POST['search'] . "%'
                        OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%'
                        OR lc.sub_category LIKE '%" . $_POST['search'] . "%'
                        OR ac.ag_name LIKE '%" . $_POST['search'] . "%'
                        OR iv.responsible LIKE '%" . $_POST['search'] . "%'
                        OR vfi.famname LIKE '%" . $_POST['search'] . "%'
                        OR vfi.relationship LIKE '%" . $_POST['search'] . "%'
                        OR vfi.relation_Mobile LIKE '%" . $_POST['search'] . "%'
                        OR lc.loan_amt LIKE '%" . $_POST['search'] . "%'
                        OR lc.due_amt_cal LIKE '%" . $_POST['search'] . "%'
                        OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%') ";           
            }
        }
 if (isset($_POST['order'])) {
  $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
  $query .= ' ';
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
    
        if (($row['due_method_calc'] != 'Monthly' && $row['due_method_scheme'] != '1')  ) {
            if((date('d', $start) < date('d', $end)) && (date('m', $start) <= date('m', $end)) && (date('Y', $start) <= date('Y', $end)) ){
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
    $pending_due = max(0, $pending_amount / $row['due_amt_cal']);

    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = date('d-m-Y', strtotime($row['due_start_from']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_date']));
    $sub_array[] = $row['cus_id_loan'];
    $sub_array[] = $row['cus_name_loan'];
    $sub_array[] = $row['mobile1'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = (!empty($row['ag_name'])) ? (($row['responsible'] == '0') ? 'Yes': 'No') : '';
    $sub_array[] = $row['famname'];
    $sub_array[] = $row['relationship'];
    $sub_array[] = $row['relation_Mobile'];
    $sub_array[] = moneyFormatIndia($row['loan_amt']);
    $sub_array[] = moneyFormatIndia($row['due_amt_cal']);
    $sub_array[] = $row['due_period'];
    $sub_array[] = moneyFormatIndia($row['tot_amt_cal']);
    $sub_array[] = isset($balance_amount) && $balance_amount >= 0 ? moneyFormatIndia($balance_amount) : $row['tot_amt_cal'];
    $sub_array[] = isset($balance_due) && $balance_due >= 0 ? number_format($balance_due , 1, '.', ''): 0 ;
    $sub_array[] = isset($pending_amount) && ($pending_amount > 0) ? moneyFormatIndia($pending_amount) : 0;
    $sub_array[] = isset($pending_due) && $pending_due >= 0 ? number_format($pending_due , 1, '.', ''): 0;
    $sub_array[] = isset($row['od_months']) && $row['od_months'] >= 0 ? $row['od_months'] : 0;;
    $sub_array[] = isset($payable_amount) ? moneyFormatIndia($payable_amount) : 0;
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


    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT count(req_id) as count_result  FROM request_creation WHERE cus_status BETWEEN 14 AND 18");
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
