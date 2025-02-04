<?php
session_start();
include('..\..\ajaxconfig.php');

if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $qry = $connect->query("SELECT cash_tally_admin from user where user_id = $userid");
    $admin_access = $qry->fetch()['cash_tally_admin'];
}else{
    $admin_access = '1';
}
if(isset($_POST['mode'])){
    $mode = $_POST['mode'];
}

$records = array();
$qry = "SELECT id,modes FROM cash_tally_modes where ";

if($mode == 'handcredit'){
    $qry .= "handcredit = 0 ";
    if($admin_access == '1'){
        $qry .= "and admin_access = 1 ";
    }
}else if($mode == 'bankcredit'){
    $qry .= "bankcredit = 0 ";
    if($admin_access == '1'){
        $qry .= "and admin_access = 1 ";
    }
}else if($mode == 'handdebit'){
    $qry .= "handdebit = 0 ";
    if($admin_access == '1'){
        $qry .= "and admin_access = 1 ";
    }
}
else if($mode == 'bankdebit'){
    $qry .= "bankdebit = 0 ";
    if($admin_access == '1'){
        $qry .= "and admin_access = 1 ";
    }
}



$run = $connect->query($qry);
if($run->rowCount() > 0){
    $i=0;
    while($row = $run->fetch()){
        $records[$i]['id'] = $row['id'];
        $records[$i]['modes'] = $row['modes'];
        $i++;
    }
}

echo json_encode($records);

// Close the database connection
$connect = null;
?>