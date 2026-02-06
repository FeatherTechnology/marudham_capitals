<?php
include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

$type = $_POST['type'];

if ($type == 'today') {
    $where = " DATE(coll_date) = CURRENT_DATE  ";

} else if ($type == 'day') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(coll_date) >= DATE('$from_date') && DATE(coll_date) <= DATE('$to_date'))  ";

} else if ($type == 'month') {
    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " (MONTH(coll_date) = '$month' && YEAR(coll_date) = '$year')  ";
    
}

$condition = getSubareaList($connect, $user_id); //condition will be returned if user id selected
getDetials($connect, $where, $condition);

function getDetials($connect, $where, $condition)
{

    //will check based on user's branch if user selected
    //will show only interest amunt under user's branch not others also
    //excluding due type interest , coz interest loans will be sepately calculated. those interest will be collected every month as due amount
    //, COALESCE(ROUND(SUM( CASE WHEN c.due_amt_track > alc.principal_amt_cal / alc.due_period THEN c.due_amt_track - (alc.principal_amt_cal / alc.due_period) ELSE 0 END )), 0) AS total_interest_paid, COALESCE(ROUND(SUM( CASE WHEN c.due_amt_track <= alc.principal_amt_cal / alc.due_period THEN c.due_amt_track ELSE alc.principal_amt_cal / alc.due_period END )), 0) AS total_principal_paid
    // $qry = $connect->query("SELECT COALESCE(ROUND(SUM( CASE WHEN c.due_amt_track > alc.principal_amt_cal / alc.due_period THEN c.due_amt_track - (alc.principal_amt_cal / alc.due_period) ELSE 0 END )), 0) AS total_interest_paid FROM in_verification iv JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id JOIN collection c ON iv.req_id = c.req_id WHERE iv.cus_status > 13 AND due_type != 'Interest' and $where $condition");
    // $row = $qry->fetch();
    // $res['interest_paid'] = $row['total_interest_paid'];

    // $qry = $connect->query("SELECT COALESCE(sum(int_amt_track), 0) as int_amt_track FROM in_verification iv JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id JOIN collection c ON iv.req_id = c.req_id WHERE iv.cus_status > 13 AND due_type = 'Interest' and $where $condition");
    // $row = $qry->fetch();
    // $res['interest_amount'] = $row['int_amt_track'];

    // $response['split_interest'] = moneyFormatIndia($res['interest_paid']);
    // $response['interest_amount'] = moneyFormatIndia($res['interest_amount']);

    $qry = $connect->query("SELECT lc.int_amt_cal, lc.tot_amt_cal, SUM(coll.due_amt_track) AS due_amt_track
    FROM collection coll 
    JOIN in_issue ii ON coll.req_id = ii.req_id 
    JOIN acknowlegement_loan_calculation lc ON coll.req_id = lc.req_id 
    JOIN in_verification iv ON coll.req_id = iv.req_id 
    WHERE iv.cus_status >= 14 
    AND $where $condition
    GROUP BY coll.req_id");
    $interest = 0;
    while($row = $qry->fetch()){
        $interest_calc= $row['int_amt_cal'] / $row['tot_amt_cal'];
        $interest += round($row['due_amt_track'] * $interest_calc, 1);
    }

    $response['split_interest'] = moneyFormatIndia(round($interest));

    echo json_encode($response);
}

function getSubareaList($connect, $user_id)
{
    if (empty($user_id)) {
        return '';
    }

    // Get line_ids assigned to the user
    $stmt = $connect->prepare("SELECT line_id FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || empty($row['line_id'])) {
        return '';
    }

    // convert line_ids to array of integers
    $line_ids = array_map('intval', explode(',', $row['line_id']));
    if (empty($line_ids)) return '';

    // Build placeholders for IN clause
    $placeholders = implode(',', array_fill(0, count($line_ids), '?'));

    // Fetch sub_area_ids from normalized table
    $stmt = $connect->prepare("
        SELECT DISTINCT sub_area_id
        FROM area_line_mapping_sub_area
        WHERE line_map_id IN ($placeholders)
    ");
    $stmt->execute($line_ids);
    $sub_area_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($sub_area_ids)) return '';

    // Build safe placeholders for final condition
    $placeholders_sub = implode(',', array_fill(0, count($sub_area_ids), '?'));
    $GLOBALS['sub_area_params'] = $sub_area_ids; // store params for later prepared statement

    return " AND iv.sub_area IN ($placeholders_sub)";
}

// Close the database connection
$connect = null;
