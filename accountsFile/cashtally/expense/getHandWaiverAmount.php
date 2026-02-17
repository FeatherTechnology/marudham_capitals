<?php
include "../../../ajaxconfig.php";

$branch_ids = $_POST['branch_id'] ?? '';
$op_date = date('Y-m-d', strtotime($_POST['op_date']. ' +1 day'));

//cat = 16 - Waiver.
$qry = $connect->query("
WITH hand_waiver AS ( 
    SELECT IFNULL(SUM(hw.rec_amt),0) AS rec_amt 
    FROM ct_hand_waiver hw 
    WHERE hw.created_date < '$op_date'
), 
ctdbhexp AS ( 
    SELECT IFNULL(SUM(cdh.amt),0) AS amt 
    FROM ct_db_hexpense cdh 
    WHERE cdh.cat = 16 AND cdh.created_date < '$op_date' 
) 
SELECT 
    (hw.rec_amt - cdhe.amt) AS balance_waiver
FROM hand_waiver hw
CROSS JOIN ctdbhexp cdhe");

$response = $qry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($response);
?>