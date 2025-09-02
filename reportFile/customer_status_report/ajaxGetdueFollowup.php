<?php
include '../../ajaxconfig.php';

$line_arr = array();

$lineQry = $connect->query("
    SELECT duefollowup_name, GROUP_CONCAT(map_id) AS followup_ids
    FROM area_duefollowup_mapping
    WHERE status = 0
    GROUP BY duefollowup_name
    ORDER BY duefollowup_name ASC
");

while ($line = $lineQry->fetch()) {
    $line_arr[] = array(
        "followup_ids" => $line['followup_ids'],  // multiple ids
        "duefollowup_name" => $line['duefollowup_name']
    );
}

echo json_encode($line_arr);
$connect = null;
?>