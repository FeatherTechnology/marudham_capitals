<?php
include "../ajaxconfig.php";

$countListArr = array();

if (isset($_POST['areaid']) && !empty($_POST['areaid']) && isset($_POST['loanCatId']) && !empty($_POST['loanCatId']) && isset($_POST['subStatus']) && !empty($_POST['subStatus'])) {
    $areaid = $_POST['areaid'];
    $loanCatId = $_POST['loanCatId'];
    $subStatus = $_POST['subStatus'];
    $mapId = $_POST['mapId'];

    // Query 1: Total number of loans
    $stmt = $connect->query(" SELECT COUNT(ii.loan_id) AS loanCount FROM in_issue ii JOIN acknowlegement_customer_profile acp ON ii.req_id = acp.req_id JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id JOIN customer_status cs ON ii.req_id = cs.req_id JOIN area_list_creation al ON acp.area_confirm_area = al.area_id JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id) WHERE ii.cus_status BETWEEN 14 AND 17 AND acp.area_confirm_area IN ($areaid) AND alc.loan_category = $loanCatId AND FIND_IN_SET(cs.sub_status, '$subStatus') and alm.map_id IN ($mapId) ");
    $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
    $countListArr['loan_count'] = $row1['loanCount'];


    // Query 2: Count of distinct customers
    $stmt1 = $connect->query("SELECT COUNT(DISTINCT ii.cus_id) AS cusCount FROM in_issue ii JOIN acknowlegement_customer_profile acp ON ii.req_id = acp.req_id JOIN acknowlegement_loan_calculation alc ON ii.req_id = alc.req_id JOIN customer_status cs ON ii.req_id = cs.req_id JOIN area_list_creation al ON acp.area_confirm_area = al.area_id JOIN area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id) WHERE ii.cus_status BETWEEN 14 AND 17 AND acp.area_confirm_area IN ($areaid) AND alc.loan_category = $loanCatId AND FIND_IN_SET(cs.sub_status, '$subStatus') and alm.map_id IN ($mapId) ");
    $row2 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $countListArr['cus_count'] = $row2['cusCount'];
}

echo json_encode($countListArr);
?>
