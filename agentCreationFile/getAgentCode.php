<?php
include('../ajaxconfig.php');
// if(isset($_POST["dir_type"])){
//     $sdir_type = $_POST["dir_type"];
// }

$myStr = "AG";
$selectIC = $connect->query("SELECT ag_code FROM agent_creation WHERE ag_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ag_code FROM agent_creation WHERE ag_code != '' ORDER BY ag_id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["ag_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $ag_code = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-101";
    $ag_code = $initialapp;
}
echo json_encode($ag_code);

// Close the database connection
$connect = null;
?>
