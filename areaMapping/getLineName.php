<?php
include('../ajaxconfig.php');
$records = array();

if (isset($_POST['branchid'])) {
    $branch_id = $_POST['branchid'];

    $result = $connect->query("SELECT map_id, line_name FROM `area_line_mapping` where status=0 and branch_id IN ($branch_id) ORDER BY line_name ASC");
    if($result -> rowCount() > 0){
        $i = 0;
        while($row = $result->fetch()){
            $records[$i]['map_id'] = $row['map_id'];
            $records[$i]['line_name'] = $row['line_name'];
            $i++;
        }
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;