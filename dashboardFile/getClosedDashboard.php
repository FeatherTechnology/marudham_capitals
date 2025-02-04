<?php
session_start();
include '../ajaxconfig.php';
include '../dashboardFile/closedDashboardClass.php';

$user_id = $_SESSION['userid'];

$closedClass = new ClosedDashboardClass($user_id);

$response = $closedClass->getClosedCounts($connect);

echo json_encode($response);