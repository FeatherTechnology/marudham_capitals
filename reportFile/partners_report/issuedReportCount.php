<?php
include '../../ajaxconfig.php';

$to_date = $_POST['to_date'] ?? date('Y-m-d');

/* -----------------------------LOAN CATEGORIES ---------------------------- */
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

$total_today_amt = 0;
$total_today_cnt = 0;
$total_till_amt  = 0;
$total_till_cnt  = 0;

foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    /* ----------------------------- TODAY ISSUED----------------------------- */
    $todayQry = $connect->query("
        SELECT 
            COUNT(li.req_id) AS cnt,
            SUM(
                COALESCE(li.cash,0) +
                COALESCE(li.cheque_value,0) +
                COALESCE(li.transaction_value,0)
            ) AS amt
        FROM loan_issue li
        JOIN acknowlegement_loan_calculation alc 
            ON alc.req_id = li.req_id
        WHERE alc.loan_category = '$cat_id'
          AND DATE(li.created_date) = '$to_date'
    ");
    $today = $todayQry->fetch(PDO::FETCH_ASSOC);

    /* -----------------------------TILL NOW ISSUED----------------------------- */
    $tillQry = $connect->query("
        SELECT 
            COUNT(li.req_id) AS cnt,
            SUM(
                COALESCE(li.cash,0) +
                COALESCE(li.cheque_value,0) +
                COALESCE(li.transaction_value,0)
            ) AS amt
        FROM loan_issue li
        JOIN acknowlegement_loan_calculation alc 
            ON alc.req_id = li.req_id
        WHERE alc.loan_category = '$cat_id'
          AND DATE(li.created_date) <= '$to_date'
    ");
    $till = $tillQry->fetch(PDO::FETCH_ASSOC);

    $today_amt = (float)$today['amt'];
    $today_cnt = (int)$today['cnt'];
    $till_amt  = (float)$till['amt'];
    $till_cnt  = (int)$till['cnt'];

    // Skip empty rows
    if ($today_cnt == 0 && $till_cnt == 0) {
        continue;
    }

    $data[] = [
        "sno"                  => $sno++,
        "fullname"             => "Loan Issue",
        "loan_category"        => $cat_name,
        "today_issued_amount"  => $today_amt,
        "today_count"          => $today_cnt,
        "total_issued_amount"  => $till_amt,
        "total_count"          => $till_cnt
    ];

    $total_today_amt += $today_amt;
    $total_today_cnt += $today_cnt;
    $total_till_amt  += $till_amt;
    $total_till_cnt  += $till_cnt;
}

/* -----------------------------   TOTAL ROW  ---------------------------- */
$data[] = [
    "sno"                  => "",
    "fullname"             => "Total",
    "loan_category"        => "",
    "today_issued_amount"  => $total_today_amt,
    "today_count"          => $total_today_cnt,
    "total_issued_amount"  => $total_till_amt,
    "total_count"          => $total_till_cnt
];

echo json_encode(["data" => $data]);

