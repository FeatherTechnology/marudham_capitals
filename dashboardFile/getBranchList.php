<?php
session_start();
include '../dashboardFile/branchProcess.php';

$user_id = $_SESSION['userid'];
$branchProcess = new branchProcess($connect);
$branch_list = $branchProcess->getBranchList($user_id);

echo json_encode($branch_list);