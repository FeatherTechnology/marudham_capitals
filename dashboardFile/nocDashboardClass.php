<?php


class NocClass
{
    public $user_id, $date_limit;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getNOCCounts($connect)
    {
        $response = array();
        $today = date('Y-m-d');
        $month = (isset($_POST['month']) || $_POST['month'] != '') ? date('Y-m-01', strtotime($_POST['month'])) : date('Y-m-01');
        $sub_area_list = $_POST['sub_area_list'];

        $tot_noc = "SELECT COUNT(*) as tot_noc FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 21 ";
        $noc_issueqry = "SELECT req.req_id FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status = 21 ";
        $month_noc = "SELECT COUNT(*) as month_noc FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 21 AND month(req.updated_date) = month('$month') and year(req.updated_date) = year('$month') ";
        $month_noc_bal = "SELECT req.req_id FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 21 AND month(req.updated_date) = month('$month') and year(req.updated_date) = year('$month') ";
        $month_noc_issueqry = "SELECT req.req_id FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status = 21 ";
        $today_noc = "SELECT COUNT(*) as today_noc FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status >= 21 AND date(req.updated_date) = '$month' ";
        $today_noc_issueqry = "SELECT req.req_id FROM request_creation req JOIN acknowlegement_customer_profile cp ON cp.req_id = req.req_id WHERE req.cus_status = 21  ";

        if (empty($sub_area_list)) {
            $sub_area_list = $this->getUserGroupBasedSubArea($connect, $this->user_id);
        }

        $tot_noc .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $noc_issueqry .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $month_noc .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $month_noc_bal .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $month_noc_issueqry .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $today_noc .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";
        $today_noc_issueqry .= " AND ( CASE WHEN cp.area_confirm_subarea IS NOT NULL THEN cp.area_confirm_subarea IN ($sub_area_list) ELSE TRUE END ) ";


        $tot_nocQry = $connect->query($tot_noc);
        $this->date_limit = '';
        $tot_noc_issued = $this->fetchdata($connect, $noc_issueqry);
        $month_nocQry = $connect->query($month_noc);
        $this->date_limit = 'month';
        $month_noc_bal = $this->fetchdata($connect, $month_noc_bal);
        $this->date_limit = 'month';
        $month_noc_issued = $this->fetchdata($connect, $month_noc_issueqry);
        $today_nocQry = $connect->query($today_noc);
        $this->date_limit = 'today';
        $today_noc_issued = $this->fetchdata($connect, $today_noc_issueqry);


        $response['tot_noc'] = $tot_nocQry->fetch()['tot_noc'];
        $response['tot_noc_issued'] = $tot_noc_issued;
        $response['month_noc'] = $month_nocQry->fetch()['month_noc'];
        $response['month_noc_issued'] = $month_noc_issued;
        $response['month_noc_bal'] = $response['month_noc'] - $month_noc_bal;
        $response['today_noc'] = $today_nocQry->fetch()['today_noc'];
        $response['today_noc_issued'] = $today_noc_issued;

        return $response;
    }
    function fetchdata($connect, $noc_issueqry)
    {
        //this function will get each request id and check whether each request has any pending noc using getNocDocStatus method
        $noc_issueqryQry = $connect->query($noc_issueqry);
        $req_id_arr = array();
        while ($row = $noc_issueqryQry->fetch()) {
            $req_id_arr[] = $row['req_id'];
        }
        $noc_status = array_map(array($this, 'getNocDocStatus'), $req_id_arr); //uses callback function of the class to process each data if array
        // print_r($noc_status);
        $res = array_sum($noc_status); //add all the numbers inside the array
        return $res;
    }
    public function getNocDocStatus($req_id)
    {

        $nocstatus = 1;
        $connect = $GLOBALS['connect'];
        $dater = $this->date_limit;
        $today = date('Y-m-d');
        //noc status will be 1 refering that noc is completed, and 0 means noc pending
        //now check all the documents tables to check whether the passed req_id having noc_status 0, means noc not completed for that req_id
        //if in all queries the noc status count is set to 1 then the count inside the query will return null or 0
        //if count is >0 then noc status is completed

        $qry = $connect->query("SELECT COUNT(*) as `count` FROM (
            SELECT 1 FROM `signed_doc_info` a JOIN signed_doc b ON a.id = b.signed_doc_id WHERE b.req_id = $req_id AND b.noc_given != '1'
            UNION ALL
            SELECT 1 FROM `cheque_no_list` a JOIN cheque_info b ON a.cheque_table_id = b.id WHERE a.req_id = $req_id AND a.noc_given != '1'
            UNION ALL
            SELECT 1 FROM `acknowlegement_documentation` WHERE req_id = $req_id AND ((mortgage_process = 0 AND mortgage_process_noc != '1') OR (mortgage_document = 0 AND mortgage_document_noc != '1'))
            UNION ALL
            SELECT 1 FROM `acknowlegement_documentation` WHERE req_id = $req_id AND ((endorsement_process = 0 AND endorsement_process_noc != '1') OR (en_RC = 0 AND en_RC_noc != '1') OR (en_Key = 0 AND en_Key_noc != '1'))
            UNION ALL
            SELECT 1 FROM `gold_info` WHERE req_id = $req_id AND noc_given != '1'
            UNION ALL
            SELECT 1 FROM document_info ac LEFT JOIN verification_family_info fam ON ac.relation_name = fam.id WHERE ac.req_id = $req_id AND ac.doc_info_upload_noc != '1'
        ) AS combined ");
        if ($qry->fetch()['count'] > 0) {
            $nocstatus = 0;
        }
        // echo $req_id . '-' .$nocstatus.'|';

        if ($nocstatus == 1) {
            //below query will fetch the latest updated noc date from all tables if the current request id's noc is completed
            $qry = $connect->query("SELECT * from (
                SELECT b.noc_date FROM `signed_doc_info` a JOIN signed_doc b ON a.id = b.signed_doc_id WHERE b.req_id = $req_id
                UNION ALL
                SELECT a.noc_date FROM `cheque_no_list` a JOIN cheque_info b ON a.cheque_table_id = b.id WHERE a.req_id = $req_id
                UNION ALL
                SELECT mort_noc_date FROM `acknowlegement_documentation` `ad1` WHERE ad1.req_id = $req_id
                UNION ALL
                SELECT mort_doc_noc_date FROM `acknowlegement_documentation` `ad2` WHERE ad2.req_id = $req_id
                UNION ALL
                SELECT endor_noc_date FROM `acknowlegement_documentation` `ad3` WHERE ad3.req_id = $req_id
                UNION ALL
                SELECT en_rc_noc_date FROM `acknowlegement_documentation` `ad4` WHERE ad4.req_id = $req_id
                UNION ALL
                SELECT en_key_noc_date FROM `acknowlegement_documentation` `ad5` WHERE ad5.req_id = $req_id
                UNION ALL
                SELECT noc_date FROM `gold_info` gi WHERE gi.req_id = $req_id
                UNION ALL
                SELECT ac.noc_date FROM document_info ac LEFT JOIN verification_family_info fam ON ac.relation_name = fam.id WHERE ac.req_id = $req_id 
            ) as noc_date ORDER BY noc_date DESC LIMIT 1");

            $noc_date = $qry->fetch()['noc_date'];

            if ($noc_date == NULL) {
                //if the noc_date is null then there is two posibility
                //1 is the request may not issued single noc yet
                //2 is the request may not have documents given entirely
                //so we need to check whether any of the document given by the request or no
                //if not then no need to compare dates
                $qry = $connect->query("SELECT COUNT(*) as `count` FROM (
                    SELECT 1 FROM `signed_doc_info` a JOIN signed_doc b ON a.id = b.signed_doc_id WHERE b.req_id = $req_id
                    UNION ALL
                    SELECT 1 FROM `cheque_no_list` a JOIN cheque_info b ON a.cheque_table_id = b.id WHERE a.req_id = $req_id
                    UNION ALL
                    SELECT 1 FROM `acknowlegement_documentation` WHERE req_id = $req_id AND ((mortgage_process = 0 ) OR (mortgage_document = 0 ))
                    UNION ALL
                    SELECT 1 FROM `acknowlegement_documentation` WHERE req_id = $req_id AND ((endorsement_process = 0 ) OR (en_RC = 0 ) OR (en_Key = 0 ))
                    UNION ALL
                    SELECT 1 FROM `gold_info` WHERE req_id = $req_id 
                    UNION ALL
                    SELECT 1 FROM document_info ac LEFT JOIN verification_family_info fam ON ac.relation_name = fam.id WHERE ac.req_id = $req_id 
                ) AS combined ");
                $document_count = $qry->fetch()['count'];
            } else {
                $document_count = 1;
            }
            if ($document_count > 0) {
                //if the request has documents given then compare the dates
                //else the request is not having any document to issue so it will marked as issued

                if ($dater == 'month') {
                    //check for the call type and set the limit checker accordingly

                    $noc_date = date('Y-m', strtotime($noc_date));

                    //if the date is not in current month, then that noc issue cannot be calculated
                    if ($noc_date != date('Y-m', strtotime($today))) {
                        $nocstatus = 0; //not completed
                    }
                } elseif ($dater == 'today') {
                    $noc_date = date('Y-m-d', strtotime($noc_date));
                    //if the date is not in current date, then that noc issue cannot be calculated
                    if ($noc_date != $today) {
                        $nocstatus = 0; //not completed
                    }
                }
            } else {
                //if no doc submitted by the reqest then noc moved date will be tha noc date of that request
                //so check the noc moved date and compare with the current date to check whether noc is completed or not
                $qry = $connect->query("SELECT updated_date FROM request_creation where req_id = $req_id ");
                $noc_moved_date = $qry->fetch()['updated_date'];
                if ($dater == 'month') {
                    $noc_moved_date = date('Y-m', strtotime($noc_moved_date));
                    //if the date is not in current month, then that noc issue cannot be calculated
                    if ($noc_moved_date != date('Y-m', strtotime($today))) {
                        $nocstatus = 0; //not completed
                    }
                    // echo $req_id . '-' . $noc_date . '|';
                } elseif ($dater == 'today') {
                    $noc_moved_date = date('Y-m-d', strtotime($noc_moved_date));
                    //if the date is not in current date, then that noc issue cannot be calculated
                    if ($noc_date != $today) {
                        $nocstatus = 0; //not completed
                    }
                }
            }
        }
        return $nocstatus;
    }
    private function getUserGroupBasedSubArea($connect, $user_id)
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
