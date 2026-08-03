<?php
include('../ajaxconfig.php');

$cus_id = strip_tags($_POST['cus_id']);

$response      = '---';
$hasIssued     = false;
$latestCusData = '';

/* ============================
   1. CURRENT REQUEST
============================ */
$currentQry = $connect->query("
    SELECT req_id, dor, cus_data
    FROM request_creation
    WHERE cus_id = '$cus_id' 
    ORDER BY req_id DESC
    LIMIT 1
");
if ($currentQry->rowCount() > 0) {
    $current = $currentQry->fetch(PDO::FETCH_ASSOC);
    $currentReqId = $current['req_id'];
    $currentDor   = date('Y-m-d', strtotime($current['dor']));
    $latestCusData = $current['cus_data'];

    /* ============================
       2. PREVIOUS LOAN
    ============================ */

    $loanQry = $connect->query("SELECT
            req.req_id,req.cus_status,cs.created_date AS closed_date,cc.closing_date,cs1.sub_status,
            (SELECT MAX(c.coll_date) FROM collection c WHERE c.req_id = req.req_id AND c.coll_sub_status = 'Due Nil') AS due_nil_date
        FROM request_creation req
        LEFT JOIN closed_status cs ON cs.req_id = req.req_id
        LEFT JOIN customer_status cs1 ON cs1.req_id = req.req_id
        LEFT JOIN closing_customer cc ON cc.req_id = req.req_id
        WHERE req.cus_id='$cus_id' AND req.cus_status >= 14 AND req.req_id < '$currentReqId'
        ORDER BY req.req_id DESC LIMIT 1");

    if ($loanQry->rowCount() > 0) {
        $loan = $loanQry->fetch(PDO::FETCH_ASSOC);
        $hasIssued = true;
        $status = (int)$loan['cus_status'];
        $closedDate = !empty($loan['closed_date'])  ? date('Y-m-d', strtotime($loan['closed_date'])): '';
        $closingDate = !empty($loan['closing_date']) ? date('Y-m-d', strtotime($loan['closing_date'])): '';
        $dueNilDate = !empty($loan['due_nil_date']) ? date('Y-m-d', strtotime($loan['due_nil_date'])): '';

        //    DUE NIL or CURRENT REQUEST <= DUE NIL DATE
        if ($loan['sub_status'] == 'Due Nil' ||  (!empty($dueNilDate) && $currentDor <= $dueNilDate)) {
            $response = 'Reloan';
        }
        //    BEFORE CLOSED STATUS
        else if (!empty($closedDate) && $currentDor < $closingDate) {
            $response = 'Additional';
        }
        //    AFTER CLOSED STATUS BUT BEFORE CLOSING DATE
        else if (((!empty($closingDate) &&  $currentDor >= $closingDate &&  $currentDor <= $closedDate)) || $status == 20) {
            $response = 'Reloan';
        }
        //    ACTIVE LOAN
        else if ($status >= 14 && $status < 20) {
            $response = 'Additional';
        }
        //RENEWAL / RE-ACTIVE
        else if (!empty($closedDate)) {
            $monthEnd = date('Y-m-t', strtotime($closedDate));
            $nextMonth = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
            $reactiveDate = date('Y-m-d', strtotime($nextMonth . ' +6 months'));
            if ($currentDor < $reactiveDate) {
                $response = 'Renewal';
            } else {
                $response = 'Re-active';
            }
        }
    }

    //EXISTING NEW
    if (!$hasIssued && $latestCusData == 'Existing') {
        $response = 'Existing-New';
    }
}

echo $response;

$connect = null;
