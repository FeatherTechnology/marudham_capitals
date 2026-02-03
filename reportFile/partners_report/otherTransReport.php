<?php
include '../../ajaxconfig.php';

$to_date = $_POST['to_date'] ?? date('Y-m-d');

$data = [];
$sno = 1;

$total_credit = 0;
$total_debit  = 0;

/* ==========================
   EL
========================== */
$el_credit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (
        SELECT amt FROM ct_cr_hel WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_cr_bel WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$el_debit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (
        SELECT amt FROM ct_db_hel WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_db_bel WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$data[] = [
    "sno"      => $sno++,
    "fullname" => "EL",
    "credit"   => $el_credit,
    "debit"    => $el_debit
];

$total_credit += $el_credit;
$total_debit  += $el_debit;


/* ========================== INVESTMENT========================== */
$inv_credit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (   
        SELECT amt FROM ct_cr_hinvest WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_cr_binvest WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$inv_debit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (
        SELECT amt FROM ct_db_hinvest WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_db_binvest WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$data[] = [
    "sno"      => $sno++,
    "fullname" => "Investment",
    "credit"   => $inv_credit,
    "debit"    => $inv_debit
];

$total_credit += $inv_credit;
$total_debit  += $inv_debit;

/* ==========================DEPOSIT========================== */
$dep_credit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (
        SELECT amt FROM ct_cr_hdeposit WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_cr_bdeposit WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$dep_debit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (
        SELECT amt FROM ct_db_hdeposit WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_db_bdeposit WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$data[] = [
    "sno"      => $sno++,
    "fullname" => "Deposit",
    "credit"   => $dep_credit,
    "debit"    => $dep_debit
];

$total_credit += $dep_credit;
$total_debit  += $dep_debit;


/* ==========================EXPENSE (DEBIT ONLY)========================== */
$exp_debit = (float)$connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0), 2) FROM (
        SELECT amt FROM ct_db_hexpense WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT amt FROM ct_db_bexpense WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

$data[] = [
    "sno"      => $sno++,
    "fullname" => "Expenses",
    "credit"   => 0,
    "debit"    => $exp_debit
];

$total_debit += $exp_debit;


/* ========================== TOTAL========================== */
$data[] = [
    "sno"      => "",
    "fullname" => "Total",
    "credit"   => $total_credit,
    "debit"    => $total_debit
];

echo json_encode(["data" => $data]);
