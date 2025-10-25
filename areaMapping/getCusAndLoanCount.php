<?php
include "../ajaxconfig.php";

$countListArr = array();

if (
    isset($_POST['areaid']) && !empty($_POST['areaid'])
    // && isset($_POST['subStatus']) && !empty($_POST['subStatus'])
) {
    $areaid = $_POST['areaid'];
    // $loanCatId = $_POST['loanCatId'];
    // $subStatus = $_POST['subStatus'];
    // $mapId = $_POST['mapId'];

    $to_date = date('Y-m-d');
    $toDate_month_start = date('Y-m-01', strtotime($to_date));

    // Due Nil req_ids in the same month and year
    $dueNilReqIds = [];
    $dueNilQuery = $connect->query("SELECT DISTINCT cs2.req_id 
        FROM customer_status cs2
        JOIN collection col ON cs2.req_id = col.req_id
        WHERE 
            cs2.sub_status = 'Due Nil' 
            AND col.coll_sub_status IN ('Current','Pending','OD')
            AND MONTH(col.coll_date) = MONTH('$to_date')
            AND YEAR(col.coll_date) = YEAR('$to_date')
    ");
    while ($row = $dueNilQuery->fetch(PDO::FETCH_ASSOC)) {
        $dueNilReqIds[] = $row['req_id'];
    }
    $dueNilReqIdStr = !empty($dueNilReqIds) ? implode(',', $dueNilReqIds) : 0;

    // // Pending req_ids
    // $pendingReqIds = [];
    // $pendingQuery = $connect->query("SELECT DISTINCT cs3.req_id 
    //     FROM customer_status cs3
    //     JOIN collection col ON cs3.req_id = col.req_id
    //     WHERE 
    //         cs3.sub_status IN('Current','Due Nil')
    //         AND col.coll_sub_status = 'Pending'
    //         AND MONTH(col.coll_date) = MONTH('$to_date')
    //         AND YEAR(col.coll_date) = YEAR('$to_date')
    // ");
    // while ($row = $pendingQuery->fetch(PDO::FETCH_ASSOC)) {
    //     $pendingReqIds[] = $row['req_id'];
    // }
    // $pendingReqIdStr = !empty($pendingReqIds) ? implode(',', $pendingReqIds) : 0;

    // Closed with first collection Due Nil / Pending / OD
    $coll_DueNilReqIds = [];
    $coll_DueNilQuery = $connect->query("SELECT DISTINCT cs5.req_id
        FROM customer_status cs5
        JOIN (
            SELECT c.req_id, MIN(c.coll_date) AS first_coll_date
            FROM collection c
            WHERE MONTH(c.coll_date) = MONTH('$to_date')
            AND YEAR(c.coll_date) = YEAR('$to_date')
            GROUP BY c.req_id
        ) first_col ON cs5.req_id = first_col.req_id
        JOIN collection col
            ON col.req_id = first_col.req_id
            AND col.coll_date = first_col.first_coll_date
        WHERE cs5.sub_status = 'Closed'
        AND col.coll_sub_status IN ('Pending','OD','Current')
    ");
    while ($row = $coll_DueNilQuery->fetch(PDO::FETCH_ASSOC)) {
        $coll_DueNilReqIds[] = $row['req_id'];
    }
    $coll_DueNilReqIdStr = !empty($coll_DueNilReqIds) ? implode(',', $coll_DueNilReqIds) : 0;

    $loan_category = [];
    $loanCategoryQry = $connect->query("SELECT DISTINCT loan_category FROM loan_calculation WHERE status = 0");
    while ($row = $loanCategoryQry->fetch(PDO::FETCH_ASSOC)) {
        $loan_category[] = (int)$row['loan_category'];
    }

    $loanCatStr = !empty($loan_category) ? implode(',', $loan_category) : 0;

    //  Query 1: Loan Count  
    // JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id , AND alc.loan_category = $loanCatId, AND alm.map_id IN ($mapId)
    // COUNT(DISTINCT ii.loan_id) AS loanCount
    $stmt = $connect->query("SELECT COUNT(DISTINCT ii.loan_id) AS loanCount
FROM in_issue ii 
JOIN acknowlegement_customer_profile acp ON ii.req_id = acp.req_id 
JOIN customer_status cs ON ii.req_id = cs.req_id 
JOIN area_list_creation al ON acp.area_confirm_area = al.area_id 
LEFT JOIN closing_customer cc ON ii.req_id = cc.req_id
JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id
WHERE acp.area_confirm_area IN ($areaid) 
  AND alc.loan_category IN ($loanCatStr)
  AND (
        (cs.sub_status != 'Due Nil' AND cs.sub_status != 'Closed')
        OR ii.req_id IN ($dueNilReqIdStr)
        OR ii.req_id IN ($coll_DueNilReqIdStr)
      )
  AND DATE(ii.updated_date) < '$toDate_month_start';       
    ");
    // while ($row1 = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $loan_id = $row1['loan_id'];
    //     echo $loan_id . "<br>"; // print each loan_id
    // }
    $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
    $countListArr['loan_count'] = $row1['loanCount'];


    //  * Query 2: Customer Count   
    // JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id , AND alc.loan_category = $loanCatId,  AND alm.map_id IN ($mapId)
    $stmt1 = $connect->query("SELECT COUNT(DISTINCT ii.cus_id) AS cusCount 
        FROM in_issue ii 
        JOIN acknowlegement_customer_profile acp ON ii.req_id = acp.req_id 
        JOIN customer_status cs ON ii.req_id = cs.req_id 
        JOIN area_list_creation al ON acp.area_confirm_area = al.area_id 
        LEFT JOIN closing_customer cc ON ii.req_id = cc.req_id
        JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id
        WHERE acp.area_confirm_area IN ($areaid) 
        AND alc.loan_category IN ($loanCatStr)
         AND (
        (cs.sub_status != 'Due Nil' AND cs.sub_status != 'Closed')
        OR ii.req_id IN ($dueNilReqIdStr)
        OR ii.req_id IN ($coll_DueNilReqIdStr)
      )
        AND DATE(ii.updated_date) < '$toDate_month_start'
        
    ");

    $row2 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $countListArr['cus_count'] = $row2['cusCount'];
}

echo json_encode($countListArr);
