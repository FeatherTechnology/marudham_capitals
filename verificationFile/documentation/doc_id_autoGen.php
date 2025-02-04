<?php
include('../../ajaxconfig.php');

$id  = $_POST['id'];

if ($id != '') {
    $select = $connect->query("SELECT doc_id FROM verification_documentation WHERE id = '$id' ");
    $code = $select->fetch();
    $doc_id = $code['doc_id'];
} else {
    $myStr = "DOC";
    $selectIC = $connect->query("SELECT doc_id FROM verification_documentation WHERE doc_id != '' ");
    if ($selectIC->rowCount() > 0) {
        $codeAvailable = $connect->query("SELECT doc_id FROM verification_documentation WHERE doc_id != '' ORDER BY id DESC LIMIT 1");
        while ($row = $codeAvailable->fetch()) {
            $ac2 = $row["doc_id"];
        }
        $appno2 = ltrim(strstr($ac2, '-'), '-');
        $appno2 = $appno2 + 1;
        $doc_id = $myStr . "-" . "$appno2";
    } else {
        $initialapp = $myStr . "-101";
        $doc_id = $initialapp;
    }
}
echo json_encode($doc_id);

// Close the database connection
$connect = null;
