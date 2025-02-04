<?php 
include('../ajaxconfig.php');
if (isset($_POST['area'])) {
    $area = $_POST['area'];
}
$loan_category_arr = array();

$result=$connect->query("SELECT * FROM sub_area_list_creation where area_id_ref='".$area."' and status=0");
while( $row = $result->fetch()){
    $sub_area_id = $row['sub_area_id'];
    $sub_area_name = $row['sub_area_name'];
    
    $checkQry=$connect->query("SELECT * FROM area_creation where status=0 and FIND_IN_SET($sub_area_id,sub_area)");
    if ($checkQry->rowCount()>0){
        $disabled = true;
    }else{$disabled=false;}
    $checkres = $checkQry->fetch();

    $loan_category_arr[] = array("sub_area_id" => $sub_area_id, "sub_area_name" => $sub_area_name,"disabled"=>$disabled);
}

echo json_encode($loan_category_arr);

// Close the database connection
$connect = null;
?>