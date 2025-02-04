<?php
session_start();
include '../dashboardFile/branchProcess.php';

$user_id = $_SESSION['userid'];
$branch_id = $_POST['branch_id'];
$branchProcess = new branchProcess();
$sub_area_list = $branchProcess->getSubAreaList($branch_id,$user_id);

echo json_encode($sub_area_list);