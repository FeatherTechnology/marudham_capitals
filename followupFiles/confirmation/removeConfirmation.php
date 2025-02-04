<?php

include('../../ajaxconfig.php');

if(isset($_POST['req_id'])){
    $req_id = $_POST['req_id'];
}


    $sql = $connect->query(" UPDATE confirmation_followup set remove_status = 1 where req_id = '$req_id' ORDER BY id DESC LIMIT 1 ");
    
    if($sql){
        $response = 'Removed Successfully';
    }else{
        $response = 'Error While Removing';
    }

echo $response;

// Close the database connection
$connect = null;
?>