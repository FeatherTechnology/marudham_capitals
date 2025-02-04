<?php

include '../ajaxconfig.php';

$cus_id = $_POST['cus_id'];

$response = array();

$sql = $connect->query("SELECT a.guarentor_name as fam_id,a.guarentor_relation as relation,a.guarentor_photo as img,b.famname as name from customer_profile a JOIN verification_family_info b ON a.guarentor_name = b.id where a.cus_id = $cus_id ORDER BY a.id DESC LIMIT 1");
if ($sql->rowCount() > 0) {
    $row = $sql->fetch();
    // $response['img'] = $row['guarentor_photo'];
    // $response['relation'] = $row['guarentor_relation'];
    // $response['name'] = $row['famname'];
    // $response['fam_id'] = $row['guarentor_name'];
    $response[] = $row;
} else {
    $response = array();
}


// $sql = $connect->query("SELECT id,famname FROM verification_family_info where cus_id = $cus_id " );
// while($row = $sql->fetch_assoc()){
//     $response['fam_info'][] = $row;
// }

echo json_encode($response);

// Close the database connection
$connect = null;