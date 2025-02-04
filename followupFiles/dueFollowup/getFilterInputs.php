<?php

include '../../ajaxconfig.php';

//to get branches
$sql = $connect->query("SELECT branch_id,branch_name FROM branch_creation where 1");
while($row = $sql->fetch()){
    $response['branch'][] = array('id'=>$row['branch_id'],'name'=>$row['branch_name']);
}

//to get Line
$sql = $connect->query("SELECT  map_id,line_name,branch_id,area_id FROM area_line_mapping where 1 ");
while($row = $sql->fetch()){
    $response['line'][] = array('id'=>$row['map_id'],'name'=>$row['line_name'],'branch_id'=>$row['branch_id'],'area_id'=>$row['area_id']);
}

//to get Area
$sql = $connect->query("SELECT area_id,area_name FROM area_list_creation where 1 ");
while($row = $sql->fetch()){
    $response['area'][]= array('id'=>$row['area_id'],'name'=>$row['area_name']);
}

//to get Sub Area
$sql = $connect->query("SELECT sub_area_id,sub_area_name,area_id_ref FROM sub_area_list_creation where 1 ");
while($row = $sql->fetch()){
    $response['sub_area'][] = array('id'=>$row['sub_area_id'],'name'=>$row['sub_area_name'],'area_id'=>$row['area_id_ref']);
}

// ******************************************************************************************************

//to get Loan Category
$sql = $connect->query("SELECT loan_category_creation_id,loan_category_creation_name FROM loan_category_creation a
        JOIN loan_category b ON a.loan_category_creation_id = b.loan_category_name
        GROUP by b.loan_category_name"); //this will get loan category only mentioned with sub category from loan_category table
while($row = $sql->fetch()){
    $response['loan_cat'][] = array('id'=>$row['loan_category_creation_id'],'name'=>$row['loan_category_creation_name']);
}

//to get Sub Categroy
$sql = $connect->query("SELECT loan_category_id,sub_category_name,loan_category_name FROM loan_category where 1 ");
while($row = $sql->fetch()){
    $response['sub_cat'][] = array('id'=>$row['loan_category_id'],'name'=>$row['sub_category_name'],'loan_cat'=>$row['loan_category_name']);
}

//to get Agent
$sql = $connect->query("SELECT ag_id,ag_name FROM agent_creation where 1 ");
while($row = $sql->fetch()){
    $response['agent'][] = array('id'=>$row['ag_id'],'name'=>$row['ag_name']);
}

// ******************************************************************************************************


echo json_encode($response);

// Close the database connection
$connect = null;
?>