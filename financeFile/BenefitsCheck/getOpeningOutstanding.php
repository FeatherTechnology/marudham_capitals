<?php

include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');

$user_id = ($_POST['user_id'] != '') ? $_POST['user_id'] : '';
if ($user_id != '') {

    // 1️⃣ Get user's group IDs
    $stmt = $connect->prepare("SELECT group_id FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rowuser && !empty($rowuser['group_id'])) {

        $group_ids = array_map('intval', explode(',', $rowuser['group_id']));
        $placeholders = implode(',', array_fill(0, count($group_ids), '?'));

        // 2️⃣ Get sub areas from normalized table
        $stmt = $connect->prepare("
            SELECT DISTINCT sub_area_id
            FROM area_group_mapping_sub_area
            WHERE group_map_id IN ($placeholders)
        ");
        $stmt->execute($group_ids);

        $sub_area_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $sub_area_list = implode(',', $sub_area_ids);
    }
}



$type = $_POST['type'];

if ($type == 'today') {
    $to_date = date('Y-m-d', strtotime('-1 day'));

    $li_where  = " AND date(li.created_date) <= date('$to_date') AND balance_amount = '0' "; 

} else if ($type == 'day') {
    $to_date = date('Y-m-d', strtotime($_POST['from_date'].'-1 day'));

    $li_where  = " AND date(li.created_date) <= date('$to_date') AND balance_amount = '0' "; 

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
    
    $to_date = date('Y-m-t', strtotime($_POST['month'].'-01 -1 month'));

    $li_where  = " AND date(li.created_date) <= date('$to_date') AND balance_amount = '0' "; 
}

$condition = (!empty($sub_area_list)) ? " AND iv.sub_area IN ($sub_area_list) ": '';

getDetials($connect, $condition, $li_where, $to_date);


function getDetials($connect, $condition, $li_where, $to_date)
{
    // >13 means entries moved to collection from issue
    //reason to use where condition in collection is , we only need collection on particular date for calculating outstanding amt
    //will check based on user's branch if user selected

    $qry = "SELECT req.req_id FROM request_creation req
    JOIN in_verification iv ON req.req_id = iv.req_id
    JOIN loan_issue li ON req.req_id = li.req_id $li_where
    WHERE req.cus_status BETWEEN 14 AND 18 $condition

    UNION

    SELECT cc.req_id FROM closing_customer cc JOIN loan_issue li ON cc.req_id = li.req_id WHERE date(cc.closing_date) > date('$to_date') AND date(li.created_date) <= date('$to_date')  ";

    $run = $connect->query($qry);
    $req_id_list = [];
    while ($row = $run->fetch()) {
        $req_id_list[] = $row['req_id'];
    }
    $req_id_list = implode(',', $req_id_list);
    
    $qry = $connect->query("SELECT alc.due_type, alc.tot_amt_cal, c.due_amt_track, alc.principal_amt_cal, c.princ_amt_track
                            FROM acknowlegement_loan_calculation alc 
                            LEFT JOIN ( SELECT c.req_id, SUM(c.due_amt_track) AS due_amt_track, SUM(c.princ_amt_track) AS princ_amt_track FROM collection c WHERE (date(coll_date) <= '$to_date') GROUP BY c.req_id ) c ON c.req_id = alc.req_id 
                            JOIN in_issue ii ON alc.req_id = ii.req_id 
                            WHERE alc.req_id IN ($req_id_list) ");

    $balance_amount = 0;
    while($row = $qry->fetch()){
            $balance_amount += ($row['due_type'] != 'Interest') ?
        intVal($row['tot_amt_cal']) - intVal($row['due_amt_track']) :
        intVal($row['principal_amt_cal']) - intVal($row['princ_amt_track']);

    };

    $response['opening_outstanding'] = moneyFormatIndia($balance_amount);

    echo json_encode($response);
}

// Close the database connection
$connect = null;
