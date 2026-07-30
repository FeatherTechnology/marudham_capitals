<?php
session_start();

$userid = $_SESSION['userid'];
include('../../ajaxconfig.php');
$cus_id = $_POST['cus_id'] ?? '';
$closed_Sts = $_POST['closed_Sts'] ?? '';

// Get the latest req_id for this customer
$stmt = $connect->prepare("SELECT req_id FROM closed_status WHERE cus_id = :cus_id ORDER BY created_date DESC, req_id DESC LIMIT 1");
$stmt->execute([':cus_id' => $cus_id]);
$latest = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$latest) {
    echo "Error: No closed status found for this customer";
    exit;
}
$req_id = $latest['req_id'];
// Update only the latest customer entry
$update = $connect->prepare("UPDATE closed_status SET closed_sts = :closed_sts,consider_level = '',screen =2,update_login_id = :update_login_id,updated_date = NOW() WHERE req_id = :req_id AND cus_id = :cus_id");
$sql = $update->execute([  ':closed_sts' => $closed_Sts,':update_login_id' => $userid,':req_id' => $req_id,':cus_id' => $cus_id]);
echo $sql ? 'Closed Status Updated Successfully': 'Error While Update';
$connect = null;
?>