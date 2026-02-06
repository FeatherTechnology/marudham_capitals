<?php
session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

if (isset($_POST['search_date']) && $_POST['search_date'] != '') {
    $search_date   = $_POST['search_date'];
    $login_id   = $_POST['insert_login_id'];
    $date          = new DateTime($search_date);
    $full_date     = $date->format('Y-m-d');
    $till_now_date = $date->format('Y-m-01');
}

/* ================= OPENING BALANCE ================= */
// $record = getOpeningBalance($connect, $full_date,$login_id);
// $hand_opening_balance = $record['hand_opening'];

/* ================= CLOSING / DAY SUMMARY ================= */
$records = getClosingBalance($connect, $full_date, $till_now_date,$login_id);
$hand = $records['hand_summary'];

$userWiseCollection = getUserWiseCollection($connect, $full_date, $login_id);
$userWiseIssued    = getUserWiseIssued($connect, $full_date, $login_id);


/* ================= HAND VARIABLES ================= */
$h_collection      = $hand['ct_hand_collection'];
$h_issued          = $hand['ct_hand_issued'];
$h_hand_expense    = $hand['hand_expense'];
$h_agent           = $hand['ct_cr_agent'] - $hand['ct_db_agent'];
$h_deposite        = $hand['hand_cr_deposit'] - $hand['hand_db_deposite'];
$h_exchange        = $hand['hand_cr_exchange'] - $hand['hand_db_exchange'];
$h_el              = $hand['hand_cr_el'] - $hand['hand_db_el'];
$h_invest          = $hand['hand_cr_hinvest'] - $hand['hand_db_hinvest'];
$h_contra          = $hand['hand_cr_bank_withdraw'] - $hand['hand_db_bank_deposit'];
$hand_other_income = $hand['hand_other_income'];

$h_till_now_collection   = $hand['till_now_hand_collection'];
$h_till_now_loan_issued  = $hand['till_now_hand_loan_issued'];
$h_till_now_agent        = $hand['till_now_hand_agent_cr_issued'] - $hand['till_now_hand_agent_db_issued'];
$h_till_now_hand_expense = $hand['till_now_hand_hexpense'];
?>

<table class="table custom-table">
    <thead>
        <tr>
            <th width="180"></th>
            <th>User Name</th>
            <th>Hand Cash</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td><b>Collection</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_collection); ?></td>
        </tr>
        <?php foreach ($userWiseCollection as $row) { ?>
        <tr style="background:#fafafa;">
            <!-- Label column -->

            <td style="padding-left:35px;" colspan="2"><?= $row['fullname']; ?></td>

            <!-- Hand Cash -->
            <td><?= moneyFormatIndia($row['amount']); ?></td>
        </tr>
        <?php } ?>
                <tr>
            <td><b>Loan Issue</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_issued); ?></td>
        </tr>
        <?php foreach ($userWiseIssued as $row) { ?>
        <tr style="background:#fafafa;">
            <td style="padding-left:30px;" colspan="2"><?= $row['fullname']; ?></td>
            <td><?= moneyFormatIndia($row['amount']); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td><b>Expenses</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_hand_expense); ?></td>
        </tr>
        <tr>
            <td><b>Agent</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_agent); ?></td>
        </tr>
        <tr>
            <td><b>Exchange</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_exchange); ?></td>
        </tr>
        <tr>
            <td><b>Other Income</b></td>
            <td></td>
            <td><?= moneyFormatIndia($hand_other_income); ?></td>
        </tr>
        <tr>
            <td><b>EL</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_el); ?></td>
        </tr>
        <tr>
            <td><b>Investment</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_invest); ?></td>
        </tr>
        <tr>
            <td><b>Deposit</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_deposite); ?></td>
        </tr>

        <tr>
            <td><b>Contra</b></td>
            <td></td>
            <td><?= moneyFormatIndia($h_contra); ?></td>
        </tr>

    </tbody>

    <tfoot>
        <tr style="font-weight:bold; background:#f0f0f0;">
            <td>Closing Balance</td>
            <td></td>
            <td>
                <?php
                $hand_total =
                    $h_collection +
                    $h_deposite +
                    $h_exchange +
                    $h_el +
                    $h_invest +
                    $hand_other_income +
                    $h_contra -
                    $h_issued -
                    (-$h_agent) -
                    $h_hand_expense;

                echo moneyFormatIndia($hand_total);
                ?>
            </td>
            
        </tr>
    </tfoot>
</table>

<?php
/* ================= FUNCTIONS ================= */

function getOpeningBalance($connect, $op_date ,$login_id)
{
    $handCredit = $connect->query("
        SELECT SUM(amt) FROM (
            SELECT COALESCE(SUM(rec_amt),0) amt FROM ct_hand_collection WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_cr_bank_withdraw WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_cr_hoti WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id' 
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_cr_hinvest WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id' 
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_cr_hexchange WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_cr_hel WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_cr_hdeposit WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
        ) t
    ")->fetchColumn();

    $handDebit = $connect->query("
        SELECT SUM(amt) FROM (
            SELECT COALESCE(SUM(amount),0) amt FROM ct_db_bank_deposit WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_db_hinvest WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(netcash),0) FROM ct_db_hissued WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_db_hel WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_db_hexchange WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_db_hexpense WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
            UNION ALL SELECT COALESCE(SUM(amt),0) FROM ct_db_hdeposit WHERE DATE(created_date) < '$op_date' AND insert_login_id='$login_id'
        ) t
    ")->fetchColumn();

    return [
        'hand_opening' => (float)$handCredit - (float)$handDebit
    ];
}

function getClosingBalance($connect, $closing_date, $till_now_date,$login_id)
{
    $qry = $connect->query("
        SELECT
        (SELECT COALESCE(SUM(rec_amt),0) FROM ct_hand_collection WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') ct_hand_collection,
        (SELECT COALESCE(SUM(netcash),0) FROM ct_db_hissued WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') ct_hand_issued,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hag WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') ct_cr_agent,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hag WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') ct_db_agent,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hexpense WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_expense,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hdeposit WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_cr_deposit,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hdeposit WHERE DATE(created_date)='$closing_date'  AND insert_login_id='$login_id') hand_db_deposite,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hexchange WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_cr_exchange,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hexchange WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_db_exchange,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hel WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_cr_el,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hel WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_db_el,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hinvest WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_cr_hinvest,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hinvest WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_db_hinvest,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hoti WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id')  hand_other_income,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_bank_withdraw WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_cr_bank_withdraw,
        (SELECT COALESCE(SUM(amount),0) FROM ct_db_bank_deposit WHERE DATE(created_date)='$closing_date' AND insert_login_id='$login_id') hand_db_bank_deposit,

        (SELECT COALESCE(SUM(rec_amt),0) FROM ct_hand_collection WHERE DATE(created_date) BETWEEN '$till_now_date' AND '$closing_date' AND insert_login_id='$login_id') till_now_hand_collection,
        (SELECT COALESCE(SUM(netcash),0) FROM ct_db_hissued WHERE DATE(created_date) BETWEEN '$till_now_date' AND '$closing_date' AND insert_login_id='$login_id') till_now_hand_loan_issued,
        (SELECT COALESCE(SUM(amt),0) FROM ct_cr_hag WHERE DATE(created_date) BETWEEN '$till_now_date' AND '$closing_date' AND insert_login_id='$login_id') till_now_hand_agent_cr_issued,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hag WHERE DATE(created_date) BETWEEN '$till_now_date' AND '$closing_date' AND insert_login_id='$login_id') till_now_hand_agent_db_issued,
        (SELECT COALESCE(SUM(amt),0) FROM ct_db_hexpense WHERE DATE(created_date) BETWEEN '$till_now_date' AND '$closing_date' AND insert_login_id='$login_id') till_now_hand_hexpense
    ");

    return ['hand_summary' => $qry->fetch(PDO::FETCH_ASSOC)];
}
function getUserWiseCollection($connect, $date, $login_id)
{
    $qry = $connect->query("
        SELECT u.fullname, SUM(cthc.rec_amt) AS amount
        FROM ct_hand_collection  cthc
        join user u on u.user_id = cthc.user_id
        WHERE DATE(cthc.created_date) = '$date'
        AND cthc.insert_login_id = '$login_id'
        GROUP BY cthc.user_id
    ");

    return $qry->fetchAll(PDO::FETCH_ASSOC);
}
function getUserWiseIssued($connect, $date, $login_id)
{
    $qry = $connect->query("
        SELECT  SUM(cthi.netcash) AS amount ,u.fullname 
        FROM ct_db_hissued cthi
         join user u on u.user_id =cthi.li_user_id
        WHERE DATE(cthi.created_date) = '$date'
        AND cthi.insert_login_id = '$login_id'
        GROUP BY cthi.li_user_id
    ");

    return $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null;
