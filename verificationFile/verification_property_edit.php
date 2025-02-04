<?php
require '../ajaxconfig.php';

$id = $_POST['id'];

$prptyEditRes = array();

$grpInfo = $connect->query("SELECT * FROM `verification_property_info` WHERE id='$id' ");
$group = $grpInfo->fetch();

$prptyEditRes['id'] = $group['id'];
$prptyEditRes['type'] = $group['property_type'];
$prptyEditRes['measuree'] = $group['property_measurement'];
$prptyEditRes['pVal'] = $group['property_value'];
$prptyEditRes['holder'] = $group['property_holder'];

echo json_encode($prptyEditRes);

// Close the database connection
$connect = null;