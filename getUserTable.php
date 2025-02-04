<?php 
include('./ajaxconfig.php');

$response = array();
$i=0;
$role_arr = [1=>'Director',2=>'Agent',3=>'Staff'];

$qry = $connect->query("SELECT * from `user` where 1 ");
while($row = $qry->fetch()){
    $response[$i]['name'] = $row['fullname'];
    $response[$i]['user_name'] = $row['user_name'];
    $response[$i]['role'] = $role_arr[$row['role']];
    $response[$i]['designation'] = 'random';
    $response[$i]['branch_name'] = 'Puducherry';
    $response[$i]['line_name'] = 'LA1';
    $i++;
}

echo json_encode($response);

// Close the database connection
$connect = null;