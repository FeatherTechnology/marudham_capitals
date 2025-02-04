<?php
session_start();
include '../ajaxconfig.php';
include '../dashboardFile/verificaitonDashboardClass.php';

$user_id = $_SESSION['userid'];

$verificaitonClass = new verificaitonClass($user_id);

$response = $verificaitonClass->getverificaitonCounts($connect);

echo json_encode($response);