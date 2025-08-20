<?php
class ClosingBalanceClass
{
    private $db;
    public function __construct($connect)
    {
        $this->db = $connect;
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
            
            $bankCreditQry = $this->db->query("SELECT
                SUM(amt) AS bank_credit
                FROM (
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_cash_deposit WHERE date(created_date) <= '$closing_date' and to_bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(credited_amt), 0) AS amt FROM ct_bank_collection WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bdeposit WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bel WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bexchange WHERE date(created_date) <= '$closing_date' and to_bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_binvest WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_boti WHERE date(created_date) <= '$closing_date' and to_bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) <= '$closing_date' AND bank_id = '$val' $user_where)
                ) AS Bank_Credit_Closing
            ");

            $bankCredit = $bankCreditQry->fetch()['bank_credit'];

            $bankDebitQry = $this->db->query("SELECT
                SUM(amt) AS bank_debit
                FROM (
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_cash_withdraw WHERE date(created_date) <= '$closing_date' and from_bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bdeposit WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bel WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    -- (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_exf WHERE date(created_date) = '$closing_date' and to_bank_id = '$val' $user_where)
                    -- UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexchange WHERE date(created_date) <= '$closing_date' and from_acc_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexpense WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_binvest WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where)
                    UNION ALL
                    (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_bissued WHERE date(created_date) <= '$closing_date' and li_bank_id = '$val' $user_where)
                    UNION ALL 
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) <= '$closing_date' and bank_id = '$val'  $user_where)
                ) AS Bank_debit_Closing
            ");

            $bankDebit = $bankDebitQry->fetch()['bank_debit'];

            if ($bankCredit == 0 && $bankDebit == 0) {
                $records[$i]['bank_closing'] = 0;
            } else {
                $records[$i]['bank_closing'] = intVal($bankCredit) - intVal($bankDebit);
                $bank_closing_all = $bank_closing_all + $records[$i]['bank_closing'];
            }
            $i++;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // if ($user_id != '') {
        //     $qry = $this->db->query("SELECT `user_id` from user where FIND_IN_SET( `ag_id`, (SELECT `agentforstaff` from user where `user_id` = '$user_id'))");
        //     $ag_user_ids = array();
        //     //without while it will not give all the agent ids
        //     while ($rww = $qry->fetch()) {
        //         $ag_user_ids[] = $rww["user_id"];
        //     }
        //     $ag_user_id = implode(',', $ag_user_ids);
        //     $ag_where = " AND FIND_IN_SET(insert_login_id,'$ag_user_id') ";
        // } else {
        //     $ag_where = "";
        // }

        //only for collections we need user ids of agents
        // if ($user_id != '') {
        //     $qry = $this->db->query("SELECT ag.ag_id FROM agent_creation ag JOIN user us ON FIND_IN_SET(ag.ag_id, us.agentforstaff) WHERE us.user_id = '$user_id'");
        // } else {
        //     $qry = $this->db->query("SELECT ag_id FROM agent_creation WHERE 1");
        // }
        // $ag_ids = [];
        // while ($rww = $qry->fetch()) {
        //     $ag_ids[] = $rww["ag_id"];
        // }
        // $ag_ids = implode(',', $ag_ids);

        
        //only for collections we need user ids of agents
        // $qry = $connect->query("SELECT `agentforstaff` AS ag_id FROM user WHERE `user_id` = '$user_id'");
        $qry = $this->db->query("SELECT ag_id FROM agent_creation WHERE 1");
        $agent_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_ids = implode(',', $agent_ids);
        
        //get agent user id to get data for collection.
        $qry = $this->db->query("SELECT `user_id` FROM user WHERE FIND_IN_SET( `ag_id`, '$ag_ids')");
        $ag_user_ids = $qry->fetchAll(PDO::FETCH_COLUMN);
        $ag_user_id = implode(',', $ag_user_ids);
        $ag_where = " AND FIND_IN_SET(insert_login_id, '$ag_user_id') ";




        $closing_date_start = date('Y-m-01', strtotime($closing_date));
        $closing_date_start = $closing_date_start. ' 00:00:00';
        $closing_date_time = $closing_date. ' 23:59:59';

        $agentCollQry = $this->db->query("SELECT
            SUM(amt) AS agent_coll
            FROM (
                (SELECT COALESCE(SUM(total_paid_track), 0) AS amt FROM collection
                WHERE created_date BETWEEN '$closing_date_start' AND '$closing_date_time' $ag_where)   
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
                    created_date BETWEEN '$closing_date_start' AND '$closing_date_time' 
                    AND FIND_IN_SET(agent_id,'$ag_ids') AND agent_id IS NOT NULL          
            ) AS Agent_Issue_Debit_Closing;
        ");

        $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

        $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);

        $agentCreditQry = $this->db->query("SELECT
            SUM(amt) AS agent_credit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE created_date BETWEEN '$closing_date_start' AND '$closing_date_time' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
                
            ) AS Agent_Credit_Closing
        ");
  
        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        $agentDebitQry = $this->db->query("SELECT
            SUM(amt) AS agent_debit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE created_date BETWEEN '$closing_date_start' AND '$closing_date_time' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
                
            ) AS Agent_Debit_Closing
        ");

        $agentDebit = $agentDebitQry->fetch()['agent_debit'];

        $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

        //
        $agentCreditQry = $this->db->query("SELECT
            SUM(amt) AS agent_credit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE created_date BETWEEN '$closing_date_start' AND '$closing_date_time' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where)
                
            ) AS Agent_Credit_Closing
        ");

        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        $agentDebitQry = $this->db->query("SELECT
            SUM(amt) AS agent_debit
            FROM (
                (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE created_date BETWEEN '$closing_date_start' AND '$closing_date_time' AND FIND_IN_SET(ag_id, '$ag_ids') $user_where)
                
            ) AS Agent_Debit_Closing
        ");

        $agentDebit = $agentDebitQry->fetch()['agent_debit'];

        $agent_bank_op = intVal($agentDebit) - intVal($agentCredit);
        {
            $opening_qry = $this->db->query("SELECT 
    -- (Credit - Debit) + (Debit - Credit)
    IFNULL(SUM(CASE WHEN grp = 'coll_loan' THEN Credit - Debit END), 0) 
  + IFNULL(SUM(CASE WHEN grp = 'ct_tables' THEN Debit - Credit END), 0) 
  AS opening_balance
FROM (
    -- Collection
    SELECT cl.total_paid_track AS Credit, 0 AS Debit, 'coll_loan' AS grp
    FROM collection cl
    WHERE cl.created_date < DATE_FORMAT('$closing_date', '%Y-%m-01') $ag_where
                    
    UNION ALL
    -- Loan issue
    SELECT 0 AS Credit, (IFNULL(li.cash,0) + IFNULL(li.cheque_value,0) + IFNULL(li.transaction_value,0)) AS Debit, 'coll_loan' AS grp
    FROM loan_issue li
    WHERE  li.created_date < DATE_FORMAT('$closing_date', '%Y-%m-01') and FIND_IN_SET(li.agent_id,'$ag_ids') AND agent_id IS NOT NULL

    UNION ALL
    -- ct_db_hag → Debit
    SELECT 0 AS Credit, amt AS Debit, 'ct_tables' AS grp
    FROM ct_db_hag
     WHERE created_date < DATE_FORMAT('$closing_date', '%Y-%m-01')
                        $user_where

    UNION ALL
    -- ct_cr_hag → Credit
    SELECT amt AS Credit, 0 AS Debit, 'ct_tables' AS grp
    FROM ct_cr_hag
    WHERE created_date < DATE_FORMAT('$closing_date', '%Y-%m-01')
                        $user_where

    UNION ALL
    -- ct_db_bag → Debit
    SELECT 0 AS Credit, amt AS Debit, 'ct_tables' AS grp
    FROM ct_db_bag
    WHERE created_date < DATE_FORMAT('$closing_date', '%Y-%m-01')
                        $user_where

    UNION ALL
    -- ct_cr_bag → Credit
    SELECT amt AS Credit, 0 AS Debit, 'ct_tables' AS grp
    FROM ct_cr_bag
    WHERE created_date < DATE_FORMAT('$closing_date', '%Y-%m-01')
                        $user_where
) AS opening;");
            $op_bal = $opening_qry->fetch()['opening_balance'];
        }

        //
        if ($agent_hand_op == 0 && $agent_bank_op == 0 && $agent_CL_op == 0 && $op_bal == 0) {
            $records[0]['agent_closing'] = 0;
        } else {
            $records[0]['agent_closing'] = $agent_hand_op + $agent_bank_op + $agent_CL_op + $op_bal;
        }

        $records[0]['hand_closing'] = $records[0]['hand_closing'] - $agent_hand_op; //this will subract the hand debited amount for the agent with hand closing cash
        //this will subract the bank debited amount for the agent with bank closing cash

        // $records[0]['closing_balance'] = $records[0]['hand_closing'] + $bank_closing_all + $records[0]['agent_closing'];
        $records[0]['closing_balance'] = $records[0]['hand_closing'] + $bank_closing_all;

        return $records;
    }
}
