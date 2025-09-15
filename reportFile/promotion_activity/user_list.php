<?php
include('../../ajaxconfig.php');

$result = [];

$qry = $connect->query("SELECT 
        fullname, 
        GROUP_CONCAT(user_id ORDER BY user_id ASC) AS user_ids
    FROM user
    WHERE promotion_activity = 0 AND status = 0
    GROUP BY fullname
    ORDER BY fullname ASC
");

if ($qry->rowCount() > 0) {
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
}

$connect = null; // Close connection
echo json_encode($result);
