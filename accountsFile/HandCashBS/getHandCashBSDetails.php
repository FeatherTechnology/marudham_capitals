<?php
include "../../ajaxconfig.php";

$type = $_POST['type'];
$userid = $_POST['user_id'] ?? ''; //for user based

if ($type == 'today') {
    $from_date = date('Y-m-d');
    $to_date = date('Y-m-d', strtotime($from_date. '+1 day'));
    
} else if ($type == 'day') {
    $from_date = $_POST['from_date'];
    $to_date = date('Y-m-d', strtotime($_POST['to_date']. '+1 day'));
}

$response = getCollectionRecord($connect, $from_date, $to_date, $userid);

$response = array_map(function ($num) {
    return number_format(intVal($num), 0, '', ',');
}, $response);

echo json_encode($response);

function getCollectionRecord($connect, $from_date, $to_date, $userid)
{
    $response = array();
    $user_id = ($userid !='') ? " AND insert_login_id = '$userid'" : '';

    //to date is added +1 day becuase not to use date function in query. if date() function in query it affect index. 
    $accWhereCndtn = "(created_date >= '$from_date' AND created_date < '$to_date') $user_id";
    $accOPCndtn = "(created_date < '$from_date') $user_id";
    $accCLCndtn = "(created_date < '$to_date') $user_id";

    //Opening Balance
    $response['hand_opening'] = getopclbal($connect, $accOPCndtn);

    //Collection
    $qry = $connect->query("SELECT SUM(rec_amt) as rec_amt FROM ct_hand_collection WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $due_collection = $row['rec_amt'] ?? 0;
        $response['due_collection'] = (float)$due_collection;

    //Waiver
    $qry = $connect->query("SELECT SUM(rec_amt) as rec_amt FROM ct_hand_waiver WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $pre_close_waiver = $row['rec_amt'] ?? 0;
        $response['pre_close_waiver'] = $pre_close_waiver;

    // other income
    $qry = $connect->query("SELECT SUM(amt) as other_income FROM ct_cr_hoti WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $other_income = $row['other_income'] ?? 0;
        $response['other_income'] = (float)$other_income;

    // Investment Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_cr_hinvest WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $investment = $row['amt'] ?? 0;
        $response['cr_investment'] = (float)$investment;

    // Investment Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_hinvest WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $investment = $row['amt'] ?? 0;
        $response['db_investment'] = (float)$investment;

    // Deposit Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_cr_hdeposit WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $deposit = $row['amt'] ?? 0;
        $response['cr_deposit'] = (float)$deposit;

    // Deposit Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_hdeposit WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $deposit = $row['amt'] ?? 0;
        $response['db_deposit'] = (float)$deposit;

    // Exchange Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_cr_hexchange WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $exchange = $row['amt'] ?? 0;
        $response['cr_exchange'] = (float)$exchange;

    // Exchange Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_hexchange WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $exchange = $row['amt'] ?? 0;
        $response['db_exchange'] = (float)$exchange;

    // EL Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_cr_hel WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $el = $row['amt'] ?? 0;
        $response['cr_el'] = (float)$el;

    // EL Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_hel WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $el = $row['amt'] ?? 0;
        $response['db_el'] = (float)$el;

    // Bank Withdrawal
    $qry = $connect->query("SELECT SUM(amt) as bank_withdrawal FROM ct_cr_bank_withdraw WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $bank_withdrawal = $row['bank_withdrawal'] ?? 0;
        $response['credit_contra'] = (float)$bank_withdrawal;

    // Bank Deposit
    $qry = $connect->query("SELECT SUM(amount) as amt FROM ct_db_bank_deposit WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $bank_deposit = $row['amt'] ?? 0;
        $response['debit_contra'] = (float)$bank_deposit;

    // Issued
    $qry = $connect->query("SELECT SUM(netcash) AS amt FROM ct_db_hissued WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $issued = $row['amt'] ?? 0;
        $response['issued'] = (float)$issued;

    // Agent Credit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_cr_hag WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $agent = $row['amt'] ?? 0;
        $response['cr_agent'] = (float)$agent;

    // Agent Debit
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_hag WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $agent = $row['amt'] ?? 0;
        $response['db_agent'] = (float)$agent;

    // Expense
    $qry = $connect->query("SELECT SUM(amt) as amt FROM ct_db_hexpense WHERE $accWhereCndtn");

        $row = $qry->fetch();
        $expense = $row['amt'] ?? 0;
        $response['expense'] = (float)$expense;

    //Closing Balance
    $response['hand_closing'] = getopclbal($connect, $accCLCndtn);
    
    return $response;
}

function getopclbal($connect, $accOPCLBalCndtn){
    $handCreditQry = $connect->query("SELECT
        SUM(amt) AS hand_credits
        FROM (
            (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE $accOPCLBalCndtn)
        ) AS Hand_Credit_Opening");

    $handCredit = $handCreditQry->fetch()['hand_credits'];

    $handDebitQry = $connect->query("SELECT
        SUM(amt) AS hand_debits
        FROM (
            (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE $accOPCLBalCndtn)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE $accOPCLBalCndtn)
        ) AS Hand_Debit_Opening");

    $handDebit = $handDebitQry->fetch()['hand_debits'];

    $qry = $connect->query("SELECT ag_id FROM agent_creation WHERE 1");
        $agent_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_ids = implode(',', $agent_ids);

    //credit hand cash agent
    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE $accOPCLBalCndtn AND FIND_IN_SET(ag_id, '$ag_ids'))
            
        ) AS Agent_Credit_Opening");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    //Debit hand cash agent
    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE $accOPCLBalCndtn AND FIND_IN_SET(ag_id,'$ag_ids'))
            
        ) AS Agent_Debit_Opening");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    //hand cash dr - cr.
    $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);


    return (intVal($handCredit) - intVal($handDebit)) - $agent_hand_op;
}
// Close the database connection
$connect = null;