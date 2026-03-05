<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$i=0;
$records = array();

$qry = $connect->query("SELECT fullname, role, user_id FROM user WHERE user_id = '$user_id' ");
$row = $qry->fetch();
$records[0]['user_id'] = $row['user_id'];
$records[0]['user_name'] = $row['fullname'];
if($row['role'] == '1'){ $records[0]['user_type'] = 'Director'; }elseif( $row['role'] == '3'){ $records[0]['user_type'] = 'Staff'; }

$qry = $connect->query("SELECT id, CONCAT(exp_code ,'-', category) AS category FROM expense_category WHERE status = 0 ORDER BY CAST(exp_code AS UNSIGNED) ASC");
while($row = $qry->fetch()){
    $records[$i]['cat_id'] = $row['id'];
    $records[$i]['cat_name'] = $row['category'];
    $i++;
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>