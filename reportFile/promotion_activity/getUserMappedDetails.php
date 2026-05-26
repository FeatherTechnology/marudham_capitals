<?php
include('../../ajaxconfig.php');

$user_id = $_POST['userId'] ?? '';
$type = $_POST['typeVal'] ?? '';

$result =[];
$col ='';
$table = '';

if($type =='2'){
    $col = 'group_name AS map_name, GROUP_CONCAT(map_id) AS ids';
    $table = 'area_group_mapping agm ON FIND_IN_SET(agm.map_id, u.group_id)';
    $orderby = 'GROUP BY group_name ORDER BY group_name ASC';

} else if($type =='3'){
    $col = 'line_name AS map_name, GROUP_CONCAT(map_id) AS ids';
    $table = 'area_line_mapping alm ON FIND_IN_SET(alm.map_id, u.line_id)';
    $orderby = 'GROUP BY line_name ORDER BY line_name ASC';
    
} else if($type =='4'){
    $col = 'duefollowup_name AS map_name, GROUP_CONCAT(map_id) AS ids';
    $table = 'area_duefollowup_mapping adm ON FIND_IN_SET(adm.map_id, u.due_followup_lines)';
    $orderby = 'GROUP BY duefollowup_name ORDER BY duefollowup_name ASC';
}

if($col =='' && $table == ''){
    echo json_encode($result);
    exit;
}

$qry = $connect->prepare("SELECT $col
    FROM user u
    LEFT JOIN $table
    WHERE user_id IN (?) 
    $orderby");
$qry->execute([$user_id]);

$result = $qry->fetchAll(PDO::FETCH_ASSOC);


$connect = null;
echo json_encode($result);