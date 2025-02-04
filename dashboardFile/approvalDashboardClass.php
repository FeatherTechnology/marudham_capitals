<?php

class approvalClass
{
    public $user_id;
    function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    function getApprovalCounts($connect)
    {
        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) && $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];

        $tot_in_app = "SELECT COUNT(*) as tot_in_app FROM request_creation where ( cus_status >= 2 and cus_status NOT IN(4, 5, 8, 9, 10, 11, 12) ) and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_in_app = "SELECT COUNT(*) as today_in_app FROM request_creation where cus_status = 2 and date(updated_date) = '$today' ";
        $tot_issue = "SELECT COUNT(*) as tot_issue FROM request_creation req JOIN customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 14 and month(req.updated_date) = month('$month') and year(req.updated_date) = year('$month')";
        $today_issue = "SELECT COUNT(*) as today_issue FROM request_creation req JOIN customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 14 and date(req.updated_date) = '$today' ";
        $tot_app_bal = "SELECT COUNT(*) as tot_app_bal FROM request_creation where (cus_status < 14 and cus_status >= 2 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_app_bal = "SELECT COUNT(*) as today_app_bal FROM request_creation where cus_status = 2 and date(updated_date) = '$today' ";
        $tot_cancel = "SELECT COUNT(*) as tot_cancel from request_creation where cus_status = 6 and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_cancel = "SELECT COUNT(*) as today_cancel from request_creation where cus_status = 6 and date(updated_date) = '$today' ";
        $tot_new = "SELECT COUNT(*) as tot_new from request_creation where (cus_status < 14 and cus_status >= 2 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and cus_data = 'New' and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_new = "SELECT COUNT(*) as today_new from request_creation where cus_status = 2 and cus_data = 'New' and date(updated_date) = '$today' ";
        $tot_existing = "SELECT COUNT(*) as tot_existing from request_creation where (cus_status < 14 and cus_status >= 2 and cus_status NOT IN(4, 5, 6, 7, 8, 9, 10, 11, 12) ) and cus_data = 'Existing' and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_existing = "SELECT COUNT(*) as today_existing from request_creation where cus_status = 2 and cus_data = 'Existing' and date(updated_date) = '$today' ";

        if (empty($sub_area_list)) {
            $sub_area_list = $this->getUserGroupBasedSubArea($connect, $this->user_id);
        }

        $tot_in_app .= " AND sub_area IN ($sub_area_list) ";
        $today_in_app .= " AND sub_area IN ($sub_area_list) ";
        $tot_issue .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_issue .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $tot_app_bal .= " AND sub_area IN ($sub_area_list) ";
        $today_app_bal .= " AND sub_area IN ($sub_area_list) ";
        $tot_cancel .= " AND sub_area IN ($sub_area_list) ";
        $today_cancel .= " AND sub_area IN ($sub_area_list) ";
        $tot_new .= " AND sub_area IN ($sub_area_list) ";
        $today_new .= " AND sub_area IN ($sub_area_list) ";
        $tot_existing .= " AND sub_area IN ($sub_area_list) ";
        $today_existing .= " AND sub_area IN ($sub_area_list) ";


        $tot_in_appQry = $connect->query($tot_in_app);
        $today_in_appQry = $connect->query($today_in_app);
        $tot_issueQry = $connect->query($tot_issue);
        $today_issueQry = $connect->query($today_issue);
        $tot_app_balQry = $connect->query($tot_app_bal);
        $today_app_balQry = $connect->query($today_app_bal);
        $tot_cancelQry = $connect->query($tot_cancel);
        $today_cancelQry = $connect->query($today_cancel);
        $tot_newQry = $connect->query($tot_new);
        $today_newQry = $connect->query($today_new);
        $tot_existingQry = $connect->query($tot_existing);
        $today_existingQry = $connect->query($today_existing);


        $response['tot_in_app'] = $tot_in_appQry->fetch()['tot_in_app'];
        $response['today_in_app'] = $today_in_appQry->fetch()['today_in_app'];
        $response['tot_issue'] = $tot_issueQry->fetch()['tot_issue'];
        $response['today_issue'] = $today_issueQry->fetch()['today_issue'];
        $response['tot_app_bal'] = $tot_app_balQry->fetch()['tot_app_bal'];
        $response['today_app_bal'] = $today_app_balQry->fetch()['today_app_bal'];
        $response['tot_cancel'] = $tot_cancelQry->fetch()['tot_cancel'];
        $response['today_cancel'] = $today_cancelQry->fetch()['today_cancel'];
        $response['tot_revoke'] = 0;
        $response['today_revoke'] = 0;
        $response['tot_new'] = $tot_newQry->fetch()['tot_new'];
        $response['today_new'] = $today_newQry->fetch()['today_new'];
        $response['tot_existing'] = $tot_existingQry->fetch()['tot_existing'];
        $response['today_existing'] = $today_existingQry->fetch()['today_existing'];


        return $response;
    }

    function getUserGroupBasedSubArea($connect, $user_id)
    {
        $sub_area_list = array();

        $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $user_id ");
        while ($rowuser = $userQry->fetch()) {
            $group_id = $rowuser['group_id'];
        }
        $group_id = explode(',', $group_id);
        foreach ($group_id as $group) {
            $groupQry = $connect->query("SELECT * FROM area_group_mapping where map_id = $group ");
            $row_sub = $groupQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);

        return $sub_area_list;
    }
}
