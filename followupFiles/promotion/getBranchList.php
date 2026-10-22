<?php
//Also using in reports.
include('../../ajaxconfig.php');
session_start();
$userid = $_SESSION['userid'];
$branchList = [];

$branchQry = $connect->prepare("SELECT bc.branch_id, bc.branch_name FROM user u JOIN branch_creation bc ON FIND_IN_SET(bc.branch_id, u.branch_id) WHERE u.user_id = ? ORDER BY bc.branch_id");
$branchQry->execute([$userid]);
$branchList = $branchQry->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($branchList);
$connect = null;
?>