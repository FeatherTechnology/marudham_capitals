<?php
include "../../ajaxconfig.php";

session_start();
$userid = $_SESSION['userid'];

$id = $_POST['id'] ?? '';
    
$sql = $connect->prepare("UPDATE manual_noc_handover_data SET status = ?, update_login_id = ?, updated_date = NOW() WHERE id = ?");
$sql->execute([1, $userid, $id]);

$response = ($sql) ? 'Updated Successfully' : 'Error While Updating';

echo json_encode($response);

// Close the database connection
$connect = null;
?>