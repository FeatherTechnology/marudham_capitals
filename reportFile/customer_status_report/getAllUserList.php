<?php
include '../../ajaxconfig.php';

$response = array();
$i = 0;

$user_track = isset($_POST['user_track']) ? $_POST['user_track'] : '';  

if ($user_track == '1') {
    // When user_track is passed, all users
    $where = "status = 0";
} else if($user_track == '2'){
    // confirmation
     $where = "status = 0 AND (confirmation_followup = 0)";

} else  {
    // When user_track is NOT passed
    $where = "status = 0 AND (collection = 0 OR due_followup = 0)";
}

$qry = $connect->query("SELECT fullname, GROUP_CONCAT(user_id ORDER BY user_id ASC) AS user_ids 
                        FROM user 
                        WHERE $where
                        GROUP BY fullname 
                        ORDER BY fullname ASC");

while ($row = $qry->fetch()) {
    $response[$i]['user_id'] = $row['user_ids'];
    $response[$i]['username'] = $row['fullname'];
    $i++;
}

echo json_encode($response);

$connect = null;
