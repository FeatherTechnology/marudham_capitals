
<?php
include '../../ajaxconfig.php';

$response = array();
$i = 0;
$screen = isset($_POST['screen']) ? $_POST['screen'] : '';
if ($screen == '1') {
    // When screen is passed, all users
    $table = "commitment";
} else if($screen == '2'){
     $table = "request_creation";
}else if($screen == '3'){
     $table = "new_promotion";
}else if($screen == '4'){
     $table = "in_approval";
}else if($screen == '5'){
     $table = "in_acknowledgement";
}else if($screen == '6'){
     $table = "document_track";
}



// Get users who inserted commitment records
$qry = $connect->query("
    SELECT 
        GROUP_CONCAT(DISTINCT u.user_id) AS user_id,
        u.fullname
    FROM $table c
    LEFT JOIN user u ON c.insert_login_id = u.user_id
    WHERE u.user_id IS NOT NULL
    GROUP BY u.fullname
    ORDER BY u.fullname ASC
");

while ($row = $qry->fetch()) {
    $response[$i]['user_id'] = $row['user_id'];
    $response[$i]['username'] = $row['fullname'];
    $i++;
}

echo json_encode($response);
$connect = null;
?>