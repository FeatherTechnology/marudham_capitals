<?php
session_start();
$user_id = $_SESSION['userid'];

if ($user_id != '') {
    $user_where = " AND insert_login_id = '$user_id' ";
} else {
    $user_where = "";
}

$records = array();

$bank_detail = $_POST['bank_detail'];

include('../../ajaxconfig.php');

$op_date = date('Y-m-d', strtotime($_POST['op_date'] . '-1 day'));
if ($op_date == date('Y-m-d')) { // check whether opening date is current date

    $records[0]['hand_opening'] = 0;
    $records[0]['bank_opening'] = 0;
    $records[0]['agent_opening'] = 0;
    $records[0]['bank_untrkd'] = 0;
    $records[0]['opening_balance'] = 0;

} else { // only if opening date is less than today's date, increase one date

    $records = getOpeningBalance($connect, $op_date, $bank_detail, $user_where);

    foreach ($records as $key => $value) {
        $records[$key]['bank_opening'] = $value['bank_opening'];
    }
}

echo json_encode($records);

function getOpeningBalance($connect, $op_date, $bank_detail, $user_where)
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $handCreditQry = $connect->query("SELECT
        SUM(amt) AS hand_credits
        FROM (
            (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) <='$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) <='$op_date' $user_where )
        ) AS Hand_Credit_Opening
    ");

    $handCredit = $handCreditQry->fetch()['hand_credits'];

    $handDebitQry = $connect->query("SELECT
        SUM(amt) AS hand_debits
        FROM (
            (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) <='$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) <= '$op_date' $user_where )
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) <= '$op_date' $user_where )
        ) AS Hand_Debit_Opening
    ");

    $handDebit = $handDebitQry->fetch()['hand_debits'];

    $records[0]['hand_opening'] = intVal($handCredit) - intVal($handDebit);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $bank_details_arr = explode(',', $bank_detail);
    $i = 0;
    $bank_opening_all = 0;
    foreach ($bank_details_arr as $val) {
        $bankQry = $connect->query("SELECT balance FROM bank_stmt WHERE bank_id = '$val' AND DATE(trans_date) <= '$op_date' ORDER BY trans_date DESC,id DESC LIMIT 1 ;");
        // $bankCreditQry = $connect->query("SELECT
        //         SUM(amt) AS bank_credit
        //         FROM (
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_cash_deposit WHERE date(created_date) <= '$op_date' AND to_bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(credited_amt), 0) AS amt FROM ct_bank_collection WHERE date(created_date) <='$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bdeposit WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bel WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bexchange WHERE date(created_date) <= '$op_date' AND to_bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_binvest WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_boti WHERE date(created_date) <= '$op_date' AND to_bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //         ) AS Bank_Credit_Opening
        //     ");

        // $bankCredit = $bankCreditQry->fetch()['bank_credit'];

        // $bankDebitQry = $connect->query("SELECT
        //         SUM(amt) AS bank_debit
        //         FROM (
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_cash_withdraw WHERE date(created_date) <= '$op_date' AND from_bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bdeposit WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bel WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexchange WHERE date(created_date) <= '$op_date' AND from_acc_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexpense WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_binvest WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //             UNION ALL
        //             (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_bissued WHERE date(created_date) <= '$op_date' AND li_bank_id = '$val' $user_where )
        //              UNION ALL 
        //             (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) <= '$op_date' AND bank_id = '$val' $user_where )
        //         ) AS Bank_debit_Opening
        //     ");

        // $bankDebit = $bankDebitQry->fetch()['bank_debit'];

        // $records[$i]['bank_opening'] = intVal($bankCredit) - intVal($bankDebit);
        $row = $bankQry->fetch(PDO::FETCH_ASSOC);

        $opening_balance = ($row && isset($row['balance'])) ? (float)$row['balance'] : 0;
        $records[$i]['bank_opening'] = $opening_balance;
        $bank_opening_all += $opening_balance;

        $i++;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
    //only for collections we need user ids of agents
    // $qry = $connect->query("SELECT `agentforstaff` AS ag_id FROM user WHERE `user_id` = '$user_id'");
    $qry = $connect->query("SELECT ag_id FROM agent_creation WHERE 1");
    $agent_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
    $ag_ids = implode(',', $agent_ids);
    
    //get agent user id to get data for collection.
    $qry = $connect->query("SELECT `user_id` FROM user WHERE FIND_IN_SET( `ag_id`, '$ag_ids')");
    $ag_user_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
    $ag_user_id = implode(',', $ag_user_ids);
    $ag_where = " AND FIND_IN_SET(cl.insert_login_id, '$ag_user_id') ";

    
    $agentCollQry = $connect->query("SELECT
        SUM(amt) AS agent_coll
        FROM (
            SELECT COALESCE(SUM(total_paid_track), 0) AS amt
            FROM collection cl
            WHERE DATE(created_date) <= '$op_date'
            $ag_where  

        ) AS Agent_Collection_Credit_Opening
    ");

    $agentCollCredit = $agentCollQry->fetch()['agent_coll'];

    $agentIssueQry = $connect->query("SELECT 
        COALESCE(SUM(amt), 0) AS agent_issue 
        FROM (
            SELECT 
                COALESCE(SUM(
                    COALESCE(cash, 0) + 
                    COALESCE(cheque_value, 0) + 
                    COALESCE(transaction_value, 0)
                ), 0) AS amt 
            FROM loan_issue 
            WHERE 
                DATE(created_date) <= '$op_date'
                AND FIND_IN_SET(agent_id,'$ag_ids') AND agent_id IS NOT NULL 
            
        ) AS Agent_Issue_Debit_Opening
    ");

    $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

    //Collection - Loan Issue ,for Agent.
    $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);

    //credit hand cash agent
    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE DATE(created_date) <= '$op_date' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
            
        ) AS Agent_Credit_Opening
    ");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    //Debit hand cash agent
    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE DATE(created_date) <= '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
            
        ) AS Agent_Debit_Opening
    ");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    //hand cash dr - cr.
    $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

    //credit bank cash agent
    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE DATE(created_date) <= '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
            
        ) AS Agent_Credit_Opening
    ");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    //Debit bank cash agent
    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE DATE(created_date) <= '$op_date' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
            
        ) AS Agent_Debit_Opening
    ");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    //bank cash dr - cr
    $agent_bank_op = intVal($agentDebit) - intVal($agentCredit);


    $records[0]['agent_opening'] = $agent_hand_op + $agent_bank_op + $agent_CL_op;

    $records[0]['hand_opening'] = $records[0]['hand_opening'] - $agent_hand_op; //this will subract the hand debited amount for the agent with hand closing cash

   
    $opening_total = $records[0]['hand_opening'] + $bank_opening_all;

    if (floor($opening_total) == $opening_total) {
        // No decimal part
        $records[0]['opening_balance'] = number_format($opening_total, 0, '.', '');
    } else {
        // Has decimal part
        $records[0]['opening_balance'] = number_format($opening_total, 2, '.', '');
    }
    // $qry = $connect->query("SELECT bank_untrkd FROM cash_tally WHERE date(cl_date) = '$op_date' $user_where  ");
    // if ($qry->rowCount() > 0) {
    //     $records[0]['bank_untrkd'] = $qry->fetch()['bank_untrkd'];
    // } else {
    //     $records[0]['bank_untrkd'] = '0,0';
    // }

    return $records;
}

// Close the database connection
$connect = null;