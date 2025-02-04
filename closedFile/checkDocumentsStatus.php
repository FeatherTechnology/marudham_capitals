<?php
session_start();
include '../ajaxconfig.php';

if (isset($_POST["req_id"])) {
    $req_id = $_POST["req_id"];
}

$response1 = 'completed';

// $sts_qry = $connect->query("SELECT id, doc_Count FROM signed_doc_info WHERE req_id = '$req_id'");
// if ($sts_qry->num_rows > 0) {
//     while ($sts_row = $sts_qry->fetch_assoc()) {
//         $sts_qry1 = $connect->query("SELECT * FROM signed_doc WHERE req_id = '$req_id' AND signed_doc_id = '" . $sts_row['id'] . "'");
//         if ($sts_qry1->num_rows == $sts_row['doc_Count'] && $response1 != 'pending') {
//             $response1 = 'completed';
//         } else {
//             $response1 = 'pending';
//         }
//     }
// }

$response2 = 'completed';
// $sts_qry = $connect->query("SELECT id, cheque_count FROM cheque_info WHERE req_id = '$req_id'");
// if ($sts_qry->num_rows > 0) {
//     while ($sts_row = $sts_qry->fetch_assoc()) {
//         $sts_qry1 = $connect->query("SELECT * FROM cheque_upd WHERE req_id = '$req_id' AND cheque_table_id = '" . $sts_row['id'] . "'");
//         if ($sts_qry1->num_rows == $sts_row['cheque_count'] && $response2 != 'pending') {
//             $response2 = 'completed';
//         } else {
//             $response2 = 'pending';
//         }
//     }
// }

$response3 = 'completed';
$sts_qry = $connect->query("SELECT mortgage_process, mortgage_document_pending, endorsement_process, Rc_document_pending FROM acknowlegement_documentation WHERE req_id = '$req_id'");
if ($sts_qry->rowCount() > 0) {
    while ($sts_row = $sts_qry->fetch()) {
        if ($sts_row['mortgage_process'] == '0') {
            if ($sts_row['mortgage_document_pending'] == 'YES') {
                $response3 = 'pending';
            }
        }
        if ($sts_row['endorsement_process'] == '0') {
            if ($sts_row['Rc_document_pending'] == 'YES') {
                $response3 = 'pending';
            }
        }
    }
}

$response4 = 'completed';
// $sts_qry = $connect->query("SELECT * FROM document_info WHERE req_id = '$req_id'");
// if ($sts_qry->num_rows > 0) {
//     while ($sts_row = $sts_qry->fetch_assoc()) {
//         if ($sts_row['doc_upload'] == '' || $sts_row['doc_upload'] == null) {
//             $response4 = 'pending';
//         }
//     }
// }

if ($response1 == 'completed' && $response2 == 'completed' && $response3 == 'completed' && $response4 == 'completed') {
    $response = 'true';
} else {
    $response = 'false';
}

echo $response;

// Close the database connection
$connect = null;
?>
