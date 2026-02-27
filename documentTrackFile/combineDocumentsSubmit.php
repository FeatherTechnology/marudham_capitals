<?php
include "../ajaxconfig.php";
session_start();

$userid = $_SESSION['userid'] ?? '';
$req_id = $_POST['currentReqId'] ?? '';

if (!$req_id) {
    exit("Invalid req_id");
}


try{
    // ✅ Start transaction
    $connect->beginTransaction();

    /**
     * STEP 1: Get all old req_id (replace req_ids)
     */
    $qry = $connect->prepare("
        SELECT ad.req_id 
        FROM acknowlegement_documentation ad
        JOIN doc_replace_ids dri 
            ON ad.doc_id = dri.replace_doc_id
        WHERE dri.req_id = ?
    ");
    $qry->execute([$req_id]);

    $replace_req_ids = $qry->fetchAll(PDO::FETCH_COLUMN);


    /**
     * STEP 2: Get cus_profile_id of new req_id
     */
    $qry2 = $connect->prepare("
        SELECT cus_id_doc, cus_profile_id 
        FROM acknowlegement_documentation
        WHERE req_id = ?
    ");
    $qry2->execute([$req_id]);

    $info = $qry2->fetch(PDO::FETCH_ASSOC);
    $cus_id_doc = $info['cus_id_doc'];
    $cus_profile_id = $info['cus_profile_id'];


    /**
     * STEP 3: Loop old req_ids and copy data into new req_id
     */
    if (!empty($replace_req_ids)) {

        foreach ($replace_req_ids as $old_req_id) {

            // signed_doc_info
            $connect->prepare("
                INSERT INTO signed_doc_info
                (cus_id, doc_name, sign_type, signType_relationship, doc_Count, req_id, cus_profile_id, replace_status)
                SELECT cus_id, doc_name, sign_type, signType_relationship, doc_Count, ?, ?, 1
                FROM signed_doc_info
                WHERE req_id = ?
            ")->execute([$req_id, $cus_profile_id, $old_req_id]);


            // cheque_info
            // STEP 1: fetch old cheque_info rows
            $getChequeInfo = $connect->prepare("
                SELECT *
                FROM cheque_info
                WHERE req_id = ?
            ");
            $getChequeInfo->execute([$old_req_id]);

            $chequeInfos = $getChequeInfo->fetchAll(PDO::FETCH_ASSOC);

            foreach ($chequeInfos as $chequeRow) {

                // STEP 2: insert into cheque_info (parent)
                $insertChequeInfo = $connect->prepare("
                    INSERT INTO cheque_info
                    (cus_id, req_id, cus_profile_id, holder_type, holder_name,
                    holder_relationship_name, cheque_relation, chequebank_name, cheque_count, replace_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
                ");

                $insertChequeInfo->execute([
                    $chequeRow['cus_id'],
                    $req_id,
                    $cus_profile_id,
                    $chequeRow['holder_type'],
                    $chequeRow['holder_name'],
                    $chequeRow['holder_relationship_name'],
                    $chequeRow['cheque_relation'],
                    $chequeRow['chequebank_name'],
                    $chequeRow['cheque_count']
                ]);

                // STEP 3: get NEW cheque_info ID
                $new_cheque_id = $connect->lastInsertId();

                $old_cheque_id = $chequeRow['id']; // old parent id


                // STEP 4: insert cheque_no_list (child)
                $connect->prepare("
                    INSERT INTO cheque_no_list
                    (cus_id, req_id, cheque_table_id, cheque_holder_type, cheque_holder_name, cheque_no)
                    SELECT cus_id, ?, ?, cheque_holder_type, cheque_holder_name, cheque_no
                    FROM cheque_no_list
                    WHERE cheque_table_id = ?
                ")->execute([$req_id, $new_cheque_id, $old_cheque_id]);


                // STEP 5: insert cheque_upd (child)
                $connect->prepare("
                    INSERT INTO cheque_upd
                    (cus_id, req_id, cheque_table_id, upload_cheque_name)
                    SELECT cus_id, ?, ?, upload_cheque_name
                    FROM cheque_upd
                    WHERE cheque_table_id = ?
                ")->execute([$req_id, $new_cheque_id, $old_cheque_id]);

            }


            // gold_info
            $connect->prepare("
                INSERT INTO gold_info
                (cus_id, req_id, gold_sts, gold_type, Purity, gold_Count,
                gold_Weight, gold_Value, gold_upload, replace_status)
                SELECT cus_id, ?, gold_sts, gold_type, Purity, gold_Count,
                    gold_Weight, gold_Value, gold_upload, 1
                FROM gold_info
                WHERE req_id = ?
            ")->execute([$req_id, $old_req_id]);


            // document_info
            $connect->prepare("
                INSERT INTO document_info
                (cus_id, req_id, doc_name, doc_detail, doc_type, doc_holder,
                holder_name, relation_name, relation, replace_status, insert_login_id, created_date)
                SELECT cus_id, ?, doc_name, doc_detail, doc_type, doc_holder,
                    holder_name, relation_name, relation, 1, ?, NOW()
                FROM document_info
                WHERE req_id = ?
            ")->execute([$req_id, $userid, $old_req_id]);


            /**
             * STEP 4: Copy acknowlegement_documentation (single row table)
             * Update NEW req_id using OLD req_id values
             */
            $check = $connect->prepare("SELECT mortgage_process, endorsement_process FROM acknowlegement_documentation WHERE req_id = ?");
            $check->execute([$old_req_id]);

            $data = $check->fetch(PDO::FETCH_ASSOC);

            if ($data) {

                $mortgage = (int)$data['mortgage_process'];
                $endorsement = (int)$data['endorsement_process'];

                if ($mortgage === 0 || $endorsement === 0) {
                    // insert
                    $connect->prepare("
                        UPDATE acknowlegement_documentation new_ad
                        JOIN acknowlegement_documentation old_ad 
                            ON old_ad.req_id = ?
                        SET 
                            new_ad.mortgage_process = old_ad.mortgage_process,
                            new_ad.Propertyholder_type = old_ad.Propertyholder_type,
                            new_ad.Propertyholder_name = old_ad.Propertyholder_name,
                            new_ad.Propertyholder_relationship_name = old_ad.Propertyholder_relationship_name,
                            new_ad.doc_property_relation = old_ad.doc_property_relation,
                            new_ad.doc_property_type = old_ad.doc_property_type,
                            new_ad.doc_property_measurement = old_ad.doc_property_measurement,
                            new_ad.doc_property_location = old_ad.doc_property_location,
                            new_ad.doc_property_value = old_ad.doc_property_value,
                            new_ad.mortgage_name = old_ad.mortgage_name,
                            new_ad.mortgage_dsgn = old_ad.mortgage_dsgn,
                            new_ad.mortgage_nuumber = old_ad.mortgage_nuumber,
                            new_ad.reg_office = old_ad.reg_office,
                            new_ad.mortgage_value = old_ad.mortgage_value,
                            new_ad.mortgage_document = old_ad.mortgage_document,
                            new_ad.mortgage_document_upd = old_ad.mortgage_document_upd,
                            new_ad.endorsement_process = old_ad.endorsement_process,
                            new_ad.owner_type = old_ad.owner_type,
                            new_ad.owner_name = old_ad.owner_name,
                            new_ad.ownername_relationship_name = old_ad.ownername_relationship_name,
                            new_ad.en_relation = old_ad.en_relation,
                            new_ad.vehicle_type = old_ad.vehicle_type,
                            new_ad.vehicle_process = old_ad.vehicle_process,
                            new_ad.en_Company = old_ad.en_Company,
                            new_ad.en_Model = old_ad.en_Model,
                            new_ad.vehicle_reg_no = old_ad.vehicle_reg_no,
                            new_ad.endorsement_name = old_ad.endorsement_name,
                            new_ad.en_RC = old_ad.en_RC,
                            new_ad.en_Key = old_ad.en_Key,
                            new_ad.replace_status = 1,
                            new_ad.update_login_id = ?,
                            new_ad.updated_date = NOW()
                        WHERE new_ad.req_id = ?
                    ")->execute([$old_req_id, $userid, $req_id]);
                }
            }
        }
    }


    $connect->query("UPDATE document_track SET track_status = 3, update_login_id = $userid, updated_date = NOW() WHERE req_id = '$req_id'"); //After Combine doc directly removed from list.
    
    $connect->query("UPDATE noc SET noc_replace_status = 2 WHERE cus_id = '$cus_id_doc' AND noc_replace_status = 1 "); //update noc table for replace noc.

    // ✅ Commit ONLY if everything succeeds
    $connect->commit();

    echo json_encode("Documents Combined successfully");

} catch (Exception $e) {

    // ✅ Rollback on ANY error
    if ($connect->inTransaction()) {
        $connect->rollBack();
    }

    echo json_encode("Transaction Failed: " . $e->getMessage());
}

// Close the database connection
$connect = null;
?>