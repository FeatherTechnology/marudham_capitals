<?php
include('../ajaxconfig.php');

if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$records = [
    'loan_count'    => 0,
    'existing_type' => '',
    'first_loan'    => '',
    'travel'        => ''
];
$records['existing_type'] = 'Existing-New';
/* ============================
   1. GET CURRENT REQUEST
============================ */

$currentQry = $connect->query("
    SELECT req_id, dor, cus_data
    FROM request_creation
    WHERE cus_id='$cus_id'
    ORDER BY req_id DESC
    LIMIT 1
");
if ($currentQry->rowCount() > 0) {

    $current = $currentQry->fetch(PDO::FETCH_ASSOC);

    $currentReqId = $current['req_id'];
    $currentDor   = date('Y-m-d', strtotime($current['dor']));
    $cusData      = $current['cus_data'];

    /* ============================
       2. GET PREVIOUS ISSUED LOAN
    ============================ */

    $loanQry = $connect->query(" SELECT req.req_id,req.cus_status,cs.created_date AS closed_date,cc.closing_date,cs1.sub_status,
            (SELECT MAX(c.coll_date) FROM collection c WHERE c.req_id = req.req_id AND c.coll_sub_status='Due Nil') AS due_nil_date
        FROM request_creation req
        LEFT JOIN closed_status cs ON cs.req_id = req.req_id
        LEFT JOIN customer_status cs1 ON cs1.req_id = req.req_id
        LEFT JOIN closing_customer cc ON cc.req_id = req.req_id
        WHERE req.cus_id='$cus_id' AND req.cus_status >= 14 AND req.req_id < '$currentReqId'
        ORDER BY req.req_id DESC LIMIT 1");

    $records['loan_count'] = $loanQry->rowCount();
    if ($loanQry->rowCount() > 0) {
        $loan = $loanQry->fetch(PDO::FETCH_ASSOC);
        $status = (int)$loan['cus_status'];
        $closedDate = !empty($loan['closed_date']) ? date('Y-m-d', strtotime($loan['closed_date'])): '';
        $closingDate = !empty($loan['closing_date']) ? date('Y-m-d', strtotime($loan['closing_date'])) : '';
        $dueNilDate = !empty($loan['due_nil_date']) ? date('Y-m-d', strtotime($loan['due_nil_date'])) : '';
        /* =====================================
           1. DUE NIL -> RELOAN and CURRENT REQUEST <= DUE NIL DATE => RELOAN
        ===================================== */
        if ($loan['sub_status'] == 'Due Nil' ||(!empty($dueNilDate) && $currentDor <= $dueNilDate)) {
            $records['existing_type'] = 'Reloan';
        }

        /* =====================================
           2. BEFORE CLOSED STATUS => ADDITIONAL
        ===================================== */
        else if (!empty($closedDate) && $currentDor < $closingDate) {
            $records['existing_type'] = 'Additional';
        }

        /* =====================================
           3. AFTER CLOSED STATUS BEFORE CLOSING DATE => RELOAN
        ===================================== */
        else if ((!empty($closingDate) &&!empty($closedDate) && $currentDor >= $closingDate && $currentDor <= $closedDate)
            || $status == 20) {
            $records['existing_type'] = 'Reloan';
        }

        /* =====================================
           4. ACTIVE LOAN=> ADDITIONAL
        ===================================== */
        else if ( $status >= 14 && $status < 20 ) {
            $records['existing_type'] = 'Additional';
        }

        /* =====================================
           5. RENEWAL / RE-ACTIVE
        ===================================== */
        else if (!empty($closedDate)) {
            $monthEnd = date('Y-m-t', strtotime($closedDate));
            $nextMonth = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
            $reactiveDate = date('Y-m-d', strtotime($nextMonth . ' +6 months'));
            if ($currentDor < $reactiveDate) {
                $records['existing_type'] = 'Renewal';
            } else {
                $records['existing_type'] = 'Re-active';
            }
        }
    } else {
        if ($cusData == 'Existing') {
            $records['existing_type'] = 'Existing-New';
        }
    }
}

if ($records['loan_count'] > 0) {
    $result = $connect->query("SELECT created_date FROM `loan_issue` where cus_id='$cus_id' and balance_amount = 0 ORDER BY created_date LIMIT 1");
    $res = $result->fetch();
    $first_loan_date = date('d-m-Y', strtotime($res['created_date']));

    $records['first_loan'] =  $first_loan_date;

    $now = new DateTime(); // current datetime object
    $custom = new DateTime($res['created_date']); // custom datetime object

    $diff = $custom->diff($now); // difference between two dates

    $years = $diff->y; // number of years in difference
    $months = $diff->m; // number of months in difference

    $records['travel'] = $months . ' Months,' . $years . ' Years.';
} else {
    $records['first_loan'] = '';
    $records['travel'] = '';
}
echo json_encode($records);

$connect = null;
