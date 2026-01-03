<?php
session_start();
include('../ajaxconfig.php');

$req_id = $_POST['req_id'] ?? '';
$cus_id = $_POST['cus_id'] ?? '';
$userid = $_SESSION['userid'] ?? '';

try {
    // Start transaction
    $connect->beginTransaction();

    /* 1️⃣ Get cus_reg_id */
    $stmt = $connect->prepare(
        "SELECT cus_reg_id FROM customer_register WHERE cus_id = ?"
    );
    $stmt->execute([$cus_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Customer not found");
    }

    $cus_reg_id = $row['cus_reg_id'];

    /* 2️⃣ Update request_creation */
    $stmt = $connect->prepare(
        "UPDATE request_creation
         SET cus_reg_id = ?, cus_status = 1, updated_date = NOW(), update_login_id = ?
         WHERE req_id = ?"
    );
    $stmt->execute([$cus_reg_id, $userid, $req_id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Request creation not updated");
    }

    /* 3️⃣ Update customer_register */
    $stmt = $connect->prepare(
        "UPDATE customer_register
         SET cus_status = 1, updated_date = NOW()
         WHERE req_ref_id = ?"
    );
    $stmt->execute([$req_id]);

    /* 4️⃣ Insert into in_verification */
    $stmt = $connect->prepare(
        "INSERT INTO in_verification (
            `req_id`,`user_type`,`user_name`,`agent_id`,`responsible`,`remarks`,
            `declaration`,`req_code`,`dor`,`cus_reg_id`,`cus_id`,`cus_data`,
            `cus_name`,`dob`,`age`,`gender`,`state`,`district`,`taluk`,`area`,
            `sub_area`,`address`,`mobile1`,`mobile2`,`father_name`,`mother_name`,
            `marital`,`spouse_name`,`occupation_type`,`occupation`,`pic`,
            `loan_category`,`sub_category`,`tot_value`,`ad_amt`,`ad_perc`,
            `loan_amt`,`poss_type`,`due_amt`,`due_period`,`cus_status`,
            `prompt_remark`,`status`,`insert_login_id`,`update_login_id`,
            `delete_login_id`,`created_date`,`updated_date`
        )
        SELECT
            `req_id`,`user_type`,`user_name`,`agent_id`,`responsible`,`remarks`,
            `declaration`,`req_code`,`dor`,`cus_reg_id`,`cus_id`,`cus_data`,
            `cus_name`,`dob`,`age`,`gender`,`state`,`district`,`taluk`,`area`,
            `sub_area`,`address`,`mobile1`,`mobile2`,`father_name`,`mother_name`,
            `marital`,`spouse_name`,`occupation_type`,`occupation`,`pic`,
            `loan_category`,`sub_category`,`tot_value`,`ad_amt`,`ad_perc`,
            `loan_amt`,`poss_type`,`due_amt`,`due_period`,`cus_status`,
            `prompt_remark`,`status`,`insert_login_id`,`update_login_id`,
            `delete_login_id`,CURRENT_TIMESTAMP,`updated_date`
        FROM request_creation
        WHERE req_id = ?"
    );
    $stmt->execute([$req_id]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Verification insert failed");
    }

    /* 5️⃣ Update created_date explicitly */
    $stmt = $connect->prepare(
        "UPDATE in_verification
         SET created_date = CURRENT_TIMESTAMP
         WHERE req_id = ?"
    );
    $stmt->execute([$req_id]);

    // Commit transaction
    $connect->commit();

    echo json_encode("Moved to Verification");

} catch (Exception $e) {

    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    echo json_encode("Transaction Failed: " . $e->getMessage());
}

$connect = null;
