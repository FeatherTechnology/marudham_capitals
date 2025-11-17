<?php
include "C:/inetpub/wwwroot/test_mc_app/ajaxconfig.php";
session_start();
if (isset($_POST['userid'])) {
    $userid = $_POST['userid'];
} elseif (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
} else {
    $userid = null; // Or set a default value or handle the error
}

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}

if (isset($_POST['pending_sts'])) {
    $pending_sts = is_array($_POST['pending_sts']) ? implode(',', $_POST['pending_sts']) : $_POST['pending_sts'];

}

if (isset($_POST['od_sts'])) {
    $od_sts = is_array($_POST['od_sts']) ? implode(',', $_POST['od_sts']) : $_POST['od_sts'];

}

if (isset($_POST['due_nil_sts'])) {
    $due_nil_sts = is_array($_POST['due_nil_sts']) ? implode(',', $_POST['due_nil_sts']) : $_POST['due_nil_sts'];

    
}

if (isset($_POST['closed_sts'])) {
    $closed_sts = is_array($_POST['closed_sts']) ? implode(',', $_POST['closed_sts']) : $_POST['closed_sts'];

}

if (isset($_POST['bal_amt'])) {
    $bal_amt = is_array($_POST['bal_amt']) ? implode(',', $_POST['bal_amt']) : $_POST['bal_amt'];

}

if (isset($_POST['payable'])) {
    $payable_amnts = is_array($_POST['payable']) ? implode(',', $_POST['payable']) : $_POST['payable'];

}

$curdate = date('Y-m-d');
$qry = $connect->query("SELECT lc.cus_id_loan, lc.due_start_from, lc.due_method_scheme, ii.cus_status
        FROM acknowlegement_loan_calculation lc 
        LEFT JOIN in_issue ii ON lc.req_id = ii.req_id 
        WHERE lc.req_id = '$req_id' ");
$row = $qry->fetch();
$cus_id = $row['cus_id_loan'];
if (date('Y-m-d', strtotime($row['due_start_from'])) > date('Y-m-d', strtotime($curdate))  and $bal_amt != 0) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
    if ($row['cus_status'] == '15') {
        $sub_sts = 'Error';
    } elseif ($row['cus_status'] == '16') {
        $sub_sts = 'Legal';
    } else {
        $sub_sts = 'Current';
    }
} else {
    if ($pending_sts == 'true' && $od_sts == 'false') { //using i as 1 so subract it with 1
        if ($row['cus_status'] == '15') {
            $sub_sts = 'Error';
        } elseif ($row['cus_status'] == '16') {
            $sub_sts = 'Legal';
        } else {
            $sub_sts = 'Pending';
        }
    } else if ($od_sts == 'true' && $due_nil_sts == 'false') {
        if ($row['cus_status'] == '15') {
            $sub_sts = 'Error';
        } elseif ($row['cus_status'] == '16') {
            $sub_sts = 'Legal';
        } else {
            $sub_sts = 'OD';
        }
    } elseif ($due_nil_sts == 'true') {
        if ($row['cus_status'] == '15') {
            $sub_sts = 'Error';
        } elseif ($row['cus_status'] == '16') {
            $sub_sts = 'Legal';
        } else {
            $sub_sts = 'Due Nil';
        }
    } elseif ($pending_sts == 'false') {
        if ($row['cus_status'] == '15') {
            $sub_sts = 'Error';
        } elseif ($row['cus_status'] == '16') {
            $sub_sts = 'Legal';
        } else {
            if ($closed_sts == 'true') {
                $sub_sts = "Closed";
            } else {
                $sub_sts = 'Current';
            }
        }
    }
}

//If Due start from date is greater than curdate means payable is "0". For example curdate is "11-03-2025" and the due start date is "01-04-2025" the payable is 0 till april.
if ($row['due_method_scheme'] == '2') { // Weekly
    // Use 'o-W' for year + ISO week number (e.g., 2025-14)
    $due_start = date('o-W', strtotime($row['due_start_from']));
    $cur_week = date('o-W', strtotime($curdate));
    
    if ($due_start > $cur_week) {
        $payable_amnts = '0';
    }

} else if ($row['due_method_scheme'] == '3') { // Daily
    $due_start = date('Y-m-d', strtotime($row['due_start_from']));
    $cur_day = date('Y-m-d', strtotime($curdate));

    if ($due_start > $cur_day) {
        $payable_amnts = '0';
    }

} else { // Monthly (default)
    $due_start = date('Y-m', strtotime($row['due_start_from']));
    $cur_month = date('Y-m', strtotime($curdate));

    if ($due_start > $cur_month) {
        $payable_amnts = '0';
    }
}

$lpdqry = $connect->query("SELECT
        CASE 
            WHEN DAYOFMONTH(MAX(coll_date)) BETWEEN 1 AND 10 THEN '1' 
            WHEN DAYOFMONTH(MAX(coll_date)) BETWEEN 11 AND 15 THEN '2' 
            WHEN DAYOFMONTH(MAX(coll_date)) BETWEEN 16 AND 20 THEN '3' 
            WHEN DAYOFMONTH(MAX(coll_date)) BETWEEN 21 AND 25 THEN '4' 
            WHEN DAYOFMONTH(MAX(coll_date)) BETWEEN 26 AND 31 THEN '5' 
            ELSE '0' 
        END AS date_range
    FROM collection 
    WHERE req_id = '$req_id' ");

    $lpd = $lpdqry->fetch()['date_range']; 


$cmpqry = $connect->query("SELECT COALESCE(SUM(due_amt_track), 0) AS total_due_paid FROM collection WHERE YEAR(coll_date) = YEAR(CURDATE()) AND MONTH(coll_date) = MONTH(CURDATE()) AND req_id = '$req_id' ");
    $cmp = $cmpqry->fetch()['total_due_paid']; 
    $paid_status = ($cmp > 0) ? '1' : '2'; //1  => YES, 2 => NO.


$qry = $connect->query("SELECT * FROM customer_status WHERE req_id = '$req_id' ");
if($qry->rowCount() > 0){
    $query = $connect->query("UPDATE `customer_status` SET `sub_status`='$sub_sts', `payable_amnt` = '$payable_amnts', `bal_amnt`='$bal_amt', `last_paid_date`= '$lpd', `current_month_paid`='$paid_status', `insert_login_id`='$userid', `created_date`=NOW() WHERE `req_id`='$req_id' ");

}else{
    $query = $connect->query("INSERT INTO `customer_status`( `req_id`, `cus_id`, `sub_status`, `payable_amnt`, `bal_amnt`, `last_paid_date`, `current_month_paid`,`insert_login_id`, `created_date`) VALUES ('$req_id', '$cus_id', '$sub_sts', '$payable_amnts', '$bal_amt', '$lpd', '$paid_status', '$userid', NOW() )");
    
}

if($query){
    $result = 1;
}else{
    $result = 2;
}

echo json_encode($result);
