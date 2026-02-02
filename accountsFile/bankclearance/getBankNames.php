<?php
session_start();
include("../../ajaxconfig.php");
if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}

    $qry = $connect->query("SELECT bc.* FROM bank_creation bc JOIN user u ON FIND_IN_SET(bc.id, u.bank_access) > 0 WHERE u.user_id =  $user_id;");
    $records = array();
    if($qry->rowCount() > 0){
        $i=0;
        while($row = $qry->fetch()){
            $records[$i]['id'] = $row['id'];
            $records[$i]['bank_name'] = $row['bank_name'];
            $records[$i]['short_name'] = $row['short_name'];
            $i++;
        }
    }	
    echo json_encode($records);

// Close the database connection
$connect = null;
?>