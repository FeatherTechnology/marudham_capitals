<?php
include "../ajaxconfig.php";

$countListArr = array();

if (isset($_POST['areaid']) && !empty($_POST['areaid'])) {
    $areaid = $_POST['areaid'];

    // Query 1: Total number of loans
    $stmt = $connect->query("SELECT COUNT(ii.loan_id) AS loanCount FROM in_issue ii JOIN acknowlegement_customer_profile acp ON ii.req_id = acp.req_id WHERE ii.cus_status BETWEEN 14 AND 17 AND acp.area_confirm_area IN ($areaid)");
    $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
    $countListArr['loan_count'] = $row1['loanCount'];

    // Query 2: Count of distinct customers
    $stmt1 = $connect->query("SELECT COUNT(DISTINCT ii.cus_id) AS cusCount FROM in_issue ii JOIN acknowlegement_customer_profile acp ON ii.req_id = acp.req_id WHERE ii.cus_status BETWEEN 14 AND 17 AND acp.area_confirm_area IN ($areaid)");
    $row2 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $countListArr['cus_count'] = $row2['cusCount'];
}

echo json_encode($countListArr);
?>
