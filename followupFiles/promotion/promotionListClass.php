<?php
session_start();
class promotionListClass
{
    public $sub_area_list = array();
    public $accessType;
    public function __construct($connect)
    {
        $userid = $_SESSION["userid"] ?? 0;

        // Super admin bypass
        if ($userid == 1) {
            $this->sub_area_list = '';
            return;
        }

        // Fetch user access details
        $stmt = $connect->prepare("SELECT group_id, line_id, due_followup_lines, promotion_activity_mapping_access FROM user WHERE user_id = ?");
        $stmt->execute([$userid]);
        $rowuser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$rowuser) {
            $this->sub_area_list = '';
            return;
        }

        $this->accessType = (int)$rowuser['promotion_activity_mapping_access'];
        $ids = [];
        $table = '';
        $column = '';
        $selectColumn = '';

        /* =====================================Decide mapping table based on access===================================== */
        if ($this->accessType == 1) {
            // Group-based
            $ids = array_filter(explode(',', $rowuser['group_id']));
            $table = 'area_group_mapping_sub_area';
            $column = 'group_map_id';
            $selectColumn = 'sub_area_id';
        } elseif ($this->accessType == 2) {
            // Line-based
            $ids = array_filter(explode(',', $rowuser['line_id']));
            $table = 'area_line_mapping_sub_area';
            $column = 'line_map_id';
            $selectColumn = 'sub_area_id';
        } elseif ($this->accessType == 3) {
            // Due-followup based
            $ids = array_filter(explode(',', $rowuser['due_followup_lines']));
            $table = 'area_duefollowup_mapping_area';
            $column = 'duefollowup_map_id';
            $selectColumn = 'area_id';
        }

        if (empty($ids)) {
            $this->sub_area_list = '';
            return;
        }

        // Build placeholders
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Single optimized query (NO LOOP)
        $sql = "SELECT DISTINCT $selectColumn FROM $table WHERE $column IN ($placeholders)";

        $stmt = $connect->prepare($sql);
        $stmt->execute(array_map('intval', $ids));

        // Fetch directly as array
        $sub_area_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Final clean list
        $this->sub_area_list = implode(',', array_unique($sub_area_ids));
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
