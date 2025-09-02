<?php
include '../../ajaxconfig.php';

$line_arr = array();

$lineQry = $connect->query("
    SELECT line_name, GROUP_CONCAT(map_id) AS line_ids
    FROM area_line_mapping
    WHERE status = 0
    GROUP BY line_name
    ORDER BY line_name ASC
");

while ($line = $lineQry->fetch()) {
    $line_arr[] = array(
        "line_ids" => $line['line_ids'],  // multiple ids
        "line_name" => $line['line_name']
    );
}

echo json_encode($line_arr);
$connect = null;
?>
