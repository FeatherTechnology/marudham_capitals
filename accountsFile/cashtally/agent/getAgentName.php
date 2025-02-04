<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$i=0;
$records = array();

$qry = $connect->query("SELECT role from user where user_id = $user_id ");
$role = $qry->fetch()['role'];// check if the user is Director

if($role== 1){
    //if yes then show all the agent names directly
    $qry=$connect->query("SELECT ag.ag_id,ag.ag_name from agent_creation ag where ag.status=0");
}else{
    //else show only accessed agent names
    $qry=$connect->query("SELECT ag.ag_id,ag.ag_name from agent_creation ag JOIN user us ON FIND_IN_SET(ag.ag_id,us.agentforstaff) where us.user_id='$user_id' and ag.status=0");
}

while($row = $qry->fetch()){
    $records[$i]['ag_id'] = $row['ag_id']; 
    $records[$i]['ag_name'] = $row['ag_name']; 
    $i++;
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>