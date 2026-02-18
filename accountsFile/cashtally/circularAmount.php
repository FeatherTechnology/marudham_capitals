<?php
session_start();
$user_id = $_SESSION['userid'];
include('../../ajaxconfig.php');
include('../../moneyFormatIndia.php');

$branch_id = isset($_POST['branch_id']) ? $_POST['branch_id'] : '';
$op_date   = isset($_POST['op_date']) ? date('Y-m-d', strtotime($_POST['op_date'])) : date('Y-m-d');

$next_date = date('Y-m-d', strtotime($op_date . ' +1 day'));

/* =====================================================  TOTAL COLLECTION BALANCE ===================================================== */

$qry = $connect->query("
WITH user_coll AS (
    SELECT
        c.insert_login_id AS user_id,
        SUM(CASE WHEN c.coll_date < '$op_date' 
                 THEN c.total_paid_track ELSE 0 END) AS coll_amt_ys,

        SUM(CASE WHEN c.coll_date >= '$op_date' 
                  AND c.coll_date < '$next_date'
                 THEN c.total_paid_track ELSE 0 END) AS coll_amt_today
    FROM collection c
    WHERE c.coll_mode = '1'
      AND c.branch IN ($branch_id)
      AND c.coll_date < '$next_date'
    GROUP BY c.insert_login_id
),

user_hand AS (
    SELECT
        hc.user_id,
        SUM(CASE WHEN hc.created_date < '$op_date' 
                 THEN hc.rec_amt ELSE 0 END) AS rec_amt_ys,

        SUM(CASE WHEN hc.created_date >= '$op_date' 
                  AND hc.created_date < '$next_date'
                 THEN hc.rec_amt ELSE 0 END) AS rec_amt_today
    FROM ct_hand_collection hc
    WHERE hc.branch_id IN ($branch_id)
      AND hc.created_date < '$next_date'
    GROUP BY hc.user_id
)

SELECT 
    SUM(
        (IFNULL(uc.coll_amt_ys,0) - IFNULL(uh.rec_amt_ys,0)) +
        (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0))
    ) AS total_balance

FROM user u
LEFT JOIN user_coll uc ON uc.user_id = u.user_id
LEFT JOIN user_hand uh ON uh.user_id = u.user_id
WHERE u.user_id <> 1
AND (
    (IFNULL(uc.coll_amt_ys,0) - IFNULL(uh.rec_amt_ys,0)) > 0
 OR (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0)) > 0
 OR (uh.rec_amt_today > 0)
)
");

$row = $qry->fetch(PDO::FETCH_ASSOC);
$total_balance = $row['total_balance'] ?? 0;

/* =====================================================  TOTAL ISSUED ===================================================== */

$sumQry = $connect->query(" SELECT SUM(user_balance) AS total_issued
    FROM (
        SELECT t.insert_login_id, t.total_issued - 
            COALESCE((
                SELECT SUM(hi.netcash)
                FROM ct_db_hissued hi
                WHERE hi.li_user_id = t.insert_login_id
                AND DATE(hi.created_date) >= t.first_created_date ),0) AS user_balance
        FROM (
            SELECT li.insert_login_id, SUM(li.cash) AS total_issued,
                DATE(MIN(li.created_date)) AS first_created_date
            FROM loan_issue li
            WHERE (li.agent_id = '' OR li.agent_id IS NULL)
            AND ( (li.issued_mode = 1 AND li.payment_type = '0')
                OR (li.issued_mode = 0 AND li.cash IS NOT NULL) )
            AND li.created_date 
                BETWEEN '2026-01-01 00:00:01'
                AND '{$op_date} 23:59:59'
            GROUP BY li.insert_login_id
        ) t
    ) final_table
    WHERE user_balance > 0; ");


$row = $sumQry->fetch();
$totalIssued = $row['total_issued'] ?? 0;

/* ===================================================== TOTAL EXCHANGE ===================================================== */

$exchangeQry = $connect->query("
    SELECT SUM(amt) AS total_exchange
    FROM ct_db_hexchange
    WHERE to_user_id = '$user_id'
      AND received = 1
");

$exchangeRow = $exchangeQry->fetch(PDO::FETCH_ASSOC);
$total_exchange = $exchangeRow['total_exchange'] ?? 0;

/* ===================================================== Contra ===================================================== */
$withdrawQry = $connect->query("
    SELECT SUM(amt) as total_withdraw
    FROM ct_db_cash_withdraw
    WHERE received = 1
");

$withdrawRow = $withdrawQry->fetch(PDO::FETCH_ASSOC);
$total_withdraw = $withdrawRow['total_withdraw'] ?? 0;

$total_credit =  $total_balance + $total_exchange + $total_withdraw ;
$total_debit = $totalIssued ;
$grand_total = $total_credit - $total_debit;


$connect = null;
?>

<table class="table custom-table">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Type</th>
            <th>Credit</th>
            <th>Debit</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Collection</td>
            <td><?= moneyFormatIndia($total_balance); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Issued</td>
            <td></td>
            <td><?= moneyFormatIndia($totalIssued); ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Exchange</td>
            <td><?= moneyFormatIndia($total_exchange); ?></td>
            <td></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Contra</td>
            <td><?= moneyFormatIndia($total_withdraw); ?></td>
            <td></td>
        </tr>
    </tbody>
    <tfoot>
    <tr style="font-weight:bold; background:#f1f1f1;">
        <td colspan="2" style="text-align:right;">Total</td>
        <td><?= moneyFormatIndia($total_credit); ?></td>
        <td><?= moneyFormatIndia($total_debit); ?></td>
    </tr>
    <tr style="font-weight:bold; background:#e2e2e2;">
        <td colspan="2" style="text-align:right;">Balance</td>
        <td colspan="2"><?= moneyFormatIndia($grand_total); ?></td>
    </tr>
</tfoot>
</table>
