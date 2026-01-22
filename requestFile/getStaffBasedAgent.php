<?php
include('../ajaxconfig.php');
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
}

$detailrecords = array();

$result = $connect->query("SELECT agentforstaff FROM user where status = 0 and user_id = $user_id ");
$row = $result->fetch();
    $agentforstaff = $row['agentforstaff'];

$agent_ids = explode(',', $agentforstaff);
$i = 0;
foreach ($agent_ids as $ag) {
    $qry = $connect->query("SELECT ag_id, ag_name From agent_creation where ag_id = '" . $ag . "' ");
    $row = $qry->fetch();
    $detailrecords[$i]['ag_id'] = $row['ag_id'];
    $detailrecords[$i]['ag_name'] = $row['ag_name'];
    $i++;
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;