<?php 
include('../ajaxconfig.php');

if(isset($_POST['branch_id'])){
    $branch_id = $_POST['branch_id'];
}

$staffArr = array();

$branch_id1 = explode(',',$branch_id);

foreach($branch_id1 as $branch_id){
    $result=$connect->query("SELECT * FROM area_group_mapping where status=0 and branch_id = '".$branch_id."' ");
    while( $row = $result->fetch()){
        $map_id = $row['map_id'];
        $group_name = $row['group_name'];
        
        // $area_id = explode(',',$row['area_id']);
        // foreach($area_id as $area){

        //     $runQry = $connect->query("SELECT * From area_list_creation where area_id= $area and area_enable = 0");
        //     if($runQry ->num_rows >0){

        //     }
        // }

        
        $staffArr[] = array("map_id" => $map_id, "group_name" => $group_name);
    }
}

echo json_encode($staffArr);

// Close the database connection
$connect = null;
?>