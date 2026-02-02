<?php
include('../../ajaxconfig.php');

$result = [];

$qry = $connect->query("SELECT 
        fullname, 
        user_id AS user_ids
    FROM user
    WHERE cash_tally = 0 AND status = 0
    GROUP BY fullname
    ORDER BY fullname ASC
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null; // Close connection
echo json_encode($result);
