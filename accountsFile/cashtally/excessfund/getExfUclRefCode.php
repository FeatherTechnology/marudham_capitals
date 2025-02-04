<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$records = array();

//////////////////////// To get Exchange reference Code once again /////////////////////////
$myStr = "UCL";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_exf WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_db_exf WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["ref_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $records['ref_code'] = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-100001";
    $records['ref_code'] = $initialapp;
}
///////////////////////////////////////////////////////////////////////////////////////////


$qry = $connect->query("SELECT fullname,role,user_id from user where user_id ='$user_id' ");
$row = $qry->fetch();
$records['user_id'] = $row['user_id'];
$records['user_name'] = $row['fullname'];
if($row['role'] == '1'){$records['user_type'] = 'Director';}elseif($row['role'] == '3'){$records['user_type'] = 'Staff';}

echo json_encode($records);

// Close the database connection
$connect = null;
?>