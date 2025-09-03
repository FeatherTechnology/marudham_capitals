<?php
include '../../ajaxconfig.php';

$line_arr = array();

$lineQry = $connect->query("
    SELECT group_name, GROUP_CONCAT(map_id) AS group_ids
    FROM area_group_mapping
    WHERE status = 0
    GROUP BY group_name
    ORDER BY group_name ASC
");

while ($line = $lineQry->fetch()) {
    $line_arr[] = array(
        "group_ids" => $line['group_ids'],  // multiple ids
        "group_name" => $line['group_name']
    );
}

echo json_encode($line_arr);
$connect = null;
?>
