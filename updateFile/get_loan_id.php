<?php
include('../ajaxconfig.php');

$loan_id_arr = array();

$cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
$result = $connect->query("SELECT ii.loan_id,cp.guarentor_name,cp.guarentor_photo,ii.req_id
FROM in_issue ii
JOIN customer_profile cp ON ii.req_id = cp.req_id
WHERE ii.cus_id = '$cus_id'
  AND ii.cus_status BETWEEN 14 AND 17");

while ($row = $result->fetch()) {
    $loan_id_arr[] = array("loan_id" => $row['loan_id'],"guarentor_name" => $row['guarentor_name'],"guarentor_photo" => $row['guarentor_photo'],"req_id" => $row['req_id']);
}

echo json_encode($loan_id_arr);

// Close the database connection
$connect = null;
?>
