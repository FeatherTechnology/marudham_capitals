<?php 
include('../ajaxconfig.php');
$taluk='';  
if (isset($_POST['talukselected'])) {
    $taluk = $_POST['talukselected'];
}

$loan_category_arr = array();

$result=$connect->query("SELECT * FROM area_list_creation where taluk= '".$taluk."' and status=0");

while( $row = $result->fetch()){
    $area_id = $row['area_id'];
    $area_name = $row['area_name'];
    $loan_category_arr[] = array("area_id" => $area_id, "area_name" => $area_name);
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
?>