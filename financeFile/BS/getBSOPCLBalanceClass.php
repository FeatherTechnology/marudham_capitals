<?php
class ClosingBalanceClass
{
    private $db;
    public function __construct($connect)
    {
        $this->db = $connect;
    }
    public function getUncleared($op_date, $closing_date)
    {
        // ---------- PREVIOUS (Before Opening Date) ----------
        $prevStmtQry = $this->db->query("
            SELECT 
                COALESCE(SUM(credit),0) AS stmt_credit,
                COALESCE(SUM(debit),0)  AS stmt_debit
            FROM bank_stmt  
            WHERE DATE(trans_date) < '$op_date'
        ");
        $prevStmt = $prevStmtQry->fetch(PDO::FETCH_ASSOC);

        $prevClearQry = $this->db->query("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 1 THEN transaction_amount ELSE 0 END),0) AS clear_credit,
                COALESCE(SUM(CASE WHEN type = 2 THEN transaction_amount ELSE 0 END),0) AS clear_debit
            FROM cleared_bank_stmt_history
            WHERE DATE(created_date) < '$op_date'
        ");

        $prevClear = $prevClearQry->fetch(PDO::FETCH_ASSOC);

        $previous_uncleared_credit = round($prevStmt['stmt_credit'] - $prevClear['clear_credit'], 2);
        $previous_uncleared_debit  = round($prevStmt['stmt_debit']  - $prevClear['clear_debit'], 2);

        // ---------- CURRENT (Between Opening & Closing Date) ----------
        $currStmtQry = $this->db->query("
            SELECT 
                COALESCE(SUM(credit),0) AS stmt_credit,
                COALESCE(SUM(debit),0)  AS stmt_debit
            FROM bank_stmt  
            WHERE DATE(trans_date) <='$closing_date'
        ");
        $currStmt = $currStmtQry->fetch(PDO::FETCH_ASSOC);

        $currClearQry = $this->db->query("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 1 THEN transaction_amount ELSE 0 END),0) AS clear_credit,
                COALESCE(SUM(CASE WHEN type = 2 THEN transaction_amount ELSE 0 END),0) AS clear_debit
            FROM cleared_bank_stmt_history
            WHERE DATE(created_date) <= '$closing_date'
        ");

        $currClear = $currClearQry->fetch(PDO::FETCH_ASSOC);
        
        $current_uncleared_credit = round($currStmt['stmt_credit'] - $currClear['clear_credit'], 2);
        $current_uncleared_debit  = round($currStmt['stmt_debit']  - $currClear['clear_debit'], 2);

        return [
            'previous_uncleared_credit' => $previous_uncleared_credit ?? 0,
            'previous_uncleared_debit'  => $previous_uncleared_debit ?? 0,
            'current_uncleared_credit'  => $current_uncleared_credit ?? 0,
            'current_uncleared_debit'   => $current_uncleared_debit ?? 0
        ];
    }

    public function getClosingBalance($closing_date, $bank_detail, $user_id)
    {
        $user_where = "";
        if ($user_id != '') {
            $user_where = "AND insert_login_id = '$user_id' ";
        }

        $handCreditQry = $this->db->query("SELECT
            SUM(amt) AS hand_credits
            FROM (
                (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_waiver WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) <= '$closing_date' $user_where)
            ) AS Hand_Credit_Closing
        ");

        $handCredit = $handCreditQry->fetch()['hand_credits'];

        $handDebitQry = $this->db->query("SELECT
            SUM(amt) AS hand_debits
            FROM (
                (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) <= '$closing_date' $user_where)
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) <= '$closing_date' $user_where)
            ) AS Hand_Debit_Closing
        ");

        $handDebit = $handDebitQry->fetch()['hand_debits'];
        if ($handCredit == 0 && $handDebit == 0) {
            $records[0]['hand_closing'] = 0;
        } else {
            $records[0]['hand_closing'] = intVal($handCredit) - intVal($handDebit);
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $bank_details_arr = explode(',', $bank_detail);
        $i = 0;
        $bank_closing_all = 0;
        foreach ($bank_details_arr as $val) {
            $bankQry = $this->db->query("SELECT balance FROM bank_stmt WHERE bank_id = '$val' AND DATE(trans_date) <= '$closing_date' ORDER BY trans_date DESC,id DESC LIMIT 1 ;");

            $row = $bankQry->fetch(PDO::FETCH_ASSOC);

            $closing_balance = ($row && isset($row['balance'])) ? (float)$row['balance'] : 0;
            $records[$i]['bank_closing'] = $closing_balance;
            $bank_closing_all += $closing_balance;

            $i++;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //only for collections we need user ids of agents
        $qry = $this->db->query("SELECT ag_id FROM agent_creation WHERE 1");
        $agent_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_ids = implode(',', $agent_ids);

        //get agent user id to get data for collection.
        $qry = $this->db->query("SELECT `user_id` FROM user WHERE FIND_IN_SET( `ag_id`, '$ag_ids')");
        $ag_user_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_user_id = implode(',', $ag_user_ids);
        $ag_where = " AND FIND_IN_SET(insert_login_id, '$ag_user_id') ";


        $agentCollQry = $this->db->query("SELECT
            SUM(amt) AS agent_coll
            FROM (
                (SELECT COALESCE(SUM(total_paid_track), 0) AS amt FROM collection
                WHERE DATE(created_date) <= '$closing_date' $ag_where)   
            ) AS Agent_Collection_Credit_Closing
        ");

        $agentCollCredit = $agentCollQry->fetch()['agent_coll'];

        $agentIssueQry = $this->db->query("SELECT 
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
                    DATE(created_date) <= '$closing_date' 
                    AND FIND_IN_SET(agent_id,'$ag_ids') AND agent_id IS NOT NULL          
            ) AS Agent_Issue_Debit_Closing;
        ");

        $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

        $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);

        $agentCreditQry = $this->db->query("SELECT
            SUM(amt) AS agent_credit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE DATE(created_date) <= '$closing_date' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
                
            ) AS Agent_Credit_Closing
        ");

        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        $agentDebitQry = $this->db->query("SELECT
            SUM(amt) AS agent_debit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE DATE(created_date) <= '$closing_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
                
            ) AS Agent_Debit_Closing
        ");

        $agentDebit = $agentDebitQry->fetch()['agent_debit'];

        $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

        //
        $agentCreditQry = $this->db->query("SELECT
            SUM(amt) AS agent_credit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE DATE(updated_date) <= '$closing_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
                
            ) AS Agent_Credit_Closing
        ");

        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        $agentDebitQry = $this->db->query("SELECT
            SUM(amt) AS agent_debit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE DATE(updated_date) <= '$closing_date' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
                
            ) AS Agent_Debit_Closing
        ");

        $agentDebit = $agentDebitQry->fetch()['agent_debit'];

        $agent_bank_op = intVal($agentDebit) - intVal($agentCredit);

        //
        if ($agent_hand_op == 0 && $agent_bank_op == 0 && $agent_CL_op == 0) {
            $records[0]['agent_closing'] = 0;
        } else {
            $records[0]['agent_closing'] = $agent_hand_op + $agent_bank_op + $agent_CL_op;
        }

        $records[0]['hand_closing'] = $records[0]['hand_closing'] - $agent_hand_op; //this will subract the hand debited amount for the agent with hand closing cash
        //this will subract the bank debited amount for the agent with bank closing cash

        $closing_total = $records[0]['hand_closing'] + $bank_closing_all;

        if (floor($closing_total) == $closing_total) {
            // No decimal part
            $records[0]['closing_balance'] = number_format($closing_total, 0, '.', '');
        } else {
            // Has decimal part
            $records[0]['closing_balance'] = number_format($closing_total, 2, '.', '');
        }

        return $records;
    }

    public function getDetails($op_date, $bank_detail, $user_id)
    {
        $user_where = "";
        if ($user_id != '') {
            $user_where = "AND insert_login_id = '$user_id' ";
        }

        $handCreditQry = $this->db->query("SELECT
            SUM(amt) AS hand_credits
            FROM (
                (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_waiver WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) <'$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) < '$op_date' $user_where )
            ) AS Hand_Credit_Opening
        ");

        $handCredit = $handCreditQry->fetch()['hand_credits'];

        $handDebitQry = $this->db->query("SELECT
            SUM(amt) AS hand_debits
            FROM (
                (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) <'$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) < '$op_date' $user_where )
                UNION ALL
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) < '$op_date' $user_where )
            ) AS Hand_Debit_Opening
        ");

        $handDebit = $handDebitQry->fetch()['hand_debits'];

        $records[0]['hand_opening'] = intVal($handCredit) - intVal($handDebit);

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $bank_details_arr = explode(',', $bank_detail);
        $i = 0;
        $bank_opening_all = 0;
        foreach ($bank_details_arr as $val) {
            $bankQry = $this->db->query("SELECT balance FROM bank_stmt WHERE bank_id = '$val' AND DATE(trans_date) < '$op_date' ORDER BY trans_date DESC,id DESC LIMIT 1 ;");
            $row = $bankQry->fetch(PDO::FETCH_ASSOC);

            $opening_balance = ($row && isset($row['balance'])) ? (float)$row['balance'] : 0;
            $records[$i]['bank_opening'] = $opening_balance;
            $bank_opening_all += $opening_balance;

            $i++;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //only for collections we need user ids of agents
        // $qry = $this->db->query("SELECT `agentforstaff` AS ag_id FROM user WHERE `user_id` = '$user_id'");
        $qry = $this->db->query("SELECT ag_id FROM agent_creation WHERE 1");
        $agent_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_ids = implode(',', $agent_ids);

        //get agent user id to get data for collection.
        $qry = $this->db->query("SELECT `user_id` FROM user WHERE FIND_IN_SET( `ag_id`, '$ag_ids')");
        $ag_user_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_user_id = implode(',', $ag_user_ids);
        $ag_where = " AND FIND_IN_SET(cl.insert_login_id, '$ag_user_id') ";


        $agentCollQry = $this->db->query("SELECT
            SUM(amt) AS agent_coll
            FROM (
                SELECT COALESCE(SUM(total_paid_track), 0) AS amt
                FROM collection cl
                WHERE DATE(created_date) < '$op_date'
                $ag_where  

            ) AS Agent_Collection_Credit_Opening
        ");

        $agentCollCredit = $agentCollQry->fetch()['agent_coll'];

        $agentIssueQry = $this->db->query("SELECT 
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
                    DATE(created_date) < '$op_date'
                    AND FIND_IN_SET(agent_id,'$ag_ids') AND agent_id IS NOT NULL 
                
            ) AS Agent_Issue_Debit_Opening
        ");

        $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

        //Collection - Loan Issue ,for Agent.
        $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);

        //credit hand cash agent
        $agentCreditQry = $this->db->query("SELECT
            SUM(amt) AS agent_credit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE DATE(created_date) < '$op_date' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
                
            ) AS Agent_Credit_Opening
        ");

        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        //Debit hand cash agent
        $agentDebitQry = $this->db->query("SELECT
            SUM(amt) AS agent_debit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE DATE(created_date) < '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
                
            ) AS Agent_Debit_Opening
        ");

        $agentDebit = $agentDebitQry->fetch()['agent_debit'];

        //hand cash dr - cr.
        $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

        //credit bank cash agent
        $agentCreditQry = $this->db->query("SELECT
            SUM(amt) AS agent_credit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE DATE(updated_date) < '$op_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
                
            ) AS Agent_Credit_Opening
        ");

        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        //Debit bank cash agent
        $agentDebitQry = $this->db->query("SELECT
            SUM(amt) AS agent_debit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE DATE(updated_date) < '$op_date' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
                
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
        return $records;
    }
}