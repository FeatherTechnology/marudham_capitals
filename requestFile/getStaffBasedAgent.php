<?php
include('../ajaxconfig.php');
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}

$qry = $connect->query("SELECT ac.ag_id, ac.ag_name From agent_creation ac JOIN user u ON FIND_IN_SET(ac.ag_id, u.agentforstaff) WHERE user_id = '$user_id' ");
$row = $qry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($row);

// Close the database connection
$connect = null;