<?php 
session_start();
include('../ajaxconfig.php');

$userid = $_SESSION['userid'] ?? '';
$req_id = $_POST['req_id'] ?? '';
$cus_id = preg_replace('/\D/', '', $_POST['cus_id']) ?? '';

try {
    // ✅ Start transaction
    $connect->beginTransaction();

    //Closed  Completed And Move to NOC = 21.

    // Update request_creation
    $stmt = $connect->prepare(
        "UPDATE request_creation 
         SET cus_status = 21, updated_date = NOW(), update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update customer_register
    $stmt = $connect->prepare(
        "UPDATE customer_register 
         SET cus_status = 21
         WHERE cus_id = ? AND req_ref_id = ?"
    );
    $stmt->execute([$cus_id, $req_id]);

    // Update in_verification
    $stmt = $connect->prepare(
        "UPDATE in_verification 
         SET cus_status = 21, update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_approval
    $stmt = $connect->prepare(
        "UPDATE in_approval 
         SET cus_status = 21, update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_acknowledgement
    $stmt = $connect->prepare(
        "UPDATE in_acknowledgement 
         SET cus_status = 21, updated_date = NOW(), update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_issue
    $stmt = $connect->prepare(
        "UPDATE in_issue 
         SET cus_status = 21, update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update closed_status
    $stmt = $connect->prepare(
        "UPDATE closed_status 
         SET cus_sts = 21, update_login_id = ?, updated_date = NOW() 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

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