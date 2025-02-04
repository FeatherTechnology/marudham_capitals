<?php
include('../ajaxconfig.php');

// $id  = $_POST['id'];

// if($id !=''){
//     $select = $connect->query("SELECT doc_id FROM verification_documentation WHERE id = '$id' ");
//     $code = $select ->fetch_assoc();
//     $doc_id = $code['doc_id'];

// }else{
$myStr = "CC";
$selectIC = $connect->query("SELECT com_code FROM concern_creation WHERE com_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT com_code FROM concern_creation WHERE com_code != '' ORDER BY id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["com_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    $doc_id = $myStr."-". "$appno2";
}
else
{
    $initialapp = $myStr."-101";
    $doc_id = $initialapp;
}
// }
echo json_encode($doc_id);

// Close the database connection
$connect = null;
?>