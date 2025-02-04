<?php
session_start();
$user_id = $_SESSION['userid'];
include("../../ajaxconfig.php");

$i=0;
$records = array();

$qry = $connect->query("SELECT bank_details from `user` where `user_id` = '$user_id' ");
$values = $qry->fetch()['bank_details'];

if($values != ''){

    $bank_id_arr = explode(',',$values);

    foreach ($bank_id_arr as $val){

        $qry = $connect->query("SELECT id,bank_name,short_name,acc_no from bank_creation where id=$val");
        while($row = $qry->fetch()){
            $records[$i]['bank_id'] = $row['id'];
            $records[$i]['bank_fullname'] = $row['bank_name'];
            $records[$i]['acc_no'] = $row['acc_no'];
            $records[$i]['bank_name'] = $row['short_name'] .' - '. substr($row['acc_no'],-5) ;
            $i++;
        }
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>