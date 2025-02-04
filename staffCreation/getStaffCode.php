<?php
include('../ajaxconfig.php');


$myStr = "ST";
$selectIC = $connect->query("SELECT staff_code FROM staff_creation WHERE staff_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT staff_code FROM staff_creation WHERE staff_code != '' ORDER BY staff_id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["staff_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $staff_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-101";
    $staff_code = $initialapp;
}
echo json_encode($staff_code);

// Close the database connection
$connect = null;
?>
