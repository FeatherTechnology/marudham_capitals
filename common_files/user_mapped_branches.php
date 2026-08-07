<?php
require '../ajaxconfig.php';
@session_start();
$user_id = $_SESSION['userid'];
$result =array();
$qry=$connect->query("SELECT bc.branch_id, bc.branch_name FROM branch_creation bc JOIN user u ON FIND_IN_SET(bc.branch_id, u.branch_id) WHERE u.user_id = '$user_id' ");
if($qry->rowCount()>0){
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}
$connect=null; //Close Connection.

echo json_encode($result);
?>