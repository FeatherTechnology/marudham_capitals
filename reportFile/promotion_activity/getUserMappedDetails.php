<?php
include('../../ajaxconfig.php');

$type = $_POST['typeVal'] ?? '';

$result =[];
$col ='';
$table = '';

if($type =='2'){
    $col = 'group_name AS map_name, map_id AS ids';
    $table = 'area_group_mapping agm';
    $orderby = 'GROUP BY group_name ORDER BY group_name ASC';

} else if($type =='3'){
    $col = 'line_name AS map_name, map_id AS ids';
    $table = 'area_line_mapping alm';
    $orderby = 'GROUP BY line_name ORDER BY line_name ASC';
    
} else if($type =='4'){
    $col = 'duefollowup_name AS map_name, map_id AS ids';
    $table = 'area_duefollowup_mapping adm';
    $orderby = 'GROUP BY duefollowup_name ORDER BY duefollowup_name ASC';
}

if($col =='' && $table == ''){
    echo json_encode($result);
    exit;
}

$qry = $connect->prepare("SELECT $col
    FROM $table
    WHERE 1 
    $orderby");
$qry->execute();

$result = $qry->fetchAll(PDO::FETCH_ASSOC);

//Close DB connection
$connect = null;

echo json_encode($result);