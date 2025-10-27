<?php 
include('../ajaxconfig.php');

$detailrecords = array();

// Get all active areas
$selectQry = "SELECT area_id, area_name FROM area_list_creation WHERE status = 0";
$res = $connect->query($selectQry);

if ($res->rowCount() > 0) {

    // 🔹 Get all area IDs already used in area_duefollowup_mapping
    $mappedAreaIds = [];
    $mapQry = $connect->query("SELECT area_id FROM area_duefollowup_mapping WHERE status = 0");
    if ($mapQry->rowCount() > 0) {
        while ($mapRow = $mapQry->fetch()) {
            // area_id can be comma-separated, so we split it
            $mappedAreaIds = array_merge($mappedAreaIds, explode(',', $mapRow['area_id']));
        }
    }
    $mappedAreaIds = array_unique(array_filter($mappedAreaIds)); // remove duplicates and empty values

    $j = 0;
    while ($row = $res->fetchObject()) {

        $areaId = $row->area_id;

        $detailrecords[$j]['area_id']   = $areaId;
        $detailrecords[$j]['area_name'] = $row->area_name;

        // 🔹 Disable area if it is already mapped
        if (in_array($areaId, $mappedAreaIds)) {
            $detailrecords[$j]['disabled'] = true;
        } else {
            $detailrecords[$j]['disabled'] = false;
        }

        $j++;
    }
}

echo json_encode($detailrecords);


// Close the database connection

// if (isset($_POST['branchid']) && isset($_POST['status'])) {
//     $branchid = $_POST['branchid'];
//     $status = $_POST['status'];
//     // Convert comma-separated string to array
//     $statusArr = array_map('trim', explode(',', $status));

//     // Build properly quoted list for SQL IN()
//     $statusList = "'" . implode("','", $statusArr) . "'";

//     $qry = $connect->query("SELECT `area_id` FROM `area_duefollowup_mapping` WHERE `branch_id` = $branchid AND `customer_status` IN ($statusList)");
//     $excludeAreaIds = [];
//     if($qry->rowCount() > 0){
//         while($duerow = $qry->fetchObject()){
//             $excludeAreaIds = array_merge($excludeAreaIds, explode(',', $duerow->area_id));
//         }
//     }
    
//     $branchStmt = $connect->prepare("SELECT taluk FROM branch_creation WHERE status = 0 AND branch_id = ? ");
//     $branchStmt->execute([$branchid]);
//     $branchInfo = $branchStmt->fetchObject();
//     $branchTaluk = $branchInfo->taluk;

//     $areaStmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND taluk = ? ");
//     $areaStmt->execute([$branchTaluk]);
//     $j = 0;

//     while($areaRow = $areaStmt->fetchObject()) {
//         $detailrecords[$j]['area_id']   = $areaRow->area_id;
//         $detailrecords[$j]['area_name'] = $areaRow->area_name;

//         if(in_array($areaRow->area_id, $excludeAreaIds)){
//             $detailrecords[$j]['disabled'] = true;
//         }else{
//             $detailrecords[$j]['disabled'] = false;
//         }

//         $j++;
//     }
// }

// echo json_encode($detailrecords);

// if (isset($_POST['lineid']) && isset($_POST['loanCatId']) && isset($_POST['branchid'])) {
//     $lineid = $_POST['lineid'];
//     $loan_cat_area_id = $_POST['loanCatId'];
//     $branchid = $_POST['branchid'];

//     $qry = $connect->query("SELECT `area_id` FROM `area_duefollowup_mapping` WHERE `loan_category_id` = $loan_cat_area_id AND `branch_id` = $branchid ");
//     $excludeAreaIds = [];
//     if($qry->rowCount() > 0){
//         while($duerow = $qry->fetchObject()){
//             $excludeAreaIds = array_merge($excludeAreaIds, explode(',', $duerow->area_id));
//         }
//     }
    
//     $selectQry = "SELECT area_id FROM area_line_mapping WHERE status = 0 AND FIND_IN_SET(map_id, ?) ";
//     $stmt = $connect->prepare($selectQry);
//     $stmt->execute([$lineid]);
//     $j = 0;

//     if ($stmt->rowCount() > 0) {
//         while ($row = $stmt->fetchObject()) {
//             $areaIds = explode(',', $row->area_id);

//             foreach ($areaIds as $area_id) {
//                 $areaStmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND area_id = ? ");
//                 $areaStmt->execute([$area_id]);

//                 if ($areaRow = $areaStmt->fetchObject()) {
//                     $detailrecords[$j]['area_id']   = $areaRow->area_id;
//                     $detailrecords[$j]['area_name'] = $areaRow->area_name;

//                     if(in_array($areaRow->area_id, $excludeAreaIds)){
//                         $detailrecords[$j]['disabled'] = true;
//                     }else{
//                         $detailrecords[$j]['disabled'] = false;
//                     }

//                     $j++;
//                 }
//             }
//         }
//     }
// }

// Close the connection
$connect = null;
?>