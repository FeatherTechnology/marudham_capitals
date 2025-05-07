<?php 
include('../ajaxconfig.php');

$detailrecords = array();

if (isset($_POST['lineid']) && isset($_POST['loanCatId']) && isset($_POST['branchid'])) {
    $lineid = $_POST['lineid'];
    $loan_cat_area_id = $_POST['loanCatId'];
    $branchid = $_POST['branchid'];

    $qry = $connect->query("SELECT `area_id` FROM `area_duefollowup_mapping` WHERE `loan_category_id` = $loan_cat_area_id AND `branch_id` = $branchid ");
    $excludeAreaIds = [];
    if($qry->rowCount() > 0){
        while($duerow = $qry->fetchObject()){
            $excludeAreaIds = array_merge($excludeAreaIds, explode(',', $duerow->area_id));
        }
    }
    
    $selectQry = "SELECT area_id FROM area_line_mapping WHERE status = 0 AND FIND_IN_SET(map_id, ?) ";
    $stmt = $connect->prepare($selectQry);
    $stmt->execute([$lineid]);
    $j = 0;

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetchObject()) {
            $areaIds = explode(',', $row->area_id);

            foreach ($areaIds as $area_id) {
                $areaStmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND area_id = ? ");
                $areaStmt->execute([$area_id]);

                if ($areaRow = $areaStmt->fetchObject()) {
                    $detailrecords[$j]['area_id']   = $areaRow->area_id;
                    $detailrecords[$j]['area_name'] = $areaRow->area_name;

                    if(in_array($areaRow->area_id, $excludeAreaIds)){
                        $detailrecords[$j]['disabled'] = true;
                    }else{
                        $detailrecords[$j]['disabled'] = false;
                    }

                    $j++;
                }
            }
        }
    }
}

echo json_encode($detailrecords);

// Close the connection
$connect = null;
?>