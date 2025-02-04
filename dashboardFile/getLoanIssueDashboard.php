<?php
session_start();
include '../ajaxconfig.php';
include '../dashboardFile/loanIssueDashboardClass.php';

$user_id = $_SESSION['userid'];

$LoanIssueClass = new LoanIssueClass($user_id);

$response = $LoanIssueClass->getLoanIssueCounts($connect);

echo json_encode($response);