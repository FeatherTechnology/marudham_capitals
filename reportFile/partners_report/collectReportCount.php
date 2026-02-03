<?php
include '../../ajaxconfig.php';

$to_date = $_POST['to_date'] ?? date('Y-m-d');

/* -----------------------------LOAN CATEGORIES----------------------------- */
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;
$total_today = 0;
$total_till = 0;

foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    /* ----------------------------- TODAY COLLECTION----------------------------- */
    $todayQry = $connect->query("
        SELECT COALESCE(SUM(cl.total_paid_track),0)
        FROM collection cl
        JOIN acknowlegement_loan_calculation alc 
            ON cl.req_id = alc.req_id
        WHERE alc.loan_category = '$cat_id'
          AND DATE(cl.coll_date) = '$to_date'
    ");
    $today_amt = (float)$todayQry->fetchColumn();

    /* -----------------------------TILL NOW COLLECTION----------------------------- */
    $tillQry = $connect->query("
        SELECT COALESCE(SUM(cl.total_paid_track),0)
        FROM collection cl
        JOIN acknowlegement_loan_calculation alc 
            ON cl.req_id = alc.req_id
        WHERE alc.loan_category = '$cat_id'
          AND DATE(cl.coll_date) <= '$to_date'
    ");
    $till_amt = (float)$tillQry->fetchColumn();

    // Skip empty rows (optional)
    if ($today_amt == 0 && $till_amt == 0) {
        continue;
    }

    $data[] = [
        "sno"           => $sno++,
        "fullname"      => "Collection",
        "loan_category" => $cat_name,
        "today"         => $today_amt,
        "till_now"      => $till_amt
    ];

    $total_today += $today_amt;
    $total_till  += $till_amt;
}

/* ----------------------------- TOTAL ROW----------------------------- */
$data[] = [
    "sno"           => "",
    "fullname"      => "Total",
    "loan_category" => "",
    "today"         => $total_today,
    "till_now"      => $total_till
];

echo json_encode(["data" => $data]);
