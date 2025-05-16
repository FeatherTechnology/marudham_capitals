<?php 
include('../ajaxconfig.php');

$detailrecords = array();

if (isset($_POST['lineid'])) {
    $lineid = $_POST['lineid'];

    $selectQry = "SELECT area_id FROM area_line_mapping WHERE status = 0 AND FIND_IN_SET(map_id, ?) ";
    $stmt = $connect->prepare($selectQry);
    $stmt->execute([$lineid]);

    $j = 0;

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetchObject()) {
            $areaIds = explode(',', $row->area_id);

            foreach ($areaIds as $area_id) {
                $areaStmt = $connect->prepare("SELECT area_id, area_name FROM area_list_creation WHERE status = 0 AND area_id = ?");
                $areaStmt->execute([$area_id]);

                if ($areaRow = $areaStmt->fetchObject()) {
                    $detailrecords[$j]['area_id']   = $areaRow->area_id;
                    $detailrecords[$j]['area_name'] = $areaRow->area_name;

                    // Default to false (not disabled)
                    $detailrecords[$j]['disabled'] = false;

                    $runQry = $connect->prepare("SELECT sub_area_id FROM area_duefollowup_mapping WHERE FIND_IN_SET(?, area_id)");
                    $runQry->execute([$area_id]);

                    $sub_area_ids = [];
                    if ($runQry->rowCount() > 0) {
                        while ($ress = $runQry->fetch()) {
                            $sub_area_ids = array_merge($sub_area_ids, explode(',', $ress['sub_area_id']));
                        }

                        $runQry1 = $connect->prepare("SELECT sub_area_id FROM sub_area_list_creation WHERE area_id_ref = ? AND status = 0");
                        $runQry1->execute([$area_id]);

                        $sub_area_all = $runQry1->fetchAll(PDO::FETCH_COLUMN);

                        if (empty($sub_area_all)) {
                            $detailrecords[$j]['disabled'] = true;
                        } else {
                            $uniqueMappedSubs = array_unique($sub_area_ids);
                            $finalizeSub = array_diff($sub_area_all, $uniqueMappedSubs);
                            $detailrecords[$j]['disabled'] = empty($finalizeSub);
                        }
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
