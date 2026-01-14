<?php
include('../ajaxconfig.php');

$cus_id = strip_tags($_POST['cus_id']);

$response       = '---';
$hasIssued      = false;
$lastIssuedDate = '';
$latestCusData  = '';

/* ============================
   1️⃣ MAIN REQUEST DATA
   ============================ */
$result = $connect->query("
    SELECT cus_status, cus_data
    FROM request_creation
    WHERE cus_id = '$cus_id'
    ORDER BY created_date DESC
");

if ($result->rowCount() > 0) {

    $i = 0;

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $cus_status = (int)$row['cus_status'];

        // First (latest) record
        if ($i == 0) {
            $latestCusData = $row['cus_data']; // New / Existing
            $response = '---';
        }
        // Second record onwards
        else {
            // Issued range (14–19)
            if ($cus_status >= 14 && $cus_status < 20) {
                $response  = 'Additional';
                $hasIssued = true;
                break; // no need to check further
            }
        }
        $i++;
    }

    /* ============================
       2️⃣ GET LAST ISSUED DATE
       ============================ */
    $csQry = $connect->query("
        SELECT cs.created_date
        FROM closed_status cs
        JOIN in_issue ii ON ii.req_id = cs.req_id
        WHERE ii.cus_id = '$cus_id'
        ORDER BY cs.created_date DESC
        LIMIT 1
    ");

    if ($csQry->rowCount() > 0) {
        $hasIssued = true;
        $lastIssuedDate = $csQry->fetchColumn();
    }

    /* ============================
       3️⃣ RENEWAL / RE-ACTIVE LOGIC
       ============================ */
    if ($hasIssued && $lastIssuedDate && $response !== 'Additional') {

        $monthEnd       = date('Y-m-t', strtotime($lastIssuedDate));
        $nextMonthStart = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
        $reactiveDate   = date('Y-m-d', strtotime($nextMonthStart . ' +3 months'));
        $today          = date('Y-m-d');

        $response = ($today < $reactiveDate) ? 'Renewal' : 'Re-active';
    }

    /* ============================
       4️⃣ EXISTING-NEW LOGIC
       ============================ */
    if (!$hasIssued && $latestCusData === 'Existing') {
        $response = 'Existing-New';
    }

} else {
    $response = '---';
}

echo $response;
$connect = null;
