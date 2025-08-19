<?php
include('../../ajaxconfig.php');

$result = array();

$qry = $connect->query("SELECT user_id, fullname 
    FROM user 
    WHERE due_followup_lines IS NOT NULL 
    AND due_followup_lines != ''
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null; // Close connection
echo json_encode($result);
