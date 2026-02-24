<?php
class CircularAmountClass
{
    private $db;

    public function __construct($connect)
    {
        $this->db = $connect;
    }

    public function getTotalBalance($from_date, $to_date, $branch_id)
    {
        $sql = "
            WITH user_coll AS (
                SELECT
                    c.insert_login_id AS user_id,
                    SUM(CASE WHEN c.coll_date >= :from_date 
                            AND c.coll_date < :to_date
                            THEN c.total_paid_track ELSE 0 END) AS coll_amt_today
                FROM collection c
                WHERE c.coll_mode = '1'
                AND c.branch IN ($branch_id)
                AND c.coll_date < :to_date
                GROUP BY c.insert_login_id
            ),
            user_hand AS (
                SELECT
                    hc.user_id,
                    SUM(CASE WHEN hc.created_date >= :from_date 
                            AND hc.created_date < :to_date
                            THEN hc.rec_amt ELSE 0 END) AS rec_amt_today
                FROM ct_hand_collection hc
                WHERE hc.branch_id IN ($branch_id)
                AND hc.created_date < :to_date
                GROUP BY hc.user_id
            )
            SELECT 
                SUM(
                    (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0))
                ) AS total_balance
            FROM user u
            LEFT JOIN user_coll uc ON uc.user_id = u.user_id
            LEFT JOIN user_hand uh ON uh.user_id = u.user_id
            WHERE u.user_id <> 1
            AND ((IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0)) > 0
            OR (uh.rec_amt_today > 0))
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':from_date' => $from_date,
            ':to_date' => $to_date
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_balance'] ?? 0;
    }

    public function getTotalIssued($from_date, $to_date)
    {
        $sql = "
            SELECT SUM(user_balance) AS total_issued
            FROM (
                SELECT t.insert_login_id, t.total_issued - 
                    COALESCE((
                        SELECT SUM(hi.netcash)
                        FROM ct_db_hissued hi
                        WHERE hi.li_user_id = t.insert_login_id
                        AND DATE(hi.created_date) >= t.first_created_date
                    ),0) AS user_balance
                FROM (
                    SELECT li.insert_login_id, SUM(li.cash) AS total_issued,
                        DATE(MIN(li.created_date)) AS first_created_date
                    FROM loan_issue li
                    WHERE (li.agent_id = '' OR li.agent_id IS NULL)
                    AND ( (li.issued_mode = 1 AND li.payment_type = '0')
                        OR (li.issued_mode = 0 AND li.cash IS NOT NULL) )
                    AND li.created_date BETWEEN :from_date_time AND :to_date_time
                    GROUP BY li.insert_login_id
                ) t
            ) final_table
            WHERE user_balance > 0
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':from_date_time' => $from_date . ' 00:00:00',
            ':to_date_time' => $to_date . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_issued'] ?? 0;
    }

    public function getTotalExchange($user_id, $from_date, $to_date)
    {
        $sql = "
            SELECT SUM(amt) AS total_exchange
            FROM ct_db_hexchange
            WHERE to_user_id = :user_id
            AND received = 1
            AND created_date BETWEEN :from_date_time AND :to_date_time
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':from_date_time' => $from_date . ' 00:00:00',
            ':to_date_time' => $to_date . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_exchange'] ?? 0;
    }

    public function getTotalWithdraw($from_date, $to_date)
    {
        $sql = "
            SELECT SUM(amt) AS total_withdraw
            FROM ct_db_cash_withdraw
            WHERE received = 1
            AND created_date BETWEEN :from_date_time AND :to_date_time
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':from_date_time' => $from_date . ' 00:00:00',
            ':to_date_time' => $to_date . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_withdraw'] ?? 0;
    }

    /**
     * Get final circular amount
     */
    public function getCircularAmount($from_date, $to_date, $branch_id, $user_id)
    {
        $total_balance = $this->getTotalBalance($from_date, $to_date, $branch_id);
        $total_issued = $this->getTotalIssued($from_date, $to_date);
        $total_exchange = $this->getTotalExchange($user_id, $from_date, $to_date);
        $total_withdraw = $this->getTotalWithdraw($from_date, $to_date);

        return ($total_balance + $total_exchange + $total_withdraw) - $total_issued;
    }
}