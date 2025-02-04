<?php

class LoanIssueClass
{
    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    function getLoanIssueCounts($connect)
    {

        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) && $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];

        $tot_li = "SELECT COUNT(*) as tot_li FROM request_creation where cus_status >= 13 and month(updated_date) = month('$month') and year(updated_date) = year('$month') ";
        $today_li = "SELECT COUNT(*) as today_li FROM request_creation where cus_status = 13 and date(updated_date) = '$today' ";
        $tot_li_issue = "SELECT COUNT(*) as tot_li_issue FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 14 and month(req.updated_date) = month('$month') and year(req.updated_date) = year('$month')";
        $today_li_issue = "SELECT COUNT(*) as today_li_issue FROM request_creation req JOIN customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 14 and date(req.updated_date) = '$today' ";
        $tot_li_bal = "SELECT COUNT(*) as tot_li_bal FROM request_creation where cus_status = 13 and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_li_bal = "SELECT COUNT(*) as today_li_bal FROM request_creation where cus_status = 13 and date(updated_date) = '$today' ";
        $tot_cash = "SELECT SUM(li.cash) as tot_cash from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where li.cash IS NOT NULL and month(li.created_date) = month('$month') and year(li.created_date) = year('$month')";
        $today_cash = "SELECT SUM(li.cash) as today_cash from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where li.cash IS NOT NULL and date(li.created_date) = '$today' ";
        $tot_cheque = "SELECT SUM(li.cheque_value) as tot_cheque from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where li.cheque_value IS NOT NULL and month(li.created_date) = month('$month') and year(li.created_date) = year('$month')";
        $today_cheque = "SELECT SUM(li.cheque_value) as today_cheque from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where li.cheque_value IS NOT NULL and date(li.created_date) = '$today' ";
        $tot_transaction = "SELECT SUM(li.transaction_value) as tot_transaction from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where li.transaction_value IS NOT NULL and month(li.created_date) = month('$month') and year(li.created_date) = year('$month')";
        $today_transaction = "SELECT SUM(li.transaction_value) as today_transaction from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where li.transaction_value IS NOT NULL and date(li.created_date) = '$today' ";
        $tot_new = "SELECT COUNT(*) as tot_new from request_creation where cus_status = 13 and cus_data = 'New' and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_new = "SELECT COUNT(*) as today_new from request_creation where cus_status = 13 and cus_data = 'New' and date(updated_date) = '$today' ";
        $tot_existing = "SELECT COUNT(*) as tot_existing from request_creation where cus_status = 13 and cus_data = 'Existing' and month(updated_date) = month('$month') and year(updated_date) = year('$month')";
        $today_existing = "SELECT COUNT(*) as today_existing from request_creation where cus_status = 13 and cus_data = 'Existing' and date(updated_date) = '$today' ";
        $today_li_amt = "SELECT COALESCE(SUM(lc.net_cash_cal),0) as today_li_amt FROM request_creation req JOIN acknowlegement_loan_calculation lc ON lc.req_id = req.req_id LEFT JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status = 13 and date(req.updated_date) = '$today' ";
        $today_issued_amt = "SELECT COALESCE(SUM(li.cash + li.cheque_value + li.transaction_value),0) as today_issued_amt from loan_issue li JOIN acknowlegement_customer_profile cp ON cp.req_id = li.req_id where date(li.created_date) = '$today' ";

        if (empty($sub_area_list)) {
            $sub_area_list = $this->getUserGroupBasedSubArea($connect, $this->user_id);
        }

        $tot_li .= " AND sub_area IN ($sub_area_list) ";
        $today_li .= " AND sub_area IN ($sub_area_list) ";
        $tot_li_issue .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_li_issue .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $tot_li_bal .= " AND sub_area IN ($sub_area_list) ";
        $today_li_bal .= " AND sub_area IN ($sub_area_list) ";
        $tot_new .= " AND sub_area IN ($sub_area_list) ";
        $tot_cash .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_cash .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $tot_cheque .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_cheque .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $tot_transaction .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_transaction .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_new .= " AND sub_area IN ($sub_area_list) ";
        $tot_existing .= " AND sub_area IN ($sub_area_list) ";
        $today_existing .= " AND sub_area IN ($sub_area_list) ";
        $today_li_amt .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";
        $today_issued_amt .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END )";


        $tot_liQry = $connect->query($tot_li);
        $today_liQry = $connect->query($today_li);
        $tot_li_issueQry = $connect->query($tot_li_issue);
        $today_li_issueQry = $connect->query($today_li_issue);
        $tot_li_balQry = $connect->query($tot_li_bal);
        $today_li_balQry = $connect->query($today_li_bal);
        $tot_cashQry = $connect->query($tot_cash);
        $today_cashQry = $connect->query($today_cash);
        $tot_chequeQry = $connect->query($tot_cheque);
        $today_chequeQry = $connect->query($today_cheque);
        $tot_transactionQry = $connect->query($tot_transaction);
        $today_transactionQry = $connect->query($today_transaction);
        $tot_newQry = $connect->query($tot_new);
        $today_newQry = $connect->query($today_new);
        $tot_existingQry = $connect->query($tot_existing);
        $today_existingQry = $connect->query($today_existing);

        $today_li_amtQry = $connect->query($today_li_amt);
        $today_issued_amtQry = $connect->query($today_issued_amt);


        $response['tot_li'] = $tot_liQry->fetch()['tot_li'];
        $response['today_li'] = $today_liQry->fetch()['today_li'];
        $response['tot_li_issue'] = $tot_li_issueQry->fetch()['tot_li_issue'];
        $response['today_li_issue'] = $today_li_issueQry->fetch()['today_li_issue'];
        $response['tot_li_bal'] = $tot_li_balQry->fetch()['tot_li_bal'];
        $response['today_li_bal'] = $today_li_balQry->fetch()['today_li_bal'];
        $response['tot_cash'] = $tot_cashQry->fetch()['tot_cash'] ?? 0;
        $response['today_cash'] = $today_cashQry->fetch()['today_cash'] ?? 0;
        $response['tot_cheque'] = $tot_chequeQry->fetch()['tot_cheque'] ?? 0;
        $response['today_cheque'] = $today_chequeQry->fetch()['today_cheque'] ?? 0;
        $response['tot_transaction'] = $tot_transactionQry->fetch()['tot_transaction'] ?? 0;
        $response['today_transaction'] = $today_transactionQry->fetch()['today_transaction'] ?? 0;
        $response['tot_new'] = $tot_newQry->fetch()['tot_new'];
        $response['today_new'] = $today_newQry->fetch()['today_new'];
        $response['tot_existing'] = $tot_existingQry->fetch()['tot_existing'];
        $response['today_existing'] = $today_existingQry->fetch()['today_existing'];

        $response['today_li_amt'] = $today_li_amtQry->fetch()['today_li_amt'];
        $response['today_issued_amt'] = $today_issued_amtQry->fetch()['today_issued_amt'];



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
