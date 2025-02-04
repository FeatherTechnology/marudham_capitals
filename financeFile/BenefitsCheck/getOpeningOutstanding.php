<?php

include('../../ajaxconfig.php');

$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';
if ($user_id != '') { //to get user's sub area id based on user's branch assigned if user selected

    $userQry = $connect->query("SELECT group_id FROM USER WHERE user_id = $user_id ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
    }
    $group_id = explode(',', $group_id);
    $sub_area_list = array();
    foreach ($group_id as $group) {
        $groupQry = $connect->query("SELECT sub_area_id FROM area_group_mapping where map_id = $group ");
        $row_sub = $groupQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',', $sub_area_ids);
}


$type = $_POST['type'];

if ($type == 'today') {
    $where = " DATE(iv.updated_date) < CURRENT_DATE and iv.cus_status IN (14,15,16,17) ";
    $coll_where = " DATE(c.updated_date) < CURRENT_DATE and iv.cus_status IN (14,15,16,17) ";

} else if ($type == 'day') {

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $where = " (DATE(iv.updated_date) < '$from_date' ) and iv.cus_status IN (14,15,16,17) ";
    $coll_where = " (DATE(c.updated_date) < '$from_date' ) and iv.cus_status IN (14,15,16,17) ";

} else if ($type == 'month') {

    $month = date('m', strtotime($_POST['month']));
    if ($month == 01) {
        $month = 12;
    }
    if ($month == 12) {
        $year = date('Y', strtotime($_POST['month'])) - 1;
    } else {
        $year = date('Y', strtotime($_POST['month']));
    }

    $where = " (MONTH(iv.updated_date) < '$month' && YEAR(iv.updated_date) <= '$year') and iv.cus_status IN (14,15,16,17) ";
    $coll_where = " (MONTH(c.updated_date) < '$month' && YEAR(c.updated_date) <= '$year') and iv.cus_status IN (14,15,16,17) ";
    
}

$condition = ($user_id != '') ? " and FIND_IN_SET(iv.sub_area ,'" . $sub_area_list . "') " : ''; //this condition will check user based request ids in in_verification table
getDetials($connect, $condition, $where, $coll_where);


function getDetials($connect, $condition, $where, $coll_where)
{
    // >13 means entries moved to collection from issue
    //removeing customer status and greater than symbol fo collection table//replace will only work for day type
    //reason to use where condition in collection is , we only need collection on particular date for calculating outstanding amt
    //will check based on user's branch if user selected
    //will show only interest amunt under user's branch not others also
    $qry = $connect->query("SELECT 
                        SUM(
                            CASE 
                                WHEN COALESCE(alc.tot_amt_cal, 0) != 0 THEN COALESCE(alc.tot_amt_cal, 0)
                                ELSE COALESCE(alc.principal_amt_cal, 0)
                            END
                        ) AS calculated_amount
                        FROM in_verification iv
                        JOIN acknowlegement_loan_calculation alc ON iv.req_id = alc.req_id
                        WHERE $where $condition ");
    //fetching overall collection amount to be get from customers
    $row = $qry->fetch();
    $total_outstanding = $row['calculated_amount']; //Total outstanding amount

    $qry = $connect->query("SELECT 
                        COALESCE((sum(due_amt_track) + sum(princ_amt_track)), 0 ) as coll_amt_track 
                        FROM in_verification iv
                        JOIN collection c ON iv.req_id = c.req_id
                        WHERE $coll_where $condition ");
    //getting collected amount till mentioned date
    $row = $qry->fetch();
    $collected_outstanding = $row['coll_amt_track']; //collected outstanding amount

    $response['opening_outstanding'] = intval($total_outstanding) - intval($collected_outstanding);
    $response['opening_outstanding'] = moneyFormatIndia($response['opening_outstanding']);

    echo json_encode($response);
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
