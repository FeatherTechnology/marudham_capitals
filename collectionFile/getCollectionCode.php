<?php
include('../ajaxconfig.php');

$myStr = 'COL';
$selectIC = $connect->query("SELECT coll_code FROM `collection` WHERE coll_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT coll_code FROM collection WHERE coll_code != '' ORDER BY coll_id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["coll_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    // $appno1 = substr($appno2, 4, strpos($appno2, "/")) + 101 ;
    $coll_code = $myStr."-". "$appno2";
	// print_r($branch_code);die;
}
else
{
    $initialapp = $myStr."-101";
    $coll_code = $initialapp;
}
echo json_encode($coll_code);

// Close the database connection
$connect = null;
?>
