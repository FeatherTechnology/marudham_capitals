<?php
session_start();
include '../ajaxconfig.php';
include '../dashboardFile/approvalDashboardClass.php';

$user_id = $_SESSION['userid'];

$approvalClass = new approvalClass($user_id);

$response = $approvalClass->getApprovalCounts($connect);

echo json_encode($response);