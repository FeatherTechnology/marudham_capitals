<?php 
session_start();
include('../ajaxconfig.php');

$userid = $_SESSION['userid'] ?? '';
$req_id = $_POST['req_id'] ?? '';
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']) ?? '';

//Move to Issue = 13.
try {
    // ✅ Start transaction
    $connect->beginTransaction();

    //Closed  Completed And Move to NOC = 21.

    $connect->query("UPDATE request_creation set cus_status = 21,updated_date = now(), update_login_id = $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ");
    $connect->query("UPDATE customer_register set cus_status = 21 WHERE cus_id = '".$cus_id."' and req_ref_id = '".$req_id."' ");
    $connect->query("UPDATE in_verification set cus_status = 21, update_login_id = $userid WHERE cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ");
    $connect->query("UPDATE `in_approval` SET `cus_status`= 21,`update_login_id`= $userid WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ");
    $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 21,`update_login_id`= $userid and updated_date=now() WHERE  cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ");
    $connect->query("UPDATE `in_issue` SET `cus_status`= 21,`update_login_id` = $userid where cus_id = '".$cus_id."' and req_id = '".$req_id."' && cus_status = '20' ");
    $connect->query("UPDATE `closed_status` SET `cus_sts`='21',`update_login_id`=$userid,`updated_date`= now() WHERE `cus_sts`='20' and req_id = '".$req_id."' && `cus_id`='".$cus_id."' ");

    // ✅ Commit ONLY if everything succeeds
    $connect->commit();

    echo json_encode("Customer Moved to NOC");

} catch (Exception $e) {

    // ✅ Rollback on ANY error
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    echo json_encode("Error While Moving to NOC: " . $e->getMessage());
}

// Close the database connection
$connect = null;
?>