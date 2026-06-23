<?php
include('../../ajaxconfig.php');
$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';

$type = $_POST['type'];

if ($type == 'today') {
    $where = " DATE(li.created_date) = CURRENT_DATE";

} else if ($type == 'day') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(li.created_date) >= DATE('$from_date') && DATE(li.created_date) <= DATE('$to_date'))";

} else if ($type == 'month') {
    $month = date('m', strtotime($_POST['month']));
    $year = date('Y', strtotime($_POST['month']));

    $where = " (MONTH(li.created_date) = '$month' && YEAR(li.created_date) = '$year')";

}

$condition = getSubareaList($connect, $user_id); //condition will be returned if user id selected

getDetials($connect, $where, $condition);

function getDetials($connect, $where, $condition)
{
    // >13 means entries moved to collection from issue
    //will show only interest amunt under user's branch not others also
    //excluding due type interest , coz interest loans will be sepately calculated. those interest will be collected every month as due amount
    $qry = $connect->query("SELECT COALESCE(SUM(alc.int_amt_cal), 0) AS int_amt_cal 
    FROM loan_issue li
     JOIN in_issue ii ON li.req_id = ii.req_id
    JOIN acknowlegement_loan_calculation alc ON li.req_id = alc.req_id  
    JOIN in_verification iv ON li.req_id = iv.req_id  
    WHERE due_type != 'Interest' AND ii.cus_status > 13 AND li.balance_amount = 0 AND $where $condition ");
    $row = $qry->fetch();
    $benefit_amount = $row['int_amt_cal']; //interest amount

    //getting only due type interest 
    // $qry = $connect->query("SELECT COALESCE(SUM(alc.int_amt_cal), 0) AS int_amt_cal from in_verification iv
    // JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id  
    // where due_type = 'Interest' AND $where $condition ");
    // $row = $qry->fetch();
    // $interest_amount = $row['int_amt_cal']; //interest amount on interest type loans

    $response['benefit_amount'] = $benefit_amount;
    // $response['interest_amount'] = moneyFormatIndia($interest_amount);

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
