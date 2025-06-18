<?php
include('../../ajaxconfig.php');

$id  = $_POST['id'];

if ($id != '') {
    $select = $connect->query("SELECT doc_id FROM acknowlegement_documentation WHERE id = '$id' ");
    $code = $select->fetch();
    $doc_id = $code['doc_id'];
} else {
    $myStr = "DOC";

    $codeAvailable = $connect->query("SELECT CONCAT('DOC-', MAX(CAST(SUBSTRING_INDEX(doc_id, '-', -1) AS UNSIGNED))) AS doc_id FROM acknowlegement_documentation WHERE doc_id REGEXP '^DOC-[0-9]+' ");
    if ($codeAvailable->rowCount() > 0) {
        $row = $codeAvailable->fetch(); 
            $ac2 = $row["doc_id"];
        
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
