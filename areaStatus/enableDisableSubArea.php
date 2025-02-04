<?php
include ('../ajaxconfig.php');

if(isset($_POST['area_id'])){
    $area_id = $_POST['area_id'];
}
if(isset($_POST['action'])){
    $action = $_POST['action'];
}
$message = '';
if($action == 'enable'){
    $Qry = "UPDATE sub_area_list_creation set sub_area_enable = 0 where sub_area_id = '".$area_id."' ";
    $run = $connect->query($Qry) or die;
    if($run->rowCount() > 0){
        $message = 'Enabled Successfully';
    }
}else if($action == 'disable'){
    $Qry = "UPDATE sub_area_list_creation set sub_area_enable = 1 where sub_area_id = '".$area_id."' ";
    $run = $connect->query($Qry) or die;
    if($run->rowCount() > 0){
        $message = 'Disabled Successfully';
    }
}

echo json_encode($message);

// Close the database connection
$connect = null;
?>