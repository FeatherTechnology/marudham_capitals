<?php
include "../ajaxconfig.php";
session_start();
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}

if (isset($_POST['pending_sts'])) {
    $pending_sts = implode(',', $_POST['pending_sts']);
}

if (isset($_POST['od_sts'])) {
    $od_sts = implode(',', $_POST['od_sts']);
}

if (isset($_POST['due_nil_sts'])) {
    $due_nil_sts = implode(',', $_POST['due_nil_sts']);
}

if (isset($_POST['closed_sts'])) {
    $closed_sts = implode(',', $_POST['closed_sts']);
}

if (isset($_POST['bal_amt'])) {
    $bal_amt = implode(',', $_POST['bal_amt']);
}

if (isset($_POST['payable'])) {
    $payable_amnts = implode(',', $_POST['payable']);
}

$curdate = date('Y-m-d');
$qry = $connect->query("SELECT lc.cus_id_loan, lc.due_start_from, ii.cus_status
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
                $sub_sts = "Move To Close";
            } else {
                $sub_sts = 'Current';
            }
        }
    }
}

$qry = $connect->query("SELECT * FROM customer_status WHERE req_id = '$req_id' ");
if($qry->rowCount() > 0){
    $query = $connect->query("UPDATE `customer_status` SET `cus_id`='$cus_id',`sub_status`='$sub_sts',`payable_amnt` = '$payable_amnts', `bal_amnt`='$bal_amt',`insert_login_id`='$userid',`created_date`=now() WHERE `req_id`='$req_id' ");
}else{
    $query = $connect->query("INSERT INTO `customer_status`( `req_id`, `cus_id`, `sub_status`, `payable_amnt`, `bal_amnt`, `insert_login_id`, `created_date`) VALUES ('$req_id','$cus_id','$sub_sts','$payable_amnts','$bal_amt','$userid', now() )");
}

if($query){
    $result = 1;
}else{
    $result = 2;
}

echo json_encode($result);
