<?php
session_start();
include('../ajaxconfig.php');

$user_id = $_SESSION['userid'];

// JSON payload from JS
$payload = json_decode($_POST['data'], true);

$req_id = $payload['req_id'];

// Extract arrays from payload
$sign_details     = $payload['sign'];
$cheque_details   = $payload['cheque'];
$mort_details     = $payload['mort'];     
$endorse_details  = $payload['endorse'];  
$gold_details     = $payload['gold'];     
$other_doc_details    = $payload['other'];    

// ----------------------------------------------------
// SIGNED DOCUMENTS
// ----------------------------------------------------
foreach ($sign_details as $row){
    $id = $row[0];

    $connect->query("
        UPDATE signed_doc_info 
        SET noc_date = CURDATE(), noc_given = 1, update_login_id = '$user_id', updated_date = NOW() 
        WHERE id = '$id'
    ");
}

// ----------------------------------------------------
// CHEQUE LIST
// ----------------------------------------------------
foreach ($cheque_details as $row){
    $id = $row[0];

    $connect->query("
        UPDATE cheque_no_list 
        SET noc_date = CURDATE(), noc_given = 1, update_login_id = '$user_id', updated_date = NOW() 
        WHERE id = '$id'
    ");
}

// ----------------------------------------------------
// MORTGAGE DOCUMENTS
// ----------------------------------------------------
foreach ($mort_details as $row){
    $id   = $row[0];
    $mort_type = $row[1];

    if($mort_type == 'process'){
        $connect->query("
            UPDATE acknowlegement_documentation 
            SET mort_noc_date = CURDATE(), mortgage_process_noc = 1, update_login_id = '$user_id', updated_date = NOW() 
            WHERE id = '$id'
        ");
    }
    elseif($mort_type == 'document'){
        $connect->query("
            UPDATE acknowlegement_documentation 
            SET mort_doc_noc_date = CURDATE(), mortgage_document_noc = 1, update_login_id = '$user_id', updated_date = NOW() 
            WHERE id = '$id'
        ");
    }
}

// ----------------------------------------------------
// ENDORSEMENT DOCUMENTS
// ----------------------------------------------------
foreach ($endorse_details as $row){
    $id   = $row[0];
    $type = $row[1];

    if($type == 'en_process'){
        $connect->query("
            UPDATE acknowlegement_documentation 
            SET endor_noc_date = CURDATE(), endorsement_process_noc = 1, update_login_id = '$user_id', updated_date = NOW() 
            WHERE id = '$id'
        ");
    }
    elseif($type == 'en_rc'){
        $connect->query("
            UPDATE acknowlegement_documentation 
            SET en_rc_noc_date = CURDATE(), en_RC_noc = 1, update_login_id = '$user_id', updated_date = NOW() 
            WHERE id = '$id'
        ");
    }
    elseif($type == 'en_key'){
        $connect->query("
            UPDATE acknowlegement_documentation 
            SET en_key_noc_date = CURDATE(), en_Key_noc = 1, update_login_id = '$user_id', updated_date = NOW() 
            WHERE id = '$id'
        ");
    }
}

// ----------------------------------------------------
// GOLD
// ----------------------------------------------------
foreach ($gold_details as $row){
    $id     = $row[0];
    $person = $row[1];
    $name   = $row[2];

    $connect->query("
        UPDATE gold_info 
        SET noc_date = CURDATE(), noc_given = 1, update_login_id = '$user_id', updated_date = NOW()
        WHERE id = '$id'
    ");
}

// ----------------------------------------------------
// OTHER DOCUMENTS
// ----------------------------------------------------
foreach ($other_doc_details as $row){
    $id = $row[0];

    $connect->query("
        UPDATE document_info 
        SET  noc_date = CURDATE(), doc_info_upload_noc = 1, update_login_id = '$user_id', updated_date = NOW() 
        WHERE id = '$id'
    ");
}


// ----------------------------------------------------
// UPDATE FINAL STATUS BY CHECKING ALL NOC COMPLETION
// ----------------------------------------------------
updateNOCgiven($connect, $user_id, $req_id);

echo "Success";


// =======================================================================
// ===============  FINAL STATUS UPDATE FUNCTION (FULL)  =================
// =======================================================================

function updateNOCgiven($connect, $user_id, $req_id)
{
    // ----------------------------
    // CHECK STATUS FOR 4 TABLES
    // ----------------------------
    $tables = [
        'signed_doc_info' => ['prefix' => 'sign', 'field' => 'noc_given'],
        'cheque_no_list' => ['prefix' => 'cheque', 'field' => 'noc_given'],
        'document_info' => ['prefix' => 'doc', 'field' => 'doc_info_upload_noc'],
        'gold_info' => ['prefix' => 'gold', 'field' => 'noc_given']
    ];

    $statusArr = [];

    foreach ($tables as $table => $info) {

        // Total records
        $totalQry = $connect->query("SELECT COUNT(*) AS cnt FROM $table WHERE req_id = '$req_id'");
        $total = $totalQry->fetch()['cnt'];

        // Completed NOC records
        $givenQry = $connect->query("
            SELECT COUNT(*) AS cnt 
            FROM $table 
            WHERE req_id = '$req_id' AND {$info['field']} = '1'
        ");
        $given = $givenQry->fetch()['cnt'];

        // Status logic
        if ($given == $total ) {
            $statusArr[$info['prefix']] = 2; // All completed
        } elseif ($given > 0) {
            $statusArr[$info['prefix']] = 1; // Partial
        } else {
            $statusArr[$info['prefix']] = 0; // None
        }
    }

    // ----------------------------------------------------
    // CHECK MORTGAGE & ENDORSEMENT IN acknowlegement_documentation
    // ----------------------------------------------------
    $ackQry = $connect->query("
        SELECT 
            mortgage_process,
            mortgage_process_noc,
            mortgage_document_noc,
            endorsement_process,
            endorsement_process_noc,
            mortgage_document,
            en_RC,
            en_RC_noc,
            en_Key,
            en_Key_noc
        FROM acknowlegement_documentation
        WHERE req_id = '$req_id'
    ");

    $ack = $ackQry->fetch();

    // ----------------- MORTGAGE -----------------
    $mort_total = 2;
    $mort_given = 0;

    if ($ack['mortgage_process'] == '1') {
        $mort_status = 2;
    } else {
        if ($ack['mortgage_process_noc'] == '1') $mort_given++;
        if ($ack['mortgage_document_noc'] == '1') $mort_given++;

        $mort_status = ($mort_given == $mort_total) ? 2 :
                       (($mort_given > 0) ? 1 : 0);
    }
  $mort_total = 0;
   $mort_given = 0;

if ($ack['mortgage_process'] == '0')          $mort_total++;
if ($ack['mortgage_process_noc'] == '1')      $mort_given++;

if ($ack['mortgage_document'] == '0')           $mort_total++;
if ($ack['mortgage_document_noc'] == '1')       $mort_given++;

$mort_status = ($mort_total == $mort_given)
                    ? 2
                    : (($mort_given > 0) ? 1 : 0);
    // ----------------- ENDORSE -----------------
   $endorse_total = 0;
$endorse_given = 0;

if ($ack['endorsement_process'] == '0')          $endorse_total++;
if ($ack['endorsement_process_noc'] == '1')      $endorse_given++;

if ($ack['en_RC'] == '0')                       $endorse_total++;
if ($ack['en_RC_noc'] == '1')                   $endorse_given++;

if ($ack['en_Key'] == '0')                      $endorse_total++;
if ($ack['en_Key_noc'] == '1')                  $endorse_given++;

$endorse_status = ($endorse_total == $endorse_given)
                    ? 2
                    : (($endorse_given > 0) ? 1 : 0);


    // ----------------------------------------------------
    // IF ALL NOC ARE COMPLETED → UPDATE MASTER STATUS
    // ----------------------------------------------------
    if (
        $statusArr['sign'] == 2 &&
        $statusArr['cheque'] == 2 &&
        $mort_status == 2 &&
        $endorse_status == 2 &&
        $statusArr['doc'] == 2 &&
        $statusArr['gold'] == 2
    ) {
        $updateQueries = [
            "UPDATE request_creation SET cus_status = 22, update_login_id = '$user_id', updated_date = NOW() WHERE req_id = '$req_id'",
            "UPDATE customer_register SET cus_status = 22 WHERE req_ref_id = '$req_id'",
            "UPDATE in_verification SET cus_status = 22, update_login_id = '$user_id' WHERE req_id = '$req_id'",
            "UPDATE in_approval SET cus_status = 22, update_login_id = '$user_id' WHERE req_id = '$req_id'",
            "UPDATE in_acknowledgement SET cus_status = 22, update_login_id = '$user_id' WHERE req_id = '$req_id'",
            "UPDATE in_issue SET cus_status = 22, update_login_id = '$user_id' WHERE req_id = '$req_id'",
            "UPDATE closed_status SET cus_sts = 22, update_login_id = '$user_id', updated_date = NOW() WHERE req_id = '$req_id'",
            "UPDATE noc SET cus_status = 22, update_login_id = '$user_id', updated_date = NOW() WHERE req_id = '$req_id'"
        ];

        foreach ($updateQueries as $q) {
            $connect->query($q);
        }
    }
}
?>
