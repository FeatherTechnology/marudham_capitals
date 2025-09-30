<?php
include '../ajaxconfig.php';

if(isset($_POST["area_id"])){
    $area_id  = $_POST["area_id"];
}
$getct = "SELECT area_name_id, state, pincode, district FROM area_creation WHERE area_name_id = '".$area_id."'";
$result = $connect->query($getct);

$data = array();
if($row = $result->fetch(PDO::FETCH_ASSOC)){
    $data = $row; // this will include the whole row as an associative array
}

// return JSON
echo json_encode($data);

// Close the database connection
$connect = null;
?>
