<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$records = array();

$qry = $connect->query("SELECT user_id, fullname, role FROM `user` WHERE cash_tally = 0 and (user_id != '$user_id' and user_id != '1') and role != '2' ");
$i=0;
while($row = $qry->fetch()){
    $records[$i]['user_id'] = $row['user_id'];
    $records[$i]['user_name'] = $row['fullname'];
    $records[$i]['role'] = $row['role'];
    $i++;
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>