<?php
require '../ajaxconfig.php';

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
}
$proof = $_POST['proof'];

if ($proof == 0) { //customer
    $result = $connect->query("SELECT customer_name FROM `customer_register` where cus_id = '$cus_id' ");
    $row = $result->fetch();
    $response = $row['customer_name'];
} else if ($proof == 1) { //guarentor (family
    $result = $connect->query("SELECT fam.famname FROM customer_profile cp JOIN verification_family_info fam ON cp.guarentor_name = fam.id where cp.req_id = '$req_id' ");
    if ($result->rowCount() > 0) {
        $row = $result->fetch();
        $response = $row['famname'];
    } else {
        $response = '';
    }
}

echo json_encode($response);

// Close the database connection
$connect = null;
