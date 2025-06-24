<?php
include('../ajaxconfig.php');

$myStr = "CC";
$selectIC = $connect->query("SELECT com_code FROM concern_creation WHERE com_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT com_code FROM concern_creation WHERE com_code != '' ORDER BY id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["com_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $com_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-101";
    $com_code = $initialapp;
}

echo json_encode($com_code);

// Close the database connection
$connect = null;
?>