<?php
session_start();
include "../ajaxConfig.php";

try {
    $connect->beginTransaction();

    $reqid   = $_POST['reqId'];
    $cusid   = $_POST['cusId'];
    $user_id = $_SESSION['userid'];

    $check = $connect->prepare("SELECT noc_id FROM noc WHERE req_id = :req_id");
    $check->execute([':req_id' => $reqid]);

    if ($check->rowCount() > 0) {
        throw new Exception('NOC already generated for this request');
    }


    //Insert into noc table //send to handover -23.
    $qry = $connect->prepare("
        INSERT INTO noc 
        (req_id, cus_id, noc_date, noc_replace_status, cus_status, insert_login_id, update_login_id, created_date) 
        VALUES 
        (:req_id, :cus_id, NOW(), 1, 23, :user_id, :userid, NOW())
    ");
    $qry->execute([
        ':req_id'  => $reqid,
        ':cus_id'  => $cusid,
        ':user_id' => $user_id,
        ':userid' => $user_id
    ]);

    $noc_id = $connect->lastInsertId();

    //insert noc checklist table /Signed doc info, cheque info, gold info //because these 3 tables only have same column name so code in loop.
    $tables = [
        'signed_doc_info' => ['noc_tb_colname' => 'sign_id',   'noc_table' => 'noc_sign_checklist'],
        'cheque_no_list'  => ['noc_tb_colname' => 'cheque_id', 'noc_table' => 'noc_cheque_checklist'],
        'gold_info'       => ['noc_tb_colname' => 'gold_id',   'noc_table' => 'noc_gold_checklist'],
    ];

    foreach ($tables as $table => $config) {

        //Update source table
        $update = $connect->prepare("
            UPDATE $table 
            SET 
                noc_date = CURDATE(),
                noc_given = 1,
                update_login_id = :user_id,
                updated_date = NOW()
            WHERE req_id = :req_id
        ");
        $update->execute([
            ':user_id' => $user_id,
            ':req_id'  => $reqid
        ]);

        //Fetch IDs from the source table to insert in checklist table to mark the noc given doc. 
        $select = $connect->prepare("
            SELECT id 
            FROM $table 
            WHERE req_id = :req_id
        ");
        $select->execute([':req_id' => $reqid]);
        $rows = $select->fetchAll(PDO::FETCH_COLUMN);

        //Insert into noc checklist table
        $insert = $connect->prepare("
            INSERT INTO {$config['noc_table']} 
            (noc_id, {$config['noc_tb_colname']}) 
            VALUES 
            (:noc_id, :ref_id)
        ");

        //loop the insert based on the source id getting because store like normalization.
        if (!empty($rows)) {
            foreach ($rows as $refId) {
                $insert->execute([
                    ':noc_id' => $noc_id,
                    ':ref_id' => $refId
                ]);
            }
        }
    }

    //Mortgage document    //Endorsement Document //check mortgage and endorsement for the reqid and based on it update in source table and insert in checklist table.
    $select = $connect->prepare("
    SELECT mortgage_process, mortgage_document, mortgage_document_used,
           endorsement_process, en_RC, en_RC_used, en_Key, en_Key_used
    FROM acknowlegement_documentation
    WHERE req_id = :req_id
    ");
    $select->execute([':req_id' => $reqid]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    $updates = [];

    if ($row['mortgage_process'] == '0') {
        $updates[] = "mort_noc_date = CURDATE(), mortgage_process_noc = 1";

        $insert = $connect->prepare("INSERT INTO noc_mort_checklist (noc_id, mort_id) VALUES (:noc_id, 'Mortgage Process noc')");
        $insert->execute([':noc_id' => $noc_id]);
    }   

    if ($row['mortgage_document'] == '0' && $row['mortgage_document_used'] != '1') {
        $updates[] = "mort_doc_noc_date = CURDATE(), mortgage_document_noc = 1";

        $insert = $connect->prepare("INSERT INTO noc_mort_checklist (noc_id, mort_id) VALUES (:noc_id, 'Mortgage Document noc')");
        $insert->execute([':noc_id' => $noc_id]);
    }

    if ($row['endorsement_process'] == '0') {
        $updates[] = "endor_noc_date = CURDATE(), endorsement_process_noc = 1";

        $insert = $connect->prepare("INSERT INTO noc_endorse_checklist (noc_id, endorse_id) VALUES (:noc_id, 'Endorsement Process noc')");
        $insert->execute([':noc_id' => $noc_id]);
    }

    if ($row['en_RC'] == '0' && $row['en_RC_used'] != '1') {
        $updates[] = "en_rc_noc_date = CURDATE(), en_RC_noc = 1";

        $insert = $connect->prepare("INSERT INTO noc_endorse_checklist (noc_id, endorse_id) VALUES (:noc_id, 'RC noc')");
        $insert->execute([':noc_id' => $noc_id]);
    }

    if ($row['en_Key'] == '0' && $row['en_Key_used'] != '1') {
        $updates[] = "en_key_noc_date = CURDATE(), en_Key_noc = 1";

        $insert = $connect->prepare("INSERT INTO noc_endorse_checklist (noc_id, endorse_id) VALUES (:noc_id, 'Key noc')");
        $insert->execute([':noc_id' => $noc_id]);
    }

    if (!empty($updates)) {
        $sql = "
            UPDATE acknowlegement_documentation 
            SET " . implode(', ', $updates) . ",
                update_login_id = :user_id,
                updated_date = NOW()
            WHERE req_id = :req_id
        ";
        $stmt = $connect->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':req_id'  => $reqid
        ]);
    }


    //Doc info //updating source table and inserting checklist table.
    $update = $connect->prepare("
        UPDATE document_info 
        SET 
            noc_date = CURDATE(),
            doc_info_upload_noc = 1,
            update_login_id = :user_id,
            updated_date = NOW()
        WHERE req_id = :req_id
    ");
    $update->execute([
        ':user_id' => $user_id,
        ':req_id'  => $reqid
    ]);

    $select = $connect->prepare("
        SELECT id 
        FROM document_info 
        WHERE req_id = :req_id
    ");
    $select->execute([':req_id' => $reqid]);
    $rows = $select->fetchAll(PDO::FETCH_COLUMN);

    // Insert into noc checklist table
    $insert = $connect->prepare("
        INSERT INTO noc_doc_checklist 
        (noc_id, doc_id) 
        VALUES 
        (:noc_id, :ref_id)
    ");

    if (!empty($rows)) {
        foreach ($rows as $refId) {
            $insert->execute([
                ':noc_id' => $noc_id,
                ':ref_id' => $refId
            ]);
        }
    }

    //update customer status as IN NOC Handovered - 23.
    $updateQueries = [
        "UPDATE request_creation SET cus_status = 23, update_login_id = '$user_id', updated_date = NOW() WHERE req_id = '$reqid'",
        "UPDATE customer_register SET cus_status = 23 WHERE req_ref_id = '$reqid'",
        "UPDATE in_verification SET cus_status = 23, update_login_id = '$user_id' WHERE req_id = '$reqid'",
        "UPDATE in_approval SET cus_status = 23, update_login_id = '$user_id' WHERE req_id = '$reqid'",
        "UPDATE in_acknowledgement SET cus_status = 23, update_login_id = '$user_id' WHERE req_id = '$reqid'",
        "UPDATE in_issue SET cus_status = 23, update_login_id = '$user_id' WHERE req_id = '$reqid'",
        "UPDATE closed_status SET cus_sts = 23, update_login_id = '$user_id', updated_date = NOW() WHERE req_id = '$reqid'"
    ];

    foreach ($updateQueries as $q) {
        $connect->query($q);
    }

    $connect->commit();
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    $connect->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Close the database connection
$connect = null;
?>