<?php

session_start();
include('../ajaxconfig.php');
include 'getLoanDetailsClass.php';

if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}

$obj = new GetLoanDetails($connect, $req_id, date('Y-m-d'),'Collection');
echo json_encode($obj->response);
