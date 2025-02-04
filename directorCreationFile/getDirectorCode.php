<?php
include('../ajaxconfig.php');
if(isset($_POST["dir_type"])){
    $sdir_type = $_POST["dir_type"];
}

if($sdir_type == '1'){
    $myStr = "D";
}
if($sdir_type == '2'){
    $myStr = "EXD";
}
$selectIC = $connect->query("SELECT dir_code FROM director_creation WHERE dir_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT dir_code FROM director_creation WHERE dir_code != '' ORDER BY dir_id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["dir_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $dir_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-101";
    $dir_code = $initialapp;
}
echo json_encode($dir_code);
?>
