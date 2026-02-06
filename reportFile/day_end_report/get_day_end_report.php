<?php
session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

// $bank_details = '';

$userQry = $connect->query("SELECT id FROM bank_creation WHERE 1");

$bank_ids = [];
while ($row = $userQry->fetch()) {
    $bank_ids[] = $row['id']; 
}

sort($bank_ids);
// $bank_details = implode(',', $bank_ids);


if (isset($_POST['search_date']) && $_POST['search_date'] != '') {
    $search_date = $_POST['search_date'];
    $date = new DateTime($search_date);
    $full_date = $date->format('Y-m-d');
    $till_now_date = $date->format('Y-m-01');
}

$record = getOpeningBalance($connect, $full_date, $bank_ids);

// Get hand opening
$hand_opening_balance = $record['hand_opening'];



$records = getClosingBalance($connect, $full_date, $bank_ids, $till_now_date);
$hand = $records['hand_summary'];

$h_collection = $hand['ct_hand_collection'];
$h_issued = $hand['ct_hand_issued'];
$h_hand_expense = $hand['hand_expense'];
$h_agent = $hand['ct_cr_agent'] - $hand['ct_db_agent'];
$h_deposite = $hand['hand_cr_deposit'] - $hand['hand_db_deposite'];
$h_exchange = $hand['hand_cr_exchange'] - $hand['hand_db_exchange'];
$h_el = $hand['hand_cr_el'] - $hand['hand_db_el'];
$h_invest = $hand['hand_cr_hinvest'] - $hand['hand_db_hinvest'];
$h_contra = $hand['hand_cr_bank_withdraw'] - $hand['hand_db_bank_deposit'];
$h_till_now_collection = $hand['till_now_hand_collection'];
$h_till_now_loan_issued = $hand['till_now_hand_loan_issued'];
$h_till_now_agent = $hand['till_now_hand_agent_cr_issued'] - $hand['till_now_hand_agent_db_issued'];

$h_till_now_hand_expense = $hand['till_now_hand_hexpense'];
$hand_other_income = $hand['hand_other_income'];


$bankData = $records['banks'];
$bank_total_collection = 0;
$bank_total_loan_issed = 0;
$bank_total_agent = 0;
$bank_total_expense = 0;

foreach ($records['banks'] as $bank) {
    $bank_total_collection += (float) $bank['total_collection'];
    $bank_total_loan_issed += (float) $bank['total_loan_issued'];

    $ag_cr = isset($bank['ag_cr_amt_upto_date']) ? (float)$bank['ag_cr_amt_upto_date'] : 0;
    $ag_db = isset($bank['ag_db_amt_upto_date']) ? (float)$bank['ag_db_amt_upto_date'] : 0;
    $bank_total_agent += $ag_cr - $ag_db;

    $bank_total_expense += (float) $bank['bexpense_amt_upto_date'];
}
$collection_till_now = $h_till_now_collection + $bank_total_collection;
$loan_issue_till_now = $h_till_now_loan_issued + $bank_total_loan_issed;
$agent_till_now = $h_till_now_agent + $bank_total_agent;
$expense_till_now = $h_till_now_hand_expense + $bank_total_expense;


?>

<table class="table custom-table">
    <thead>
        <th width='150'></th>
        <th>Hand Cash</th>
        <?php foreach ($record['banks'] as $bank): ?>
            <th><?php echo $bank['bank_name']; ?></th>
        <?php endforeach; ?>
        <th>Total</th>
        <th>Till Now</th>
    </thead>

    <tbody>
        <tr>
            <td><b>Opening Balance</b></td>
            <td><?php echo moneyFormatIndia($hand_opening_balance); ?></td>
            <?php
            $total = $hand_opening_balance;
            foreach ($record['banks'] as $bank):
                $total += $bank['bank_opening'];
            ?>
                <td><?php echo moneyFormatIndia($bank['bank_opening']); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>Collection</b></td>
            <td><?php echo moneyFormatIndia($h_collection); ?></td>
            <?php
            $total_collection = $h_collection;
            foreach ($bankData as $bank):
                $total_collection += $bank['collection_on_date'];
            ?>
                <td><?php echo moneyFormatIndia($bank['collection_on_date']); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_collection); ?></td>
            <td><?php echo moneyFormatIndia($collection_till_now); ?></td>
        </tr>

        <tr>
            <td><b>Deposit</b></td>
            <td><?php echo moneyFormatIndia($h_deposite); ?></td>
            <?php
            $total_deposite = $h_deposite;
            foreach ($bankData as $bank):
                $diff = $bank['cr_bdeposit_amt'] - $bank['db_deposit_amt'];
                $total_deposite += $diff;
            ?>
                <td><?php echo moneyFormatIndia($diff); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_deposite); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>Exchange</b></td>
            <td><?php echo moneyFormatIndia($h_exchange); ?></td>
            <?php
            $total_exchange = $h_exchange;
            foreach ($bankData as $bank):
                $diff = $bank['cr_bexchange'] - $bank['db_bexchange'];
                $total_exchange += $diff;
            ?>
                <td><?php echo moneyFormatIndia($diff); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_exchange); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>EL</b></td>
            <td><?php echo moneyFormatIndia($h_el); ?></td>
            <?php
            $total_el = $h_el;
            foreach ($bankData as $bank):
                $diff = $bank['cr_bel_amt_on_date'] - $bank['db_bel_amt_on_date'];
                $total_el += $diff;
            ?>
                <td><?php echo moneyFormatIndia($diff); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_el); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>Investment</b></td>
            <td><?php echo moneyFormatIndia($h_invest); ?></td>
            <?php
            $total_invest = $h_invest;
            foreach ($bankData as $bank):
                $diff = $bank['cr_binvest'] - $bank['db_binvest'];
                $total_invest += $diff;
            ?>
                <td><?php echo moneyFormatIndia($diff); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_invest); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>Other Income</b></td>
            <td><?php echo moneyFormatIndia($hand_other_income); ?></td>
            <?php
            $total_other_income = $hand_other_income;
            foreach ($bankData as $bank):
                $total_other_income += $bank['bank_other_income'];
            ?>
                <td><?php echo moneyFormatIndia($bank['bank_other_income']); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_other_income); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>Contra</b></td>
            <td><?php echo moneyFormatIndia($h_contra); ?></td>
            <?php
            $total_contra = $h_contra;
            foreach ($bankData as $bank):
                $diff = $bank['cr_cash_deposit'] - $bank['db_cash_withdraw'];
                $total_contra += $diff;
            ?>
                <td><?php echo moneyFormatIndia($diff); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_contra); ?></td>
            <td></td>
        </tr>

        <tr>
            <td><b>Loan Issue</b></td>
            <td><?php echo moneyFormatIndia( -$h_issued); ?></td>
            <?php
            $total_loan_issue = $h_issued;
            foreach ($bankData as $bank):
                $total_loan_issue += $bank['loan_issue_on_date'];
            ?>
                <td><?php echo moneyFormatIndia(- $bank['loan_issue_on_date']); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia(-$total_loan_issue); ?></td>
            <td><?php echo moneyFormatIndia(-$loan_issue_till_now); ?></td>
        </tr>

        <tr>
            <td><b>Agent</b></td>
            <td><?php echo moneyFormatIndia($h_agent); ?></td>
            <?php
            $total_agent = $h_agent;
            foreach ($bankData as $bank):
                $diff = $bank['ag_cr_amt'] - $bank['ag_db_amt'];
                $total_agent += $diff;
            ?>
                <td><?php echo moneyFormatIndia($diff); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia($total_agent); ?></td>
            <td><?php echo moneyFormatIndia($agent_till_now); ?></td>
        </tr>

        <tr>
            <td><b>Expenses</b></td>
            <td><?php echo moneyFormatIndia( -$h_hand_expense); ?></td>
            <?php
            $total_exp = $h_hand_expense;
            foreach ($bankData as $bank):
                $total_exp += $bank['bexpense_amt'];
            ?>
                <td><?php echo moneyFormatIndia(- $bank['bexpense_amt']); ?></td>
            <?php endforeach; ?>
            <td><?php echo moneyFormatIndia(-$total_exp); ?></td>
            <td><?php echo moneyFormatIndia(-$expense_till_now); ?></td>
        </tr>
    </tbody>

    <tfoot>
        <tr style="font-weight:bold; background:#f0f0f0;">
            <td>Closing Balance</td>
            <td>
                <?php
                $hand_total = $hand_opening_balance +  $h_collection + $h_deposite + $h_exchange + $h_el +  $h_invest + $hand_other_income + $h_contra - ($h_issued) - (-$h_agent) -($h_hand_expense);
                echo moneyFormatIndia($hand_total);
                ?>
            </td>

            <?php
            $grand_total = $hand_total;
            foreach ($bankData as $index => $bank) {
                $bank_total = 0;

                // Opening Balance
                $bank_opening = $record['banks'][$index]['bank_opening'] ?? 0;
                $bank_total += $bank_opening;

                // Income Side (additions)
                $bank_total += $bank['collection_on_date'] ?? 0;
                $bank_total += ($bank['cr_bdeposit_amt'] ?? 0) - ($bank['db_deposit_amt'] ?? 0);
                $bank_total += ($bank['cr_bexchange'] ?? 0) - ($bank['db_bexchange'] ?? 0);
                $bank_total += ($bank['cr_bel_amt_on_date'] ?? 0) - ($bank['db_bel_amt_on_date'] ?? 0);
                $bank_total += ($bank['cr_binvest'] ?? 0) - ($bank['db_binvest'] ?? 0);
                $bank_total += ($bank['cr_cash_deposit'] ?? 0) - ($bank['db_cash_withdraw'] ?? 0);
                $bank_total += $bank['bank_other_income'] ?? 0;

                // Expense Side (use conditional subtraction like hand_total)
                $loan_issue = $bank['loan_issue_on_date'] ?? 0;
                $bank_total -= $loan_issue;

                $agent_diff = ($bank['ag_cr_amt'] ?? 0) - ($bank['ag_db_amt'] ?? 0);
                $bank_total -= (-$agent_diff);

                $bexpense = $bank['bexpense_amt'] ?? 0;
                $bank_total -= $bexpense ;

                // Add to grand total
                $grand_total += $bank_total;

                echo "<td>" . moneyFormatIndia($bank_total) . "</td>";
            } ?>
            <td><?php echo moneyFormatIndia($grand_total); ?></td>
            <td></td>
        </tr>
    </tfoot>
</table>

<?php
function getOpeningBalance($connect, $op_date, $bank_detail)
{
    $record = [];

    // HAND CASH OPENING
    $handCreditQry = $connect->query("SELECT
        SUM(amt) AS hand_credits
        FROM (
            (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) < '$op_date' )
        ) AS Hand_Credit_Opening
    ");
    $handCredit = $handCreditQry->fetch()['hand_credits'];

    $handDebitQry = $connect->query("SELECT
        SUM(amt) AS hand_debits
        FROM (
            (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) < '$op_date' )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) < '$op_date' )
        ) AS Hand_Debit_Opening
    ");
    $handDebit = $handDebitQry->fetch()['hand_debits'];

    $record['hand_opening'] = intval($handCredit) - intval($handDebit);

    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE date(created_date) < '$op_date' )
        ) AS Agent_Credit_Opening
    ");
    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE date(created_date) < '$op_date' )
        ) AS Agent_Debit_Opening
    ");
    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    $agent_hand_op = intval($agentCredit) - intval($agentDebit);
    $record['hand_opening'] -= -$agent_hand_op;

    // BANK OPENING (Dynamic)
    // $bank_details_arr = explode(',', $bank_detail);
    $record['banks'] = [];

    foreach ($bank_detail as $val) {
        $bankNameQry = $connect->query("SELECT bank_name FROM bank_creation WHERE id = '$val' ");
        $bank_name = $bankNameQry->fetchColumn();
         $bankQry = $connect->query("SELECT balance FROM bank_stmt WHERE bank_id = '$val' AND DATE(trans_date) < '$op_date' ORDER BY trans_date DESC,id DESC LIMIT 1 ;");
         $row = $bankQry->fetch(PDO::FETCH_ASSOC);
        $bank_opening = ($row && isset($row['balance'])) ? (float)$row['balance'] : 0;

        $record['banks'][] = [
            'bank_id' => $val,
            'bank_name' => $bank_name,
            'bank_opening' => $bank_opening
        ];
    }

    return $record;
}



function getClosingBalance($connect, $closing_date, $bank_detail, $till_now_date)
{
    $bank_detail = implode(',', $bank_detail);

    $handcollection = $connect->query("SELECT
    (SELECT COALESCE(SUM(rec_amt), 0) FROM ct_hand_collection WHERE date(created_date) = '$closing_date') AS ct_hand_collection,
    (SELECT COALESCE(SUM(netcash), 0) FROM ct_db_hissued WHERE date(created_date) = '$closing_date') AS ct_hand_issued,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hag WHERE date(created_date) = '$closing_date') AS ct_cr_agent,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hag WHERE date(created_date) = '$closing_date') AS ct_db_agent,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hexpense WHERE date(created_date) = '$closing_date') AS hand_expense,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hdeposit WHERE date(created_date) = '$closing_date') AS hand_cr_deposit,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hdeposit WHERE date(created_date) = '$closing_date') AS hand_db_deposite,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hexchange WHERE date(created_date) = '$closing_date') AS hand_cr_exchange,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hexchange WHERE date(created_date) = '$closing_date') AS hand_db_exchange,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hel WHERE date(created_date) = '$closing_date') AS hand_cr_el,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hel WHERE date(created_date) = '$closing_date') AS hand_db_el,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hinvest WHERE date(created_date) = '$closing_date') AS hand_cr_hinvest,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_db_hinvest WHERE date(created_date) = '$closing_date') AS hand_db_hinvest,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_hoti WHERE date(created_date) = '$closing_date') AS hand_other_income,
    (SELECT COALESCE(SUM(amt), 0) FROM ct_cr_bank_withdraw WHERE date(created_date) = '$closing_date') AS hand_cr_bank_withdraw,
    (SELECT COALESCE(SUM(amount), 0) FROM ct_db_bank_deposit WHERE date(created_date) = '$closing_date') AS hand_db_bank_deposit,
    
     
    (SELECT COALESCE(SUM(rec_amt), 0) FROM ct_hand_collection  WHERE DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59') AS till_now_hand_collection,

    (SELECT COALESCE(SUM(netcash), 0)   FROM ct_db_hissued   WHERE DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59') AS till_now_hand_loan_issued,

    (SELECT COALESCE(SUM(amt), 0)   FROM ct_cr_hag  WHERE DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59') AS till_now_hand_agent_cr_issued,

    (SELECT COALESCE(SUM(amt), 0)  FROM ct_db_hag   WHERE DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59') AS till_now_hand_agent_db_issued,

    (SELECT COALESCE(SUM(amt), 0)  FROM ct_db_hexpense   WHERE DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59') AS till_now_hand_hexpense
    
    
");

    $handCollection = $handcollection->fetch(PDO::FETCH_ASSOC);


    $bankQry = $connect->query("SELECT
    bn.bank_name,
    bn.id AS bank_id,
    
    COALESCE(bc_on.collection_on_date, 0)        AS collection_on_date,
    COALESCE(bi_on.loan_issue_on_date, 0)        AS loan_issue_on_date,
    COALESCE(db_on.ag_db_amt, 0)                 AS ag_db_amt,
    COALESCE(cr_on.ag_cr_amt, 0)                 AS ag_cr_amt,
    COALESCE(be_on.bexpense_amt, 0)              AS bexpense_amt,
    COALESCE(cr_cd_on.cr_cash_deposit, 0)        AS cr_cash_deposit,
    COALESCE(db_cw_on.db_cash_withdraw, 0)       AS db_cash_withdraw,
    COALESCE(bd_on.db_deposit_amt, 0)            AS db_deposit_amt,
    COALESCE(cbd_on.cr_bdeposit_amt, 0)          AS cr_bdeposit_amt,
    COALESCE(bex_on.cr_bexchange, 0)             AS cr_bexchange,
    COALESCE(dbex_on.db_bexchange, 0)            AS db_bexchange,
    COALESCE(bel_on.cr_bel_amt_on_date, 0)       AS cr_bel_amt_on_date,
    COALESCE(dbel_on.db_bel_amt_on_date, 0)      AS db_bel_amt_on_date,
    COALESCE(dbinv_on.db_binvest, 0)             AS db_binvest,
    COALESCE(crinv_on.cr_binvest, 0)             AS cr_binvest,
    COALESCE(boti_on.bank_other_income, 0)       AS bank_other_income,

    total_coll.total_collection,
    total_loan.total_loan_issued,
    total_agdb.ag_db_amt_upto_date,
    total_agcr.ag_cr_amt_upto_date,
    total_bexp.bexpense_amt_upto_date

FROM bank_creation bn

LEFT JOIN (SELECT bank_id, SUM(CASE WHEN DATE(created_date) = '$closing_date' THEN total_paid_track END) AS collection_on_date FROM collection WHERE 1 GROUP BY bank_id ) bc_on           ON bc_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN DATE(created_date) = '$closing_date' THEN COALESCE(cheque_value, 0) + COALESCE(transaction_value, 0) END) AS loan_issue_on_date FROM loan_issue WHERE 1 GROUP BY bank_id ) bi_on ON bi_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS ag_db_amt FROM ct_db_bag WHERE 1 GROUP BY bank_id) db_on ON db_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS ag_cr_amt FROM ct_cr_bag WHERE 1 GROUP BY bank_id ) cr_on  ON cr_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS bexpense_amt FROM ct_db_bexpense WHERE 1 GROUP BY bank_id ) be_on  ON be_on.bank_id = bn.id

LEFT JOIN ( SELECT to_bank_id AS bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS cr_cash_deposit FROM ct_cr_cash_deposit WHERE 1 GROUP BY to_bank_id ) cr_cd_on  ON cr_cd_on.bank_id = bn.id

LEFT JOIN ( SELECT from_bank_id AS bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS db_cash_withdraw FROM ct_db_cash_withdraw WHERE 1 GROUP BY from_bank_id ) db_cw_on  ON db_cw_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS db_deposit_amt FROM ct_db_bdeposit WHERE 1 GROUP BY bank_id ) bd_on  ON bd_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS cr_bdeposit_amt FROM ct_cr_bdeposit WHERE 1 GROUP BY bank_id ) cbd_on ON cbd_on.bank_id = bn.id

LEFT JOIN ( SELECT from_bank_id AS bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS cr_bexchange FROM ct_cr_bexchange  WHERE 1 GROUP BY from_bank_id ) bex_on ON bex_on.bank_id = bn.id

LEFT JOIN ( SELECT from_acc_id AS bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS db_bexchange FROM ct_db_bexchange WHERE 1 GROUP BY from_acc_id ) dbex_on ON dbex_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS cr_bel_amt_on_date FROM ct_cr_bel WHERE 1 GROUP BY bank_id ) bel_on ON bel_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS db_bel_amt_on_date FROM ct_db_bel WHERE 1 GROUP BY bank_id ) dbel_on ON dbel_on.bank_id = bn.id

LEFT JOIN (
    SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS db_binvest FROM ct_db_binvest  WHERE 1 GROUP BY bank_id ) dbinv_on ON dbinv_on.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS cr_binvest FROM ct_cr_binvest WHERE 1 GROUP BY bank_id ) crinv_on ON crinv_on.bank_id = bn.id

LEFT JOIN ( SELECT to_bank_id AS bank_id, SUM(CASE WHEN created_date = '$closing_date' THEN amt END) AS bank_other_income FROM ct_cr_boti WHERE 1 GROUP BY to_bank_id ) boti_on ON boti_on.bank_id = bn.id

-- cumulative subqueries
LEFT JOIN ( SELECT bank_id, COALESCE(SUM(total_paid_track),0) AS total_collection FROM collection  WHERE 1 AND DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59' AND bank_id IN ($bank_detail)  GROUP BY bank_id ) total_coll      ON total_coll.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, COALESCE(SUM(COALESCE(cheque_value, 0) + COALESCE(transaction_value, 0)),0) AS total_loan_issued FROM loan_issue WHERE 1 AND DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59'AND bank_id IN ($bank_detail) GROUP BY bank_id ) total_loan  ON total_loan.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, COALESCE(SUM(amt),0) AS ag_db_amt_upto_date FROM ct_db_bag WHERE 1 AND DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59'AND bank_id IN ($bank_detail)  GROUP BY bank_id ) total_agdb ON total_agdb.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, COALESCE(SUM(amt),0) AS ag_cr_amt_upto_date FROM ct_cr_bag WHERE 1 AND DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59'AND bank_id IN ($bank_detail) GROUP BY bank_id ) total_agcr ON total_agcr.bank_id = bn.id

LEFT JOIN ( SELECT bank_id, COALESCE(SUM(amt),0) AS bexpense_amt_upto_date FROM ct_db_bexpense WHERE 1  AND DATE(created_date) BETWEEN '$till_now_date 00:00:00 ' AND '$closing_date 23:59:59'AND bank_id IN ($bank_detail) GROUP BY bank_id ) total_bexp  ON total_bexp.bank_id = bn.id

WHERE bn.id IN ($bank_detail)
ORDER BY bn.id;

");

    $banks = [];
    while ($bank = $bankQry->fetch(PDO::FETCH_ASSOC)) {
        $banks[] = $bank; // Append each row directly
    }

    return [
        'hand_summary' => $handCollection,
        'banks' => $banks
    ];
}

// Close the database connection
$connect = null;
