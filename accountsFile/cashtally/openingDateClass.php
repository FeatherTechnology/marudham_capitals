<?php
class OpeningDateClass
{
    private $db;

    public function __construct($connect)
    {
        $this->db = $connect;
    }

    public function getOpeningDate($user_id)
    {
        $latestDateQry = $this->db->query("
            SELECT MAX(latest_date) AS latest_transaction_date FROM (
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_hand_collection WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_bank_withdraw WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_hoti WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_hinvest WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_hexchange WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_hel WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_hdeposit WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_hag WHERE insert_login_id = '$user_id'
                
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bank_deposit WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hinvest WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hissued WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hel WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hexchange WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hexpense WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hdeposit WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_hag WHERE insert_login_id = '$user_id'
                
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_cash_deposit WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_bank_collection WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_bdeposit WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_bel WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_bexchange WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_binvest WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_boti WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_cr_bag WHERE insert_login_id = '$user_id'
                
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_cash_withdraw WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bdeposit WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bel WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bexchange WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bexpense WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_binvest WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bissued WHERE insert_login_id = '$user_id'
                UNION ALL
                SELECT MAX(DATE(created_date)) AS latest_date FROM ct_db_bag WHERE insert_login_id = '$user_id'
            ) AS AllTransactionDates
        ");

        $latestTxnDate = $latestDateQry->fetch()['latest_transaction_date'];
        return $latestTxnDate;
    }
}
