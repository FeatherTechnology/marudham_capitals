<?php
class CircularAmountClass
{
    private $db;

    public function __construct($connect)
    {
        $this->db = $connect;
    }
    
    public function getTotalBalance($to_date, $branch_id)
    {
        $next_date = date('Y-m-d', strtotime($to_date . ' +1 day'));

        $sql = "
                WITH user_coll AS (
                    SELECT
                        c.insert_login_id AS user_id,
                        SUM(CASE WHEN c.coll_date < :to_date 
                                THEN c.total_paid_track ELSE 0 END) AS coll_amt_ys,

                        SUM(CASE WHEN c.coll_date >= :to_date 
                                AND c.coll_date < :next_date
                                THEN c.total_paid_track ELSE 0 END) AS coll_amt_today
                    FROM collection c
                    WHERE c.coll_mode = '1'
                    AND c.branch IN ($branch_id)
                    AND c.coll_date < :next_date
                    GROUP BY c.insert_login_id
                ),

                user_hand AS (
                    SELECT
                        hc.user_id,
                        SUM(CASE WHEN hc.created_date < :to_date 
                                THEN hc.rec_amt ELSE 0 END) AS rec_amt_ys,

                        SUM(CASE WHEN hc.created_date >= :to_date 
                                AND hc.created_date < :next_date
                                THEN hc.rec_amt ELSE 0 END) AS rec_amt_today
                    FROM ct_hand_collection hc
                    WHERE hc.branch_id IN ($branch_id)
                    AND hc.created_date < :next_date
                    GROUP BY hc.user_id
                )

                SELECT 
                    SUM(
                        (IFNULL(uc.coll_amt_ys,0) - IFNULL(uh.rec_amt_ys,0)) +
                        (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0))
                    ) AS cur_circ_coll

                FROM user u
                LEFT JOIN user_coll uc ON uc.user_id = u.user_id
                LEFT JOIN user_hand uh ON uh.user_id = u.user_id
                WHERE u.user_id <> 1
                AND (
                    (IFNULL(uc.coll_amt_ys,0) - IFNULL(uh.rec_amt_ys,0)) > 0
                OR (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0)) > 0
                OR (uh.rec_amt_today > 0)
                ) ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':to_date'   => $to_date,
            ':next_date' => $next_date
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cur_circ_coll'] ?? 0;
    }
    public function getTotalwaiver($from_date, $to_date, $branch_id)
    {
        $sql = "
            WITH user_waiver AS (
                SELECT
                    c.insert_login_id AS user_id,
                    SUM(CASE 
                            WHEN c.coll_date >= :from_date 
                            AND c.coll_date < :to_date
                            THEN c.pre_close_waiver 
                            ELSE 0 
                        END) AS waiver_amt_today
                FROM collection c
                WHERE c.coll_mode = '1'
                AND c.branch IN ($branch_id)
                AND c.coll_date < :to_date
                AND c.pre_close_waiver > 0
                GROUP BY c.insert_login_id
            ),
            user_hand AS (
                SELECT
                    hw.user_id,
                    SUM(CASE 
                            WHEN hw.created_date >= :from_date 
                            AND hw.created_date < :to_date
                            THEN hw.rec_amt 
                            ELSE 0 
                        END) AS rec_amt_today
                FROM ct_hand_waiver hw
                WHERE hw.branch_id IN ($branch_id)
                AND hw.created_date < :to_date
                GROUP BY hw.user_id
            )

            SELECT 
                SUM(
                    (IFNULL(uw.waiver_amt_today,0) - IFNULL(uh.rec_amt_today,0))
                ) AS cur_circ_waiver
            FROM user u
            LEFT JOIN user_waiver uw ON uw.user_id = u.user_id
            LEFT JOIN user_hand uh ON uh.user_id = u.user_id
            WHERE u.user_id <> 1
            AND (
                    (IFNULL(uw.waiver_amt_today,0) - IFNULL(uh.rec_amt_today,0)) > 0
                OR (uh.rec_amt_today > 0)
                );
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':from_date' => $from_date,
            ':to_date' => $to_date
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cur_circ_waiver'] ?? 0;
    }

    public function getTotalIssued($to_date)
    {
        $sql = "
            SELECT SUM(user_balance) AS cur_circ_issued
            FROM (
                SELECT t.insert_login_id, t.total_issued - 
                    COALESCE((
                        SELECT SUM(hi.netcash)
                        FROM ct_db_hissued hi
                        WHERE hi.li_user_id = t.insert_login_id
                        AND hi.created_date BETWEEN :from_date_time AND :to_date_time
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
            ':from_date_time' => '2026-01-01 00:00:00',
            ':to_date_time' => $to_date . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cur_circ_issued'] ?? 0;
    }

    public function getTotalExchange($to_date)
    {
        $sql = "
            SELECT SUM(amt) AS cur_circ_exchange
            FROM ct_db_hexchange
            WHERE received = 1
            AND date(created_date) <= :to_date_time
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':to_date_time' => $to_date . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cur_circ_exchange'] ?? 0;
    }

    public function getTotalWithdraw($to_date)
    {
        $sql = "
        SELECT SUM(amt) AS cur_circ_withdraw
        FROM ct_db_cash_withdraw
        WHERE (  received = 1 AND DATE(created_date) <= :to_date_time) OR (received = 0 AND DATE(created_date) <= :to_date_time AND DATE(updated_date) > :to_date_time)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':to_date_time' => $to_date . ' 23:59:59'
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cur_circ_withdraw'] ?? 0;
    }

    public function getTotalPreviousCollection($from_date, $branch_id)
    {
        $prev_date = date('Y-m-d', strtotime($from_date . ' -1 day'));

        $sql = "
            WITH user_coll AS (
                    SELECT
                        c.insert_login_id AS user_id,
                        SUM(CASE WHEN c.coll_date < :to_date 
                                THEN c.total_paid_track ELSE 0 END) AS coll_amt_ys,

                        SUM(CASE WHEN c.coll_date >= :to_date 
                                AND c.coll_date < :next_date
                                THEN c.total_paid_track ELSE 0 END) AS coll_amt_today
                    FROM collection c
                    WHERE c.coll_mode = '1'
                    AND c.branch IN ($branch_id)
                    AND c.coll_date < :next_date
                    GROUP BY c.insert_login_id
                ),

                user_hand AS (
                    SELECT
                        hc.user_id,
                        SUM(CASE WHEN hc.created_date < :to_date 
                                THEN hc.rec_amt ELSE 0 END) AS rec_amt_ys,

                        SUM(CASE WHEN hc.created_date >= :to_date 
                                AND hc.created_date < :next_date
                                THEN hc.rec_amt ELSE 0 END) AS rec_amt_today
                    FROM ct_hand_collection hc
                    WHERE hc.branch_id IN ($branch_id)
                    AND hc.created_date < :next_date
                    GROUP BY hc.user_id
                )

                SELECT 
                    SUM(
                        (IFNULL(uc.coll_amt_ys,0) - IFNULL(uh.rec_amt_ys,0)) +
                        (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0))
                    ) AS pre_circ_coll

                FROM user u
                LEFT JOIN user_coll uc ON uc.user_id = u.user_id
                LEFT JOIN user_hand uh ON uh.user_id = u.user_id
                WHERE u.user_id <> 1
                AND (
                    (IFNULL(uc.coll_amt_ys,0) - IFNULL(uh.rec_amt_ys,0)) > 0
                OR (IFNULL(uc.coll_amt_today,0) - IFNULL(uh.rec_amt_today,0)) > 0
                OR (uh.rec_amt_today > 0)
                )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':to_date' => $prev_date,
            ':next_date' => $from_date
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['pre_circ_coll'] ?? 0;
    }

    public function getTotalPreIssued($from_date)
    {
        $prev_date_time = date('Y-m-d', strtotime($from_date . ' -1 day'));

        $sql = "
            SELECT SUM(user_balance) AS pre_circ_issued
            FROM (
                SELECT t.insert_login_id, t.total_issued - 
                    COALESCE((
                        SELECT SUM(hi.netcash)
                        FROM ct_db_hissued hi
                        WHERE hi.li_user_id = t.insert_login_id
                        AND hi.created_date BETWEEN :from_date_time AND :to_date_time
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
            ':from_date_time' =>'2026-01-01 00:00:00',
            ':to_date_time' => $prev_date_time . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['pre_circ_issued'] ?? 0;
    }

    public function getTotalpreExchange($from_date)
    {

    $prev_date_time = date('Y-m-d', strtotime($from_date . ' -1 day'));

        $sql = "
            SELECT SUM(amt) AS pre_circ_exchange
            FROM ct_db_hexchange
            WHERE  received = 1
            AND date(created_date) <= :to_date_time
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':to_date_time' => $prev_date_time . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['pre_circ_exchange'] ?? 0;
    }

     public function getTotalpreWithdraw($from_date)
    {
        $prev_date_time = date('Y-m-d', strtotime($from_date . ' -1 day'));
        $sql = "SELECT SUM(amt) AS pre_circ_withdraw
        FROM ct_db_cash_withdraw
        WHERE (  received = 1 AND DATE(created_date) <= :to_date_time) OR (received = 0 AND DATE(created_date) <= :to_date_time AND DATE(updated_date) > :to_date_time)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':to_date_time' => $prev_date_time . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['pre_circ_withdraw'] ?? 0;
    }

    public function getTotalprewaiver($from_date, $branch_id)
    {
        $prev_date_time = date('Y-m-d', strtotime($from_date . ' -1 day'));
        $sql = "
            WITH user_waiver AS (
                SELECT
                    c.insert_login_id AS user_id,
                    SUM(CASE 
                            WHEN c.coll_date >= :from_date 
                            AND c.coll_date < :to_date
                            THEN c.pre_close_waiver 
                            ELSE 0 
                        END) AS waiver_amt_today
                FROM collection c
                WHERE c.coll_mode = '1'
                AND c.branch IN ($branch_id)
                AND c.coll_date < :to_date
                AND c.pre_close_waiver > 0
                GROUP BY c.insert_login_id
            ),
            user_hand AS (
                SELECT
                    hw.user_id,
                    SUM(CASE 
                            WHEN hw.created_date >= :from_date 
                            AND hw.created_date < :to_date
                            THEN hw.rec_amt 
                            ELSE 0 
                        END) AS rec_amt_today
                FROM ct_hand_waiver hw
                WHERE hw.branch_id IN ($branch_id)
                AND hw.created_date < :to_date
                GROUP BY hw.user_id
            )

            SELECT 
                SUM(
                    (IFNULL(uw.waiver_amt_today,0) - IFNULL(uh.rec_amt_today,0))
                ) AS pre_circ_Waiver
            FROM user u
            LEFT JOIN user_waiver uw ON uw.user_id = u.user_id
            LEFT JOIN user_hand uh ON uh.user_id = u.user_id
            WHERE u.user_id <> 1
            AND (
                    (IFNULL(uw.waiver_amt_today,0) - IFNULL(uh.rec_amt_today,0)) > 0
                OR (uh.rec_amt_today > 0)
                );
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':from_date' => $prev_date_time . ' 00:00:00',
            ':to_date' => $prev_date_time . ' 23:59:59'
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['pre_circ_Waiver'] ?? 0;
    }

    /**
     * Get final circular amount
     */
    public function getCircularAmount($from_date, $to_date, $branch_id, $user_id)
    {
        $cur_circ_coll = $this->getTotalBalance( $to_date, $branch_id);
        $cur_circ_waiver = $this->getTotalwaiver($from_date, $to_date, $branch_id);
        $cur_circ_issued = $this->getTotalIssued($to_date);
        $cur_circ_exchange = $this->getTotalExchange($from_date);
        $cur_circ_withdraw = $this->getTotalWithdraw($to_date);

        $pre_circ_issued = $this->getTotalPreIssued($from_date);
        $pre_circ_coll= $this->getTotalPreviousCollection($from_date, $branch_id);
        $pre_circ_exchange = $this->getTotalpreExchange($from_date);
        $pre_circ_withdraw = $this->getTotalpreWithdraw($from_date);
        $pre_circ_Waiver = $this->getTotalprewaiver($from_date, $branch_id);

    return [
        'cur_circ_withdraw' => $cur_circ_withdraw,
        'cur_circ_waiver'  => $cur_circ_waiver,
        'cur_circ_coll'  => $cur_circ_coll,
        'cur_circ_issued'  => $cur_circ_issued,
        'cur_circ_exchange'  => $cur_circ_exchange,

        'pre_circ_issued'  => $pre_circ_issued,
        'pre_circ_coll'  => $pre_circ_coll,
        'pre_circ_exchange'  => $pre_circ_exchange,
        'pre_circ_withdraw'  => $pre_circ_withdraw,
        'pre_circ_Waiver'  => $pre_circ_Waiver,
    ];
    }
}