<?php
include('../../ajaxconfig.php');
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
    $qry = $connect->query("SELECT COALESCE(SUM(c.due_amt_track), 0) AS due_amt_track, COALESCE(ROUND(SUM( CASE WHEN c.due_amt_track > alc.principal_amt_cal / alc.due_period THEN c.due_amt_track - (alc.principal_amt_cal / alc.due_period) ELSE 0 END )), 0) AS total_interest_paid FROM in_verification iv JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id JOIN collection c ON iv.req_id = c.req_id WHERE iv.cus_status > 13 AND due_type != 'Interest' and $where $condition");
    $row = $qry->fetch();
    $res['interest_paid'] = $row['total_interest_paid'];
    $res['due_amt_track'] = $row['due_amt_track'];

    $qry = $connect->query("SELECT COALESCE(sum(int_amt_track), 0) as int_amt_track FROM in_verification iv JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id JOIN collection c ON iv.req_id = c.req_id WHERE iv.cus_status > 13 AND due_type = 'Interest' and $where $condition");
    $row = $qry->fetch();
    $res['interest_amount'] = $row['int_amt_track'];

    $response['split_interest'] = moneyFormatIndia($res['interest_paid']);
    $response['interest_amount'] = moneyFormatIndia($res['interest_amount']);

    echo json_encode($response);
}

function getSubareaList($connect, $user_id)
{

    if ($user_id != '') { //to get user's sub area id based on user's branch assigned

        $userQry = $connect->query("SELECT line_id FROM USER WHERE user_id = $user_id ");
        while ($rowuser = $userQry->fetch()) {
            $line_id = $rowuser['line_id'];
        }
        $line_id = explode(',', $line_id);
        $sub_area_list = array();
        foreach ($line_id as $line) {
            $groupQry = $connect->query("SELECT sub_area_id FROM area_line_mapping where map_id = $line ");
            $row_sub = $groupQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);
    } else {
        $sub_area_list = '';
    }
    $condition = ($sub_area_list != '') ? " and FIND_IN_SET(iv.sub_area ,'" . $sub_area_list . "')" : '';
    return $condition;
}

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
