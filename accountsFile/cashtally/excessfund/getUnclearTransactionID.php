<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id = $_POST['bank_id'];

$records = array();
$i=0;

$qry = $connect->query("SELECT * from bank_stmt where bank_id = '$bank_id' and insert_login_id = '$user_id' and clr_status = 0 ");
//fetching data from bank statements where clr status as 0 for get uncleared statements

    while($row = $qry->fetch()){
        $records[$i]['stmt_id'] = $row['id'];
        $records[$i]['ucl_trans_id'] = $row['trans_id'];
        $i++;
    }

echo json_encode($records);

// Close the database connection
$connect = null;
?>