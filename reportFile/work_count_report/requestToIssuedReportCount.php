<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];

if (!is_array($user_id)) {
    $user_id = explode(',', $user_id);
}
$user_id = array_map('intval', $user_id);
$user_id_str = implode(',', $user_id);

$userName = $connect->query("
    SELECT fullname FROM user 
    WHERE user_id IN ($user_id_str) AND status = 0 LIMIT 1
")->fetchColumn();

$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

function emptyTypeCounter()
{
    return [
        'new' => 0,
        'renewal' => 0,
        'reactive' => 0,
        'additional' => 0,
        'existing_new' => 0,
        'total' => 0
    ];
}


foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    $reqs = $connect->query("
        SELECT req_id, cus_id, cus_data, cus_status, created_date
        FROM request_creation
        WHERE loan_category = '$cat_id'
          AND insert_login_id IN ($user_id_str)
          AND DATE(created_date) BETWEEN '$from_date' AND '$to_date'
    ")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($reqs)) continue;

    // 🔥 STATUS BUCKETS
    $request = emptyTypeCounter();
    $cancel  = emptyTypeCounter();
    $revoke  = emptyTypeCounter();
    $process = emptyTypeCounter();
    $issued  = emptyTypeCounter();

    foreach ($reqs as $r) {

        $req_id  = $r['req_id'];
        $cus_id  = $r['cus_id'];
        $reqDate = date('Y-m-d', strtotime($r['created_date']));

        // =====================
        // Step 1: Determine type
        // =====================
        $type = '';
        if (strtolower($r['cus_data']) === 'new') {
            $type = 'new';
        } else {
            $issue = $connect->query("
            SELECT ii.cus_status, cc.created_date
            FROM in_issue ii
            LEFT JOIN closed_status cc ON cc.req_id = ii.req_id
            WHERE ii.cus_id = '$cus_id'
              AND ii.cus_status >= 14
              AND ii.req_id != '$req_id'
            ORDER BY ii.req_id DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);

            if (!$issue) {
                $type = 'existing_new';
            } elseif ($issue['cus_status'] >= 14 && $issue['cus_status'] < 20) {
                $type = 'additional';
            } else {
                $closingDate  = date('Y-m-d', strtotime($issue['created_date']));
                $monthEnd     = date('Y-m-t', strtotime($issue['created_date']));
                $nextMonth    = date('Y-m-d', strtotime($monthEnd . ' +1 day'));
                $reactiveDate = date('Y-m-d', strtotime($nextMonth . ' +3 months'));

                if ($closingDate > $reqDate) {
                    $type = 'additional';
                } elseif ($reqDate < $reactiveDate) {
                    $type = 'renewal';
                } else {
                    $type = 'reactive';
                }
            }
        }

        // =====================
        // Step 2: Count request type
        // =====================
        $request[$type]++;
        $request['total']++;

        // =====================
        // Step 3: Count final status
        // =====================
        if (in_array($r['cus_status'], [4, 5, 6, 7])) {
            $cancel[$type]++;
            $cancel['total']++;
        } elseif (in_array($r['cus_status'], [8, 9])) {
            $revoke[$type]++;
            $revoke['total']++;
        } else {
            $isIssued = $connect->query("
            SELECT COUNT(*) FROM in_issue
            WHERE req_id = '$req_id' AND cus_status >= 14
        ")->fetchColumn();

            if ($isIssued) {
                $issued[$type]++;
                $issued['total']++;
            } else {
                $process[$type]++;
                $process['total']++;
            }
        }
    }

    /* =====================
       FINAL ROW
    ===================== */

    $data[] = [
        "sno" => $sno++,
        "fullname" => $userName,
        "loan_category" => $cat_name,

        "request" => $request,
        "cancel"  => $cancel,
        "revoke"  => $revoke,
        "process" => $process,
        "issued"  => $issued
    ];
}
$totals = [
    'request' => emptyTypeCounter(),
    'cancel'  => emptyTypeCounter(),
    'revoke'  => emptyTypeCounter(),
    'process' => emptyTypeCounter(),
    'issued'  => emptyTypeCounter()
];

foreach ($data as $row) {
    foreach ($totals as $key => $val) {
        foreach ($val as $type => $v) {
            $totals[$key][$type] += $row[$key][$type] ?? 0;
        }
    }
}

$data[] = [
    "sno" => "",
    "fullname" => "Total",
    "loan_category" => "",
    "request" => $totals['request'],
    "cancel"  => $totals['cancel'],
    "revoke"  => $totals['revoke'],
    "process" => $totals['process'],
    "issued"  => $totals['issued']
];

echo json_encode(["data" => $data]);
