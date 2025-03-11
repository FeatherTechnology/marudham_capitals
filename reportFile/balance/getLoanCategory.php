<?php
session_start();
include '../../ajaxconfig.php';

$result = array();
$userQry = $connect->query("SELECT `loan_category_creation_name` FROM `loan_category_creation` WHERE 1 ");
if ($userQry->rowCount() > 0) {
    $result = $userQry->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($result);
?>