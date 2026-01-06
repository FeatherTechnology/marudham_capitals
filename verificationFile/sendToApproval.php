<?php
session_start();
include('../ajaxconfig.php');

$req_id = $_POST['req_id'] ?? '';
$cus_id = $_POST['cus_id'] ?? '';
$userid = $_SESSION['userid'] ?? '';

try {
    // ✅ Start transaction
    $connect->beginTransaction();

    // Update request_creation
    $stmt = $connect->prepare(
        "UPDATE request_creation 
         SET cus_status = 2, updated_date = NOW(), update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update customer_register
    $stmt = $connect->prepare(
        "UPDATE customer_register 
         SET cus_status = 2, updated_date = NOW() 
         WHERE req_ref_id = ?"
    );
    $stmt->execute([$req_id]);

    // Update in_verification
    $stmt = $connect->prepare(
        "UPDATE in_verification 
         SET cus_status = 2, updated_date = NOW(), update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Insert into in_approval
    $stmt = $connect->prepare(
        "INSERT INTO in_approval 
        (req_id, cus_id, cus_status, status, insert_login_id, created_date)
        SELECT req_id, cus_id, cus_status, status, update_login_id, CURRENT_TIMESTAMP
        FROM in_verification
        WHERE req_id = ?"
    );
    $stmt->execute([$req_id]);

    // Delete loan_followup
    $stmt = $connect->prepare(
        "DELETE FROM loan_followup WHERE cus_id = ?"
    );
    $stmt->execute([$cus_id]);

    // ✅ Commit ONLY if everything succeeds
    $connect->commit();

    echo json_encode("Moved to Approval");

} catch (Exception $e) {

    // ✅ Rollback on ANY error
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    echo json_encode("Transaction Failed: " . $e->getMessage());
}

$connect = null;