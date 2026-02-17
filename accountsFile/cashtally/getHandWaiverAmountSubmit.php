<?php
include "../../ajaxconfig.php";

$branch_ids = $_POST['branch_id'] ?? '';
$op_date = date('Y-m-d', strtotime($_POST['op_date']. ' +1 day'));

$qry = $connect->query("
WITH 
    user_waiver AS ( 
        SELECT c.insert_login_id AS user_id, SUM(c.pre_close_waiver) AS waiver_amt FROM collection c WHERE c.coll_mode = '1' AND c.branch IN ($branch_ids) AND c.coll_date < '$op_date' AND c.pre_close_waiver > 0 GROUP BY c.insert_login_id 
    ), 
    user_hand AS ( 
        SELECT hw.user_id, SUM(hw.rec_amt) AS rec_amt FROM ct_hand_waiver hw WHERE hw.branch_id IN ($branch_ids) AND hw.created_date < '$op_date' GROUP BY hw.user_id
    ) 
SELECT SUM((IFNULL(uw.waiver_amt, 0) - IFNULL(uh.rec_amt, 0))) AS waiver_amt 
FROM user_waiver uw
LEFT JOIN user_hand uh ON uh.user_id = uw.user_id 
WHERE 
	uw.user_id NOT IN (1) 
    AND ( (IFNULL(uw.waiver_amt, 0) - IFNULL(uh.rec_amt, 0)) > 0 )");

$response = $qry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($response);
?>