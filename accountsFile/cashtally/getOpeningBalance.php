<?php
session_start();
$user_id = $_SESSION['userid'];

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

    $old_hand = 0;
    $old_agent = 0;
    $old_bank = array();
    $old_bank_unt = array();

    $records = getOpeningBalance($connect, $op_date, $bank_detail, $user_id);

    //if while loop gets true, then the function will load the old opening balance.. so store latest opening balance
    $opening_balance = $records[0]['opening_balance'];
 $old_hand = intVal($records[0]['hand_opening']);
  $old_agent = intVal($records[0]['agent_opening']);
    // inside the while loop
    $old_bank_unt = array_fill(0, count(explode(',', $records[0]['bank_untrkd'])), 0); // init array


    // while ($records[0]['hand_opening'] != 0 || $records[0]['agent_opening'] != 0  || $records[0]['bank_opening'] != 0) {
    //     $old_hand += intVal($records[0]['hand_opening']);
    //     $old_agent += intVal($records[0]['agent_opening']);

    //     //If Multiple bank means need to extra loop for bank because other values are 0 index, so we need to loop it for proper returns;
    //     foreach ($records as $key => $value) {
    //         $old_bank_value = isset($old_bank[$key]) ? $old_bank[$key] : 0;
    //         $old_bank[$key] = $value['bank_opening'] + $old_bank_value;
    //     }
        
    //     //the untrack amount store like 100,0 in cash tally. it based on bank mapped so need to added particular bank and return the set.
    //     // $old_bank_unt += intVal($records[0]['bank_untrkd']);
    //      $old_bank_unt = addBankUntrkd(implode(',', $old_bank_unt), $records[0]['bank_untrkd']);


    //     $op_date = date('Y-m-d', strtotime($op_date . '-1 day'));
    //     $records = getOpeningBalance($connect, $op_date, $bank_detail, $user_id);
    // }

    //now reassign latest opening date to returing variable.
    $records[0]['opening_balance'] = $opening_balance;
    $records[0]['hand_opening'] =   intVal($old_hand);
    $records[0]['agent_opening'] =  intVal($old_agent);

    foreach ($records as $key => $value) {
        $old_bank_value = isset($old_bank[$key]) ? $old_bank[$key] : 0;
        $records[$key]['bank_opening'] = $value['bank_opening'] + $old_bank_value;
    }

    // $records[0]['bank_untrkd'] = intVal($records[0]['bank_untrkd']) + intVal($old_bank_unt);
    $records[0]['bank_untrkd'] = implode(',', $old_bank_unt);
}
echo json_encode($records);


function getOpeningBalance($connect, $op_date, $bank_detail, $user_id)
{


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $handCreditQry = $connect->query("SELECT
        SUM(amt) AS hand_credits
        FROM (
            (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) <='$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) <='$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
        ) AS Hand_Credit_Opening
    ");

    $handCredit = $handCreditQry->fetch()['hand_credits'];
    $handDebitQry = $connect->query("SELECT
        SUM(amt) AS hand_debits
        FROM (
            (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) <='$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) <= '$op_date' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
        ) AS Hand_Debit_Opening
    ");

    $handDebit = $handDebitQry->fetch()['hand_debits'];

    $records[0]['hand_opening'] = intVal($handCredit) - intVal($handDebit);

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $bank_details_arr = explode(',', $bank_detail);
    $i = 0;
    $bank_opening_all = 0;
    foreach ($bank_details_arr as $val) {
        $bankCreditQry = $connect->query("SELECT
                SUM(amt) AS bank_credit
                FROM (
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_cash_deposit WHERE date(created_date) <= '$op_date' and to_bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(credited_amt), 0) AS amt FROM ct_bank_collection WHERE date(created_date) <='$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bdeposit WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bel WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bexchange WHERE date(created_date) <= '$op_date' and to_bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_binvest WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_boti WHERE date(created_date) <= '$op_date' and to_bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) <= '$op_date' AND bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                ) AS Bank_Credit_Opening
            ");

        $bankCredit = $bankCreditQry->fetch()['bank_credit'];

        $bankDebitQry = $connect->query("SELECT
                SUM(amt) AS bank_debit
                FROM (
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_cash_withdraw WHERE date(created_date) <= '$op_date' and from_bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bdeposit WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bel WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    -- (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_exf WHERE date(created_date) = '$op_date' and to_bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    -- UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexchange WHERE date(created_date) <= '$op_date' and from_acc_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexpense WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_binvest WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_bissued WHERE date(created_date) <= '$op_date' and li_bank_id = '$val' and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
                     UNION ALL 
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) <= '$op_date' and bank_id = '$val' and insert_login_id = '$user_id'  ORDER BY created_date DESC LIMIT 1)
                ) AS Bank_debit_Opening
            ");

        $bankDebit = $bankDebitQry->fetch()['bank_debit'];

        $records[$i]['bank_opening'] = intVal($bankCredit) - intVal($bankDebit);
        $bank_opening_all = $bank_opening_all + $records[$i]['bank_opening'];
        $i++;
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $qry = $connect->query("SELECT `user_id` from `user` where ag_id IN (SELECT ag.ag_id FROM agent_creation ag JOIN `user` us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id = '$user_id')  ");
    //without while it will not give all the agent ids
    $ag_ids = [];
    while ($rww = $qry->fetch()) {
        $ag_ids[] = $rww["user_id"];
    }
    $ag_ids = implode(',', $ag_ids);


    $agentCollQry = $connect->query("SELECT
        SUM(amt) AS agent_coll
        FROM (
            (SELECT COALESCE(SUM(total_paid_track), 0) AS amt FROM collection
            WHERE date(created_date) <= '$op_date' AND FIND_IN_SET(insert_login_id,'$ag_ids') ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Collection_Credit_Opening
    ");

    $agentCollCredit = $agentCollQry->fetch()['agent_coll'];

    //only for collections we need user ids of agents
    $qry = $connect->query("SELECT ag.ag_id FROM agent_creation ag JOIN user us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id = '$user_id'");
    $ag_ids = [];
    while ($rww = $qry->fetch()) {
        $ag_ids[] = $rww["ag_id"];
    }
    $ag_ids = implode(',', $ag_ids);


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
        AND FIND_IN_SET(agent_id,'$ag_ids')   AND agent_id IS NOT NULL AND insert_login_id = '$user_id'
      
) AS Agent_Issue_Debit_Opening
    ");

    $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

    $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);

    //

    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE date(created_date) <= '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Credit_Opening
    ");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE date(created_date) <= '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Debit_Opening
    ");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

    //

    $agentCreditQry = $connect->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) <= '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Credit_Opening
    ");

    $agentCredit = $agentCreditQry->fetch()['agent_credit'];

    $agentDebitQry = $connect->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) <= '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') and insert_login_id = '$user_id' ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Debit_Opening
    ");

    $agentDebit = $agentDebitQry->fetch()['agent_debit'];

    $agent_bank_op = intVal($agentDebit) - intVal($agentCredit);



    $records[0]['agent_opening'] = $agent_hand_op + $agent_bank_op + $agent_CL_op;

    $records[0]['hand_opening'] = $records[0]['hand_opening'] - $agent_hand_op; //this will subract the hand debited amount for the agent with hand closing cash

    $records[0]['opening_balance'] = $records[0]['hand_opening'] + $bank_opening_all + $records[0]['agent_opening'];


    $qry = $connect->query("SELECT bank_untrkd from cash_tally where date(created_date) = '$op_date' and insert_login_id = '$user_id' ");
    if ($qry->rowCount() > 0) {
        $records[0]['bank_untrkd'] = $qry->fetch()['bank_untrkd'];
    } else {
        $records[0]['bank_untrkd'] = '0,0';
    }

    return $records;
}

function addBankUntrkd($existing, $new) {
    $existing_array = explode(',', $existing);
    $new_array = explode(',', $new);
    $result = [];

    $max = max(count($existing_array), count($new_array));
    for ($i = 0; $i < $max; $i++) {
        $existing_val = isset($existing_array[$i]) ? intval($existing_array[$i]) : 0;
        $new_val = isset($new_array[$i]) ? intval($new_array[$i]) : 0;
        $result[] = $existing_val + $new_val;
    }

    return $result;
}

// Close the database connection
$connect = null;