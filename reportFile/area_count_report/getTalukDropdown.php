<?php 
include "../../ajaxconfig.php";
$thalukDetails = array();

$selectIC = $connect->query("SELECT DISTINCT taluk FROM area_list_creation WHERE 1; ");
if($selectIC->rowCount()>0){
    $i=0;
    while($row = $selectIC->fetch()){
        $thalukDetails[$i]['taluk'] = $row["taluk"];
        $i++;
	}

}

echo json_encode($thalukDetails);

// Close the database connection
$connect = null;
?>