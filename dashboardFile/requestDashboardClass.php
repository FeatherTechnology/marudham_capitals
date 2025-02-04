<?php

class RequestClass
{
    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    function getRequestCounts($connect)
    {

        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) && $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];

        //all the above queries without $connect->query(). just query as string like $req_query = "SELECT count(*) as tot_req FROM request_creation WHERE insert_login_id = '$this->user_id' "
        $req_query = "SELECT count(*) as tot_req FROM request_creation WHERE 1 and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $issue_query = "SELECT count(*) as tot_issue FROM request_creation WHERE cus_status >= 14 and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $balance_query = "SELECT count(*) as tot_balance FROM request_creation WHERE (cus_status < 14 and cus_status NOT IN(4, 5, 6, 7, 8, 9) ) and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $cancel_query = "SELECT count(*) as tot_cancel FROM request_creation WHERE cus_status = 4 and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $revoke_query = "SELECT count(*) as tot_revoke FROM request_creation WHERE cus_status = 8 and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $new_query = "SELECT count(*) as tot_new FROM request_creation WHERE cus_data = 'New' and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $existing_query = "SELECT count(*) as tot_existing FROM request_creation WHERE cus_data = 'Existing' and month(created_date) = month('$month') and year(created_date) = year('$month')";
        $today_req_query = "SELECT count(*) as today_req FROM request_creation WHERE date(created_date) = '$today' ";
        $today_issue_query = "SELECT count(*) as today_issue FROM request_creation WHERE cus_status >= 14 AND date(created_date) = '$today' ";
        $today_balance_query = "SELECT count(*) as today_balance FROM request_creation WHERE (cus_status < 14 and cus_status NOT IN(4, 5, 6, 7, 8, 9)) AND date(created_date) = '$today'";
        $today_cancel_query = "SELECT count(*) as today_cancel FROM request_creation WHERE cus_status = 4 AND date(created_date) = '$today' ";
        $today_revoke_query = "SELECT count(*) as today_revoke FROM request_creation WHERE cus_status = 8 AND date(created_date) = '$today' ";
        $today_new_query = "SELECT count(*) as today_new FROM request_creation WHERE cus_data = 'New' AND date(created_date) = '$today' ";
        $today_existing_query = "SELECT count(*) as today_existing FROM request_creation WHERE cus_data = 'Existing' AND date(created_date) = '$today' ";

        //now i shoud add sub_area IN ($sub_area_list) in where clause to the above queries

        if (!empty($sub_area_list)) {
            //if the sub area list is empty then no need to add it to the query
            $req_query .= " AND sub_area IN ($sub_area_list) ";
            $issue_query .= " AND sub_area IN ($sub_area_list) ";
            $balance_query .= " AND sub_area IN ($sub_area_list) ";
            $cancel_query .= " AND sub_area IN ($sub_area_list) ";
            $revoke_query .= " AND sub_area IN ($sub_area_list) ";
            $new_query .= " AND sub_area IN ($sub_area_list) ";
            $existing_query .= " AND sub_area IN ($sub_area_list) ";
            $today_req_query .= " AND sub_area IN ($sub_area_list) ";
            $today_issue_query .= " AND sub_area IN ($sub_area_list) ";
            $today_balance_query .= " AND sub_area IN ($sub_area_list) ";
            $today_cancel_query .= " AND sub_area IN ($sub_area_list) ";
            $today_revoke_query .= " AND sub_area IN ($sub_area_list) ";
            $today_new_query .= " AND sub_area IN ($sub_area_list) ";
            $today_existing_query .= " AND sub_area IN ($sub_area_list) ";
        } else {
            //add insert_login_id = '$this->user_id' in where clause to the above queries
            $req_query .= " AND insert_login_id = '$this->user_id' ";
            $issue_query .= " AND insert_login_id = '$this->user_id' ";
            $balance_query .= " AND insert_login_id = '$this->user_id' ";
            $cancel_query .= " AND insert_login_id = '$this->user_id' ";
            $revoke_query .= " AND insert_login_id = '$this->user_id' ";
            $new_query .= " AND insert_login_id = '$this->user_id' ";
            $existing_query .= " AND insert_login_id = '$this->user_id' ";
            $today_req_query .= " AND insert_login_id = '$this->user_id' ";
            $today_issue_query .= " AND insert_login_id = '$this->user_id' ";
            $today_balance_query .= " AND insert_login_id = '$this->user_id' ";
            $today_cancel_query .= " AND insert_login_id = '$this->user_id' ";
            $today_revoke_query .= " AND insert_login_id = '$this->user_id' ";
            $today_new_query .= " AND insert_login_id = '$this->user_id' ";
            $today_existing_query .= " AND insert_login_id = '$this->user_id' ";
        }
        //now run the above queries and store it like before in reponse variable
        $qry = $connect->query($req_query);
        $response['tot_req'] = $qry->fetch()['tot_req'];
        $qry = $connect->query($today_req_query);
        $response['today_req'] = $qry->fetch()['today_req'];
        $qry = $connect->query($issue_query);
        $response['tot_issue'] = $qry->fetch()['tot_issue'];
        $qry = $connect->query($today_issue_query);
        $response['today_issue'] = $qry->fetch()['today_issue'];
        $qry = $connect->query($balance_query);
        $response['tot_balance'] = $qry->fetch()['tot_balance'];
        $qry = $connect->query($today_balance_query);
        $response['today_balance'] = $qry->fetch()['today_balance'];
        $qry = $connect->query($cancel_query);
        $response['tot_cancel'] = $qry->fetch()['tot_cancel'];
        $qry = $connect->query($today_cancel_query);
        $response['today_cancel'] = $qry->fetch()['today_cancel'];
        $qry = $connect->query($revoke_query);
        $response['tot_revoke'] = $qry->fetch()['tot_revoke'];
        $qry = $connect->query($today_revoke_query);
        $response['today_revoke'] = $qry->fetch()['today_revoke'];
        $qry = $connect->query($new_query);
        $response['tot_new'] = $qry->fetch()['tot_new'];
        $qry = $connect->query($today_new_query);
        $response['today_new'] = $qry->fetch()['today_new'];
        $qry = $connect->query($existing_query);
        $response['tot_existing'] = $qry->fetch()['tot_existing'];
        $qry = $connect->query($today_existing_query);
        $response['today_existing'] = $qry->fetch()['today_existing'];

        return $response;
    }
}
