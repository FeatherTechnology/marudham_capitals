<?php
include '../../ajaxconfig.php';

$to_date = $_POST['to_date'] ?? date('Y-m-d');

/* ------------------------------------------------- GET ALL BANK IDS ------------------------------------------------- */
$bankQry = $connect->query("SELECT id FROM bank_creation WHERE 1");
$bank_ids = [];

while ($row = $bankQry->fetch(PDO::FETCH_ASSOC)) {
    $bank_ids[] = $row['id'];
}

sort($bank_ids);

/* ------------------------------------------------- OPENING BALANCE FUNCTION------------------------------------------------- */
function getOpeningBalance($connect, $op_date, $bank_ids)
{
    $record = [];

    /* ---------------- HAND CASH OPENING ---------------- */

    // HAND CREDIT
    $handCreditQry = $connect->query("
        SELECT SUM(amt) AS credit FROM (
            SELECT COALESCE(SUM(rec_amt),0) amt FROM ct_hand_collection WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_cr_bank_withdraw WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_cr_hoti WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_cr_hinvest WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_cr_hexchange WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_cr_hel WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_cr_hdeposit WHERE DATE(created_date) < '$op_date'
        ) a
    ");
    $handCredit = (float)$handCreditQry->fetchColumn();

    // HAND DEBIT
    $handDebitQry = $connect->query("
        SELECT SUM(amt) AS debit FROM (
            SELECT COALESCE(SUM(amount),0) amt FROM ct_db_bank_deposit WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_db_hinvest WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(netcash),0) FROM ct_db_hissued WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_db_hel WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_db_hexchange WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_db_hexpense WHERE DATE(created_date) < '$op_date'
            UNION ALL
            SELECT COALESCE(SUM(amt),0) FROM ct_db_hdeposit WHERE DATE(created_date) < '$op_date'
        ) b
    ");
    $handDebit = (float)$handDebitQry->fetchColumn();

    $hand_opening = $handCredit - $handDebit;

    /* ---------------- AGENT ADJUSTMENT ---------------- */

    $agentCredit = (float)$connect->query("
        SELECT COALESCE(SUM(amt),0) FROM ct_cr_hag WHERE DATE(created_date) < '$op_date'
    ")->fetchColumn();

    $agentDebit = (float)$connect->query("
        SELECT COALESCE(SUM(amt),0) FROM ct_db_hag WHERE DATE(created_date) < '$op_date'
    ")->fetchColumn();

    $agent_hand_op = $agentDebit - $agentCredit;
    $hand_opening -= $agent_hand_op;

    /* ---------------- BANK OPENING (TOTAL) ---------------- */
    $bank_total = 0;

    foreach ($bank_ids as $bank_id) {

        $bankStmt = $connect->query("
            SELECT balance 
            FROM bank_stmt 
            WHERE bank_id = '$bank_id'
              AND DATE(trans_date) <'$op_date'
            ORDER BY trans_date DESC, id DESC
            LIMIT 1
        ");

        $row = $bankStmt->fetch(PDO::FETCH_ASSOC);
        $bank_total += ($row && isset($row['balance'])) ? (float)$row['balance'] : 0;
    }

    /* ---------------- FINAL RECORD ---------------- */
    $record['hand_opening']  = round($hand_opening, 2);
    $record['bank_opening']  = round($bank_total, 2);
    $record['total_opening'] = round($record['hand_opening'] + $record['bank_opening'], 2);

    return $record;
}

/* -------------------------------------------------
   GET DATA & SEND RESPONSE
------------------------------------------------- */
$opening = getOpeningBalance($connect, $to_date, $bank_ids);

$response = [
    "data" => [
        [
            "sno"            => 1,
            "opening_label"  => "Opening Balance",
            "hand_cash"      => $opening['hand_opening'],
            "bank_cash"      => $opening['bank_opening'],
            "total"          => $opening['total_opening']
        ]
    ]
];

echo json_encode($response);
