<?php
include '../../ajaxconfig.php';

$response = array();
$i = 0;
$qry = $connect->query("SELECT fullname, GROUP_CONCAT(user_id ORDER BY user_id ASC) AS user_ids FROM user WHERE status = 0 AND loan_cat != '' GROUP BY fullname ORDER BY fullname ASC;");
while ($row = $qry->fetch()) {
    $response[$i]['user_id'] = $row['user_ids'];
    $response[$i]['username'] = $row['fullname'];
    $i++;
}

echo json_encode($response);

// Close the database connection
$connect = null;
