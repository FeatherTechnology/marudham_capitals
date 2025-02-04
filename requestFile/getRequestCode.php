<?php
include('../ajaxconfig.php');


$myStr = "REQ";
$selectIC = $connect->query("SELECT req_code FROM request_creation WHERE req_code != '' ");
if ($selectIC->rowCount() > 0) {
    $codeAvailable = $connect->query("SELECT req_code FROM request_creation WHERE req_code != '' ORDER BY req_id DESC LIMIT 1");
    while ($row = $codeAvailable->fetch()) {
        $ac2 = $row["req_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-');
    $appno2 = $appno2 + 1;
    $req_code = $myStr . "-" . "$appno2";
} else {
    $initialapp = $myStr . "-101";
    $req_code = $initialapp;
}
echo json_encode($req_code);

// Close the database connection
$connect = null;
