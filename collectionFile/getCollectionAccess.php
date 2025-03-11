<?php
session_start();
include '../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}

$userQry = $connect->query("SELECT collection_access FROM USER WHERE user_id = '$user_id' ");
$row = $userQry->fetch();
if($row['collection_access']==='0'){
    $result=0;
} else {
    $result=1;
}
echo json_encode($result);
?>