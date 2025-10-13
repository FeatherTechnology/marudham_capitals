<?php 
include('../ajaxconfig.php');

$staffArr = array();

if(isset($_POST['branch_id']) && $_POST['branch_id'] !=''){
    $branch_id = $_POST['branch_id'];

    $result = $connect->query("SELECT map_id, duefollowup_name FROM area_duefollowup_mapping WHERE status = 0 AND branch_id IN ($branch_id) ");
        while( $row = $result->fetch()){
            $map_id = $row['map_id'];
            $duefollowup_name = $row['duefollowup_name'];
            $staffArr[] = array("map_id" => $map_id, "duefollowup_name" => $duefollowup_name);
        }
}

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>