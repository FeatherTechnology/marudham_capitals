<?php
session_start();
include('../ajaxconfig.php');

try {
    // ✅ Start transaction
    $connect->beginTransaction();

    $req_id   = $_POST['req_id'] ?? '';
    $noc_member = $_POST['noc_member'] ?? '';
    $mem_name   = $_POST['mem_name'] ?? '';

    $noc_handover_date = date("Y-m-d");
    $userid  = $_SESSION['userid'] ?? '';

    if (empty($req_id)) {
        throw new Exception("Request ID missing");
    }

    // --- UPDATE QUERY FIXED ---
    $qry = $connect->prepare("
        UPDATE noc 
        SET 
            noc_member = :noc_member,
            mem_name = :mem_name,
            noc_handover_date = :noc_handover_date,
            cus_status = 24,
            update_login_id = :user_id,
            updated_date = NOW()
        WHERE req_id = :req_id
    ");

    $qry->bindParam(':noc_member', $noc_member);
    $qry->bindParam(':mem_name', $mem_name);
    $qry->bindParam(':noc_handover_date', $noc_handover_date);
    $qry->bindParam(':user_id', $userid);
    $qry->bindParam(':req_id', $req_id);

    $qry->execute();

    // Update request_creation
    $stmt = $connect->prepare(
        "UPDATE request_creation 
        SET cus_status = 24, updated_date = NOW(), update_login_id = ? 
        WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update customer_register
    $stmt = $connect->prepare(
        "UPDATE customer_register 
        SET cus_status = 24 
        WHERE req_ref_id = ?"
    );
    $stmt->execute([$req_id]);

    // Update in_verification
    $stmt = $connect->prepare(
        "UPDATE in_verification 
        SET cus_status = 24, update_login_id = ? 
        WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_approval
    $stmt = $connect->prepare(
        "UPDATE in_approval 
        SET cus_status = 24, update_login_id = ?
        WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_acknowledgement
    $stmt = $connect->prepare(
        "UPDATE in_acknowledgement 
        SET cus_status = 24, update_login_id = ?
        WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);    
    
    // Update in_issue
    $stmt = $connect->prepare(
        "UPDATE in_issue 
        SET cus_status = 24, update_login_id = ?
        WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);
    
    // Update closed_status
    $stmt = $connect->prepare(
        "UPDATE closed_status 
        SET cus_sts = 24, update_login_id = ?, updated_date = NOW()
        WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // ✅ Commit ONLY if everything succeeds
    $connect->commit();

    $response = json_encode([
        'status' => 'success'
    ]);

} catch (Exception $e) {
    // ✅ Rollback on ANY error
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    $response = json_encode([
        'status' => 'Failed',
        'Error' => "Error: " . $e->getMessage()
    ]);
}

echo $response;

$connect = null;
