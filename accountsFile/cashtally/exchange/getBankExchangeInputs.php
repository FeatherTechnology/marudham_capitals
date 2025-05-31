<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../../ajaxconfig.php');

$bank_id = $_POST['cash_type'];

$response = array();
//////////////////////// To get Exchange reference Code /////////////////////////
$myStr = "EXD";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bexchange WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_db_bexchange WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["ref_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $ref_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-100001";
    $ref_code = $initialapp;
}

$response[0]['ref_code'] = $ref_code;

///////////////////// TO get from account name and account number ////////////////////
$qry = $connect->query("SELECT id as bank_id,short_name,acc_no from bank_creation where id = '$bank_id' ");
if($qry){
    $row = $qry->fetch();
    $response[0]['bank_id'] = $row['bank_id'];
    $response[0]['bank_name'] = $row['short_name'] .' - '.substr($row['acc_no'],-5);
}

///////////////////// To get, to bank dropdowns and account number ////////////////////
$qry = $connect->query("SELECT bc.id as bank_id, bc.short_name, bc.acc_no, us.fullname, us.user_id from bank_creation bc LEFT JOIN user us ON FIND_IN_SET(bc.id,us.bank_details) where bc.id != '$bank_id' GROUP BY bc.acc_no ");
if($qry){
    $i=1;
    while($row = $qry->fetch()){
        $response[$i]['to_bank_id'] = $row['bank_id'];
        $response[$i]['bank_user_id'] = $row['user_id'];
        $response[$i]['bank_user_name'] = $row['fullname'];
        $response[$i]['to_bank_name'] = $row['short_name'] .' - '.substr($row['acc_no'],-5);
        $i++;
    }
}

echo json_encode($response);

// Close the database connection
$connect = null;
?>