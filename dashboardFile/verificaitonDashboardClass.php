<?php

class verificaitonClass
{
    public $user_id;
    function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    function getverificaitonCounts($connect)
    {
        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) && $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];
        $loan_category = $_POST['loan_category'];

        $tot_in_ver = "SELECT COUNT(*) as tot_in_ver from request_creation req JOIN in_verification iv ON iv.req_id = req.req_id where (req.cus_status >= 1 and req.cus_status NOT IN(4,8) ) and month(iv.created_date) = month('$month') and year(iv.created_date) = year('$month')";
        $today_in_ver = "SELECT COUNT(*) as tot_in_ver from request_creation where cus_status IN(1,10,11,12) and date(updated_date) = '$today' ";
        $tot_issue = "SELECT COUNT(*) as tot_issue FROM request_creation req JOIN customer_profile cp ON cp.req_id = req.req_id JOIN in_issue ii ON req.req_id = ii.req_id WHERE ii.cus_status >= 14 AND month(ii.updated_date) = month('$month') and year(ii.updated_date) = year('$month')";
        $today_issue = "SELECT COUNT(*) as today_issue FROM request_creation req JOIN customer_profile cp ON cp.req_id = req.req_id JOIN in_issue ii ON req.req_id = ii.req_id WHERE ii.cus_status >= 14 AND date(ii.updated_date) = '$today'  ";
        $tot_balance = "SELECT COUNT(*) as tot_balance from request_creation where (cus_status < 14 and cus_status >= 1 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and month(updated_date) = month('$month') and year(updated_date) = year('$month') ";
        $today_balance = "SELECT COUNT(*) as today_balance from request_creation where cus_status IN(1,10,11,12) and date(updated_date) = '$today'  ";
        $tot_cancel = "SELECT COUNT(*) as tot_cancel from request_creation where cus_status = 5 and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_cancel = "SELECT COUNT(*) as today_cancel from request_creation where cus_status = 5 and date(updated_date) = '$today' ";
        $tot_revoke = "SELECT COUNT(*) as tot_revoke from request_creation where cus_status = 9 and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_revoke = "SELECT COUNT(*) as today_revoke from request_creation where cus_status = 9 and date(updated_date) = '$today' ";
        $tot_new = "SELECT COUNT(*) as tot_new from request_creation req JOIN in_verification iv ON iv.req_id = req.req_id where (req.cus_status >= 1 and req.cus_status NOT IN(4,8) ) and  req.cus_data = 'New' and month(iv.created_date) = month('$month') and year(iv.created_date) = year('$month')";
        $today_new = "SELECT COUNT(*) as today_new from request_creation where cus_status = 1 and cus_data = 'New' and date(updated_date) = '$today' ";
        $tot_existing = "SELECT COUNT(*) as tot_existing from request_creation req JOIN in_verification iv ON iv.req_id = req.req_id where (req.cus_status >= 1 and req.cus_status NOT IN(4,8) ) and  req.cus_data = 'Existing' and month(iv.created_date) = month('$month') and year(iv.created_date) = year('$month')";
        $today_existing = "SELECT COUNT(*) as today_existing from request_creation where cus_status = 1 and cus_data = 'Existing' and date(updated_date) = '$today' ";

        if (empty($sub_area_list)) {
            $sub_area_list = $this->getUserGroupBasedSubArea($connect, $this->user_id);
        }

        $tot_in_ver .= " AND req.sub_area IN ($sub_area_list) ";
        $today_in_ver .= " AND sub_area IN ($sub_area_list) ";
        $tot_issue .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_issue .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $tot_balance .= " AND sub_area IN ($sub_area_list) ";
        $today_balance .= " AND sub_area IN ($sub_area_list) ";
        $tot_cancel .= " AND sub_area IN ($sub_area_list) ";
        $today_cancel .= " AND sub_area IN ($sub_area_list) ";
        $tot_revoke .= " AND sub_area IN ($sub_area_list) ";
        $today_revoke .= " AND sub_area IN ($sub_area_list) ";
        $tot_new .= " AND req.sub_area IN ($sub_area_list) ";
        $today_new .= " AND sub_area IN ($sub_area_list) ";
        $tot_existing .= " AND req.sub_area IN ($sub_area_list) ";
        $today_existing .= " AND sub_area IN ($sub_area_list) ";
        if (!empty($loan_category) && $loan_category != 0) {

            $tot_in_ver .= " AND req.loan_category = '$loan_category' ";
            $today_in_ver .= " AND loan_category = '$loan_category' ";
            $tot_issue .= " AND req.loan_category = '$loan_category'";
            $today_issue .= " AND req.loan_category = '$loan_category'";
            $tot_balance .= " AND loan_category = '$loan_category' ";
            $today_balance .= " AND loan_category = '$loan_category' ";
            $tot_cancel .= " AND loan_category = '$loan_category' ";
            $today_cancel .= " AND loan_category = '$loan_category' ";
            $tot_revoke .= " AND loan_category = '$loan_category' ";
            $today_revoke .= " AND loan_category = '$loan_category' ";
            $tot_new .= " AND req.loan_category = '$loan_category' ";
            $today_new .= " AND loan_category = '$loan_category' ";
            $tot_existing .= " AND req.loan_category = '$loan_category' ";
            $today_existing .= " AND loan_category = '$loan_category' ";
        }

        $tot_in_verQry = $connect->query($tot_in_ver);
        $today_in_verQry = $connect->query($today_in_ver);
        $tot_issueQry = $connect->query($tot_issue);
        $today_issueQry = $connect->query($today_issue);
        $tot_balanceQry = $connect->query($tot_balance);
        $today_balanceQry = $connect->query($today_balance);
        $tot_cancelQry = $connect->query($tot_cancel);
        $today_cancelQry = $connect->query($today_cancel);
        $tot_revokeQry = $connect->query($tot_revoke);
        $today_revokeQry = $connect->query($today_revoke);
        $tot_newQry = $connect->query($tot_new);
        $today_newQry = $connect->query($today_new);
        $tot_existingQry = $connect->query($tot_existing);
        $today_existingQry = $connect->query($today_existing);

        $response['tot_in_ver'] = $tot_in_verQry->fetch()['tot_in_ver'];
        $response['today_in_ver'] = $today_in_verQry->fetch()['tot_in_ver'];
        $response['tot_issue'] = $tot_issueQry->fetch()['tot_issue'];
        $response['today_issue'] = $today_issueQry->fetch()['today_issue'];
        $response['tot_balance'] = $tot_balanceQry->fetch()['tot_balance'];
        $response['today_balance'] = $today_balanceQry->fetch()['today_balance'];
        $response['tot_cancel'] = $tot_cancelQry->fetch()['tot_cancel'];
        $response['today_cancel'] = $today_cancelQry->fetch()['today_cancel'];
        $response['tot_revoke'] = $tot_revokeQry->fetch()['tot_revoke'];
        $response['today_revoke'] = $today_revokeQry->fetch()['today_revoke'];
        $response['tot_new'] = $tot_newQry->fetch()['tot_new'];
        $response['today_new'] = $today_newQry->fetch()['today_new'];
        $response['tot_existing'] = $tot_existingQry->fetch()['tot_existing'];
        $response['today_existing'] = $today_existingQry->fetch()['today_existing'];

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
        $stmt = $connect->prepare(" SELECT DISTINCT sub_area_id FROM area_group_mapping_sub_area
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
