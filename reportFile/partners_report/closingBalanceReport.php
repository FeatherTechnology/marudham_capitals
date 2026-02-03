<?php
include '../../ajaxconfig.php';

$to_date = $_POST['to_date'] ?? date('Y-m-d');

$data = [];

/* -----------------------------HAND CASH CLOSING----------------------------- */
// Hand Credit
$handCredit = $connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0),2) FROM (
        SELECT SUM(rec_amt) amt FROM ct_hand_collection WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_cr_hoti WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_cr_hinvest WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_cr_hexchange WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_cr_hel WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_cr_hdeposit WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();

// Hand Debit
$handDebit = $connect->query("
    SELECT ROUND(COALESCE(SUM(amt),0),2) FROM (
        SELECT SUM(amount) amt FROM ct_db_bank_deposit WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_db_hinvest WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(netcash) FROM ct_db_hissued WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_db_hel WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_db_hexchange WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_db_hexpense WHERE DATE(created_date) <= '$to_date'
        UNION ALL
        SELECT SUM(amt) FROM ct_db_hdeposit WHERE DATE(created_date) <= '$to_date'
    ) x
")->fetchColumn();
/* -----------------------------BANK CASH CLOSING ----------------------------- */
$bank_total = 0;
$bankQry = $connect->query("SELECT id FROM bank_creation");
while ($bank = $bankQry->fetch(PDO::FETCH_ASSOC)) {

    $stmt = $connect->query("
        SELECT balance 
        FROM bank_stmt
        WHERE bank_id = '{$bank['id']}'
          AND DATE(trans_date) <= '$to_date'
        ORDER BY trans_date DESC, id DESC
        LIMIT 1
    ");

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $bank_total += $row ? (float)$row['balance'] : 0;
}

$bank_closing = round($bank_total, 2);

$agentCreditQry = $connect->query("
    SELECT COALESCE(SUM(amt),0) AS agent_credit
    FROM ct_cr_hag
    WHERE DATE(created_date) <= '$to_date'
");

$agentCredit = (float)$agentCreditQry->fetch()['agent_credit'];

$agentDebitQry = $connect->query("
    SELECT COALESCE(SUM(amt),0) AS agent_debit
    FROM ct_db_hag
    WHERE DATE(created_date) <= '$to_date'
");

$agentDebit = (float)$agentDebitQry->fetch()['agent_debit'];
/* Agent impact */
$agent_hand_op = round($agentDebit - $agentCredit, 2);
/* ----------------------------- FINAL HAND CLOSING ----------------------------- */

$hand_closing = round(($handCredit - $handDebit) - $agent_hand_op, 2);
/* -----------------------------  FINAL ROW----------------------------- */

$data[] = [
    "sno"           => 1,
    "closing_label" => "Closing Balance",
    "hand_cash"     => $hand_closing,
    "bank_cash"     => $bank_closing,
    "total"         => round($hand_closing + $bank_closing, 2)
];

echo json_encode(["data" => $data]);
