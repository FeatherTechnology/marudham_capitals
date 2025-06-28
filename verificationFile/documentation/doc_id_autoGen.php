<?php
include('../../ajaxconfig.php');

$id  = $_POST['id'];

$select = $connect->query("SELECT doc_id FROM acknowlegement_documentation WHERE id = '$id' AND doc_id IS NOT NULL ");
if ($select && $select->rowCount() > 0) {
    $code = $select->fetch();
    $doc_id = $code['doc_id'];

} else {
   $myStr = "DOC";

$codeAvailable = $connect->query("SELECT MAX(CAST(SUBSTRING_INDEX(doc_id, '-', -1) AS UNSIGNED)) AS max_number FROM acknowlegement_documentation WHERE doc_id REGEXP '^DOC-[0-9]+' FOR UPDATE");

if ($codeAvailable && $codeAvailable->rowCount() > 0) {
    $row = $codeAvailable->fetch();
    $maxNumber = isset($row["max_number"]) ? (int)$row["max_number"] : 0;

    $nextNumber = $maxNumber + 1;
    $doc_id = $myStr . "-" . $nextNumber;
} else {
    $doc_id = $myStr . "-101";
}
}

echo json_encode($doc_id);

// Close the database connection
$connect = null;

