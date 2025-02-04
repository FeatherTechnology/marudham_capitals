<?php
session_start();
include '../ajaxconfig.php';
include '../dashboardFile/nocDashboardClass.php';

$user_id = $_SESSION['userid'];

$NocClass = new NocClass($user_id);

$response = $NocClass->getNOCCounts($connect);

echo json_encode($response);