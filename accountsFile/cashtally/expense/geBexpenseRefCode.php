<?php

include('../../../ajaxconfig.php');

//////////////////////// To get Expense reference Code /////////////////////////
$myStr = "EXP";
$selectIC = $connect->query("SELECT ref_code FROM ct_db_bexpense WHERE ref_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT ref_code FROM ct_db_bexpense WHERE ref_code != '' ORDER BY id DESC LIMIT 1");
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


//////////////////////////////////////////////////////////////////////////////////

echo $ref_code;

// Close the database connection
$connect = null;
?>

