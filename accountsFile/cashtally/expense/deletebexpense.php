<?php

include('../../../ajaxconfig.php');

$bexp_id = $_POST['bexp_id'];

$qry = $connect->query("SELECT * from ct_db_bexpense where id='$bexp_id' ");
$upd = $qry->fetch()['upload'];
if($upd != ''){
    unlink('../../../uploads/expenseBill/'.$upd);
}

$qry = $connect->query("DELETE  from ct_db_bexpense where id = '$bexp_id' ");
if($qry){
    $response = "Deleted Successfully";
}else{
    $response = "Error While Deleting";
}

echo $response;

// Close the database connection
$connect = null;
?>