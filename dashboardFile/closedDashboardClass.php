<?php

class ClosedDashboardClass
{
    public $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getClosedCounts($connect)
    {
        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) || $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];

        $tot_in_cl = "SELECT COUNT(*) as tot_in_cl FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 20 ";
        $month_in_cl = "SELECT COUNT(*) as month_in_cl FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closing_customer cc ON cc.req_id = req.req_id WHERE month(cc.closing_date) = month('$month') and year(cc.closing_date) = year('$month') ";
        $month_cl_status = "SELECT COUNT(*) as month_cl_status FROM closed_status cls JOIN acknowlegement_customer_profile cp ON cp.req_id = cls.req_id WHERE cls.cus_sts = 21 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') ";
        $month_cl_bal = "SELECT COUNT(*) as month_cl_bal FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status = 20 and month(req.updated_date) = month('$month') and year(req.updated_date) = year('$month') ";
        $today_in_cl = "SELECT COUNT(*) as today_in_cl FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status = 20 and date(req.updated_date) = date('$month') ";
        $today_cl_status = "SELECT COUNT(*) as today_cl_status FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and date(cls.created_date) = date('$month') ";
        $consider = "SELECT COUNT(*) as consider FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.closed_sts = 1 ";
        $waiting = "SELECT COUNT(*) as waiting FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.closed_sts = 2 ";
        $blocked = "SELECT COUNT(*) as `blocked` FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.closed_sts = 3 ";
        $bronze = "SELECT COUNT(*) as bronze FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.consider_level = 1 ";
        $silver = "SELECT COUNT(*) as silver FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.consider_level = 2 ";
        $gold = "SELECT COUNT(*) as gold FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.consider_level = 3 ";
        $platinum = "SELECT COUNT(*) as platinum FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.consider_level = 4 ";
        $diamond = "SELECT COUNT(*) as diamond FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id JOIN closed_status cls ON cls.req_id = req.req_id WHERE req.cus_status >= 20 and month(cls.created_date) = month('$month') and year(cls.created_date) = year('$month') and cls.consider_level = 5 ";


        if (empty($sub_area_list)) {
            $sub_area_list = $this->getUserGroupBasedSubArea($connect, $this->user_id);
        }

        $tot_in_cl .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $month_in_cl .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $month_cl_status .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $month_cl_bal .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $today_in_cl .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $today_cl_status .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $consider .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $waiting .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $blocked .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $bronze .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $silver .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $gold .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $platinum .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $diamond .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $tot_in_clQry = $connect->query($tot_in_cl);
        $month_in_clQry = $connect->query($month_in_cl);
        $month_cl_statusQry = $connect->query($month_cl_status);
        $month_cl_balQry = $connect->query($month_cl_bal);
        $today_in_clQry = $connect->query($today_in_cl);
        $today_cl_statusQry = $connect->query($today_cl_status);
        $considerQry = $connect->query($consider);
        $waitingQry = $connect->query($waiting);
        $blockedQry = $connect->query($blocked);
        $bronzeQry = $connect->query($bronze);
        $silverQry = $connect->query($silver);
        $goldQry = $connect->query($gold);
        $platinumQry = $connect->query($platinum);
        $diamondQry = $connect->query($diamond);

        $response['tot_in_cl'] = $tot_in_clQry->fetch()['tot_in_cl'];
        $response['month_in_cl'] = $month_in_clQry->fetch()['month_in_cl'];
        $response['month_cl_status'] = $month_cl_statusQry->fetch()['month_cl_status'];
        $response['month_cl_bal'] = $month_cl_balQry->fetch()['month_cl_bal'];
        $response['today_in_cl'] = $today_in_clQry->fetch()['today_in_cl'];
        $response['today_cl_status'] = $today_cl_statusQry->fetch()['today_cl_status'];
        $response['cl_cn'] = $considerQry->fetch()['consider'];
        $response['cl_wl'] = $waitingQry->fetch()['waiting'];
        $response['cl_bl'] = $blockedQry->fetch()['blocked'];
        $response['cl_bronze'] = $bronzeQry->fetch()['bronze'];
        $response['cl_silver'] = $silverQry->fetch()['silver'];
        $response['cl_gold'] = $goldQry->fetch()['gold'];
        $response['cl_platinum'] = $platinumQry->fetch()['platinum'];
        $response['cl_diamond'] = $diamondQry->fetch()['diamond'];

        return $response;
    }

    function getUserGroupBasedSubArea($connect, $user_id)
    {
        if (empty($user_id)) {
            return '';
        }

        // 1. Get group IDs of user
        $stmt = $connect->prepare("SELECT group_id FROM user WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['group_id'])) {
            return '';
        }

        $group_ids = array_filter(explode(',', $row['group_id']));
        if (empty($group_ids)) {
            return '';
        }

        // 2. Prepare placeholders
        $placeholders = implode(',', array_fill(0, count($group_ids), '?'));

        // 3. Fetch sub_area_ids directly from normalized table
        $stmt = $connect->prepare("SELECT DISTINCT sub_area_id FROM area_group_mapping_sub_area
        WHERE group_map_id IN ($placeholders) ");
        $stmt->execute(array_map('intval', $group_ids));

        // 4. Fetch all sub_area_ids as array
        $sub_area_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($sub_area_ids)) {
            return '';
        }

        // 5. Return comma-separated list (if needed)
        return implode(',', array_unique($sub_area_ids));
    }
}
