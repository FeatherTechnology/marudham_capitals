<?php
include('../ajaxconfig.php');


$detailrecords = array();

$i = 0;
$qry = $connect->query("SELECT * From agent_creation where status = 0 ");
while ($row = $qry->fetch()) {
    $detailrecords[$i]['ag_id'] = $row['ag_id'];
    $detailrecords[$i]['ag_name'] = $row['ag_name'];
    $i++;
}

echo json_encode($detailrecords);

// Close the database connection
$connect = null;