<?php

class collectionClass
{
    public $user_id;
    function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    function getCollectionCounts($connect)
    {
        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) && $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];

        $total_paid = "SELECT COALESCE(sum(c.due_amt_track +  c.princ_amt_track + c.int_amt_track ),0) as paid from `collection` c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id where ( ( MONTH(coll_date)= MONTH('$month') && YEAR(coll_date)= YEAR('$month') ) || ( MONTH(trans_date)= MONTH('$month') && YEAR(trans_date)= YEAR('$month') ) ) ";
        $total_penalty = "SELECT COALESCE(sum(c.penalty_track), 0) as penalty from `collection` c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id where ( ( MONTH(coll_date)= MONTH('$month') && YEAR(coll_date)= YEAR('$month') ) || ( MONTH(trans_date)= MONTH('$month') && YEAR(trans_date)= YEAR('$month') ) ) ";
        $total_fine = "SELECT COALESCE(sum(c.coll_charge_track), 0) as fine from `collection` c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id where ( ( MONTH(coll_date)= MONTH('$month') && YEAR(coll_date)= YEAR('$month') ) || ( MONTH(trans_date)= MONTH('$month') && YEAR(trans_date)= YEAR('$month') ) ) ";

        $today_paid = "SELECT COALESCE(sum(c.due_amt_track +  c.princ_amt_track + c.int_amt_track ),0) as paid from `collection` c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id where date(c.coll_date) = '$today' AND ( DATE(c.coll_date) = '$today' || DATE(c.trans_date) = '$today' )";
        $today_penalty = "SELECT COALESCE(sum(c.penalty_track), 0) as penalty from `collection` c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id where date(c.coll_date) = '$today' AND ( DATE(c.coll_date) = '$today' || DATE(c.trans_date) = '$today' )";
        $today_fine = "SELECT COALESCE(sum(c.coll_charge_track), 0) as fine from `collection` c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id where date(c.coll_date) = '$today' AND ( DATE(c.coll_date) = '$today' || DATE(c.trans_date) = '$today' )";
        $today_handcash = "SELECT COALESCE(sum(c.due_amt_track + c.penalty_track + c.coll_charge_track), 0) as today_hand_paid from `collection` c  where date(c.coll_date) = '$today' AND ( c.trans_date ='0000-00-00' )";
        $today_bankcash = "SELECT COALESCE(sum(c.due_amt_track + c.penalty_track + c.coll_charge_track), 0) as today_bank_paid from `collection` c  where date(c.coll_date) = '$today' AND ( c.trans_date !='0000-00-00' )";

        if (!empty($sub_area_list)) {
            $total_paid .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $total_penalty .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $total_fine .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $today_paid .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $today_penalty .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $today_fine .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $today_handcash .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
            $today_bankcash .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        } else {
            $total_paid .= " AND c.insert_login_id = '$this->user_id' ";
            $total_penalty .= " AND c.insert_login_id = '$this->user_id' ";
            $total_fine .= " AND c.insert_login_id = '$this->user_id' ";
            $today_paid .= " AND c.insert_login_id = '$this->user_id' ";
            $today_penalty .= " AND c.insert_login_id = '$this->user_id' ";
            $today_fine .= " AND c.insert_login_id = '$this->user_id' ";
            $today_handcash .= " AND c.insert_login_id = '$this->user_id' ";
            $today_bankcash .= " AND c.insert_login_id = '$this->user_id' ";
        }

        $total_paidQry = $connect->query($total_paid);
        $total_penaltyQry = $connect->query($total_penalty);
        $total_fineQry = $connect->query($total_fine);
        $today_paidQry = $connect->query($today_paid);
        $today_penaltyQry = $connect->query($today_penalty);
        $today_fineQry = $connect->query($today_fine);
        $today_handcash = $connect->query($today_handcash);
        $today_bankcash = $connect->query($today_bankcash);

        $response['tot_col_paid'] = number_format($total_paidQry->fetch()['paid'], 0, '', ',');
        $response['tot_col_pen'] = number_format($total_penaltyQry->fetch()['penalty'], 0, '', ',');
        $response['tot_col_fine'] = number_format($total_fineQry->fetch()['fine'], 0, '', ',');
        $response['today_col_paid'] = number_format($today_paidQry->fetch()['paid'], 0, '', ',');
        $response['today_col_pen'] = number_format($today_penaltyQry->fetch()['penalty'], 0, '', ',');
        $response['today_col_fine'] = number_format($today_fineQry->fetch()['fine'], 0, '', ',');
        $response['today_handcash'] = number_format($today_handcash->fetch()['today_hand_paid'], 0, '', ',');
        $response['today_bankcash'] = number_format($today_bankcash->fetch()['today_bank_paid'], 0, '', ',');
        // Calculate the total collection
        $total_collection = (int) str_replace(',', '', $response['today_col_paid']) +
            (int) str_replace(',', '', $response['today_col_pen']) +
            (int) str_replace(',', '', $response['today_col_fine']);

        // Format the total collection
        $response['total_collection'] = number_format($total_collection, 0, '', ',');

        return $response;
    }
    function getCollectionSplit($connect)
    {

        $response = array();
        $today = date('Y-m-d');
        $sub_area_list = $_POST['sub_area_list'];
        $pid = $_POST['pid'];

        $split_arr = ['tot_col_paid' => 'Total Paid Split', 'today_col_paid' => 'Today Paid Split', 'tot_col_pen' => 'Total Penalty Split', 'today_col_pen' => 'Today Penalty Split', 'tot_col_fine' => 'Total Fine Split', 'today_col_fine' => 'Today Fine Split'];

        if ($pid == 'today_col_paid' || $pid == 'tot_col_paid') {
            $fields = "c.due_amt_track +  c.princ_amt_track + c.int_amt_track";
        } else if ($pid == 'today_col_pen' || $pid == 'tot_col_pen') {
            $fields = "c.penalty_track";
        } else if ($pid == 'today_col_fine' || $pid == 'tot_col_fine') {
            $fields = "c.coll_charge_track";
        }

        $qry = "SELECT 
            SUM(CASE WHEN c.coll_sub_status = 'Current' THEN COALESCE($fields, 0) ELSE 0 END) AS cur_amt,
            COUNT(CASE WHEN c.coll_sub_status = 'Current' AND COALESCE($fields, 0) != 0 THEN 1 END) AS cur_point,
            SUM(CASE WHEN c.coll_sub_status = 'Pending' THEN COALESCE($fields, 0) ELSE 0 END) AS pend_amt,
            COUNT(CASE WHEN c.coll_sub_status = 'Pending' AND COALESCE($fields, 0) != 0 THEN 1 END) AS pend_point,
            SUM(CASE WHEN c.coll_sub_status = 'OD' THEN COALESCE($fields, 0) ELSE 0 END) AS od_amt,
            COUNT(CASE WHEN c.coll_sub_status = 'OD' AND COALESCE($fields, 0) != 0 THEN 1 END) AS od_point 
        FROM collection c JOIN acknowlegement_customer_profile cp ON cp.req_id = c.req_id WHERE ";


        if ($pid == 'today_col_paid' || $pid == 'today_col_pen' || $pid == 'today_col_fine') {
            $qry .= " ( DATE(c.coll_date) = '$today' || DATE(c.trans_date) = '$today' ) ";
        }
        if ($pid == 'tot_col_paid' || $pid == 'tot_col_pen' || $pid == 'tot_col_fine') {
            $qry .= " ( ( MONTH(coll_date)= MONTH('$today') && YEAR(coll_date)= YEAR('$today') ) || ( MONTH(trans_date)= MONTH('$today') && YEAR(trans_date)= YEAR('$today') ) ) ";
        }

        if (!empty($sub_area_list)) {
            $qry .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        } else {
            $qry .= " AND c.insert_login_id = '$this->user_id' ";
        }

        $runQry = $connect->query($qry);
        $response = $runQry->fetch();
        $response['split_name'] = $split_arr[$pid];
        // $row = $runQry->fetch();
        // $response['cur_amt'] = number_format($row['cur_amt'], 0, '', ', ');
        // $response['cur_point'] = $row['cur_point'];
        // $response['pend_amt'] = number_format($row['pend_amt'], 0, '', ', ');
        // $response['pend_point'] = $row['pend_point'];
        // $response['od_amt'] = number_format($row['od_amt'], 0, '', ', ');
        // $response['od_point'] = $row['od_point'];

        return $response;
    }
}
