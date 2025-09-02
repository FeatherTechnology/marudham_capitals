<?php
session_start();
class promotionListClass
{
    public $sub_area_list = array();
    public $accessType; 
    public function __construct($connect)
    {
        $userid = $_SESSION["userid"];

        if ($userid != 1) {  // super admin bypass
            $userQry = $connect->query("
            SELECT group_id, line_id, due_followup_lines, promotion_activity_mapping_access 
            FROM user 
            WHERE user_id = $userid
        ");
            $rowuser = $userQry->fetch();

              $this->accessType = $rowuser['promotion_activity_mapping_access'];
            $sub_area_ids = [];

            if ($this->accessType == 1) {
                // 🔹 Group-based access
                $group_ids = explode(',', $rowuser['group_id']);
                foreach ($group_ids as $group) {
                    $groupQry = $connect->query("SELECT sub_area_id FROM area_group_mapping WHERE map_id = $group");
                    if ($row_sub = $groupQry->fetch()) {
                        $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_sub['sub_area_id']));
                    }
                }
            } elseif ($this->accessType == 2) {
                // 🔹 Line-based access
                $line_ids = explode(',', $rowuser['line_id']);
                foreach ($line_ids as $line) {
                    $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping WHERE map_id = $line");
                    if ($row_line = $lineQry->fetch()) {
                        $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_line['sub_area_id']));
                    }
                }
            } elseif ($this->accessType == 3) {
                // 🔹 Due Followup-based access
                $due_ids = explode(',', $rowuser['due_followup_lines']);
                foreach ($due_ids as $due) {
                    $dueQry = $connect->query("SELECT area_id FROM area_duefollowup_mapping WHERE map_id = $due");
                    if ($row_due = $dueQry->fetch()) {
                        $sub_area_ids = array_merge($sub_area_ids, explode(',', $row_due['area_id']));
                    }
                }
            }
            // Remove duplicates and store final list
            $sub_area_ids = array_unique(array_filter($sub_area_ids));
            $this->sub_area_list = implode(',', $sub_area_ids);
        }
    }

    function getdetails($connect, $type)
    {
        $arr = array();
        $colName = ($this->accessType == 3)
            ? "cp.area_confirm_area"          // Due Followup
            : "cp.area_confirm_subarea";      // Group/Line
        if ($type == 'existing') {
            //only closed customers who dont have any loans in current.

            $sql = $connect->query("SELECT cs.cus_id,cs.consider_level,cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id WHERE cs.cus_sts >= '20' and $colName IN ($this->sub_area_list) and cs.closed_sts = 1 ");

            while ($row = $sql->fetch()) {

                $last_closed_date = date('Y-m-d', strtotime($row['updated_date']));

                $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
                if ($check_req->rowCount() == 0) {
                    $arr[] = array('cus_id' => $row['cus_id'], 'sub_status' => $row['consider_level'], 'last_updated_date' => $last_closed_date);
                }
            }
        } else {

                 $sql = $connect->query("
            SELECT req.*
            FROM request_creation req
            WHERE (req.cus_status >= 4 AND req.cus_status <= 9)
              AND (
                    " . ($this->accessType == 3 
                        ? "req.area" 
                        : "req.sub_area") . " IN ($this->sub_area_list)
                  OR 
                    " . ($this->accessType == 3 
                        ? "(SELECT area_confirm_area FROM acknowlegement_customer_profile WHERE req_id = req.req_id)" 
                        : "(SELECT area_confirm_subarea FROM customer_profile WHERE req_id = req.req_id)") . " IN ($this->sub_area_list)
              )
            GROUP BY req.cus_id
        ");
            while ($row = $sql->fetch()) {

                $last_updated_date = date('Y-m-d', strtotime($row['updated_date']));
                $last_closed_date = '';

                $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
                if ($check_req->rowCount() == 0) {
                    $arr[] = array('cus_id' => $row['cus_id'], 'sub_status' => $row['cus_status'], 'last_updated_date' => $last_updated_date);
                }
            }
        }
        return $arr;
    }

    function getCustomerPromotionType($connect, $cus_id)
    {
        $response = 'Loan Progress';

        $sql = $connect->query("SELECT cs.cus_id,cs.consider_level,cs.updated_date FROM closed_status cs JOIN acknowlegement_customer_profile cp ON cs.req_id = cp.req_id WHERE cs.cus_sts >= '20' and cs.cus_id = '$cus_id' ");

        while ($row = $sql->fetch()) {

            $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
            if ($check_req->rowCount() == 0) {
                $response = 'Existing';
            }
        }

        $sql = $connect->query("SELECT req.* FROM request_creation req WHERE (req.cus_status >= 4 AND req.cus_status <= 9) and req.cus_id = '$cus_id' ");
        while ($row = $sql->fetch()) {

            $check_req = $connect->query("SELECT req_id from request_creation where (cus_status NOT between 4 and 9) and cus_status < 20 and cus_id = '" . $row['cus_id'] . "' ORDER By req_id DESC LIMIT 1 ");
            if ($check_req->rowCount() == 0) {
                $response = 'Repromotion';
            }
        }
        return $response;
    }
}
