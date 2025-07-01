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
            (SELECT COALESCE(SUM(rec_amt), 0) AS amt FROM ct_hand_collection WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bank_withdraw WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hoti WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hinvest WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hexchange WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hel WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hdeposit WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
        ) AS Hand_Credit_Closing
    ");

        $handCredit = $handCreditQry->fetch()['hand_credits'];

        $handDebitQry = $this->db->query("SELECT
        SUM(amt) AS hand_debits
        FROM (
            (SELECT COALESCE(SUM(amount), 0) AS amt FROM ct_db_bank_deposit WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hinvest WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_hissued WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hel WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexchange WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hexpense WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
            UNION ALL
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hdeposit WHERE date(created_date) <= '$closing_date' $user_where ORDER BY created_date DESC LIMIT 1)
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
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_cash_deposit WHERE date(created_date) <= '$closing_date' and to_bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(credited_amt), 0) AS amt FROM ct_bank_collection WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bdeposit WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bel WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bexchange WHERE date(created_date) <= '$closing_date' and to_bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_binvest WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_boti WHERE date(created_date) <= '$closing_date' and to_bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) <= '$closing_date' AND bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                ) AS Bank_Credit_Closing
            ");

            $bankCredit = $bankCreditQry->fetch()['bank_credit'];

            $bankDebitQry = $this->db->query("SELECT
                SUM(amt) AS bank_debit
                FROM (
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_cash_withdraw WHERE date(created_date) <= '$closing_date' and from_bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bdeposit WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bel WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    -- (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_exf WHERE date(created_date) = '$closing_date' and to_bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    -- UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexchange WHERE date(created_date) <= '$closing_date' and from_acc_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bexpense WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_binvest WHERE date(created_date) <= '$closing_date' and bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL
                    (SELECT COALESCE(SUM(netcash), 0) AS amt FROM ct_db_bissued WHERE date(created_date) <= '$closing_date' and li_bank_id = '$val' $user_where ORDER BY created_date DESC LIMIT 1)
                    UNION ALL 
                    (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) <= '$closing_date' and bank_id = '$val'  $user_where ORDER BY created_date DESC LIMIT 1)
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
        if ($user_id != '') {
            $qry = $this->db->query("SELECT `user_id` from `user` where ag_id IN (SELECT ag.ag_id FROM agent_creation ag JOIN `user` us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id = '$user_id')  ");
            $ag_ids = array();
            //without while it will not give all the agent ids
            while ($rww = $qry->fetch()) {
                $ag_ids[] = $rww["user_id"];
            }
            $ag_ids = implode(',', $ag_ids);
            $ag_where = " AND FIND_IN_SET(insert_login_id,'$ag_ids') ";
        } else {
            $ag_where = "";
        }

        $agentCollQry = $this->db->query("SELECT
        SUM(amt) AS agent_coll
        FROM (
            (SELECT COALESCE(SUM(total_paid_track), 0) AS amt FROM collection
            WHERE date(created_date) <= '$closing_date' $ag_where ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Collection_Credit_Closing
    ");

        $agentCollCredit = $agentCollQry->fetch()['agent_coll'];


        //only for collections we need user ids of agents
        if ($user_id != '') {
            $qry = $this->db->query("SELECT ag.ag_id FROM agent_creation ag JOIN user us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id = '$user_id'");
        } else {
            $qry = $this->db->query("SELECT ag_id FROM agent_creation where 1");
        }
        $ag_ids = [];
        while ($rww = $qry->fetch()) {
            $ag_ids[] = $rww["ag_id"];
        }
        $ag_ids = implode(',', $ag_ids);


        $agentIssueQry = $this->db->query("  SELECT 
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
        AND FIND_IN_SET(agent_id,'$ag_ids')   AND agent_id IS NOT NULL  $user_where
      
) AS Agent_Issue_Debit_Closing;
    ");

        $agentIssueDebit = $agentIssueQry->fetch()['agent_issue'];

        $agent_CL_op = intVal($agentCollCredit) - intVal($agentIssueDebit);


        $agentCreditQry = $this->db->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_hag WHERE date(created_date) <= '$closing_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Credit_Closing
    ");
  


        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        $agentDebitQry = $this->db->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_hag WHERE date(created_date) <= '$closing_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Debit_Closing
    ");

        $agentDebit = $agentDebitQry->fetch()['agent_debit'];

        $agent_hand_op = intVal($agentDebit) - intVal($agentCredit);

        //

        $agentCreditQry = $this->db->query("SELECT
        SUM(amt) AS agent_credit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_cr_bag WHERE date(created_date) <= '$closing_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where ORDER BY created_date DESC LIMIT 1)
            
        ) AS Agent_Credit_Closing
    ");

        $agentCredit = $agentCreditQry->fetch()['agent_credit'];

        $agentDebitQry = $this->db->query("SELECT
        SUM(amt) AS agent_debit
        FROM (
            (SELECT COALESCE(SUM(amt), 0) AS amt FROM ct_db_bag WHERE date(created_date) <= '$closing_date' AND FIND_IN_SET(ag_id,'$ag_ids') $user_where ORDER BY created_date DESC LIMIT 1)
            
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

        $records[0]['closing_balance'] = $records[0]['hand_closing'] + $bank_closing_all + $records[0]['agent_closing'];

        return $records;
    }
}
