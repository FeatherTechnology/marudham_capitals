<?php
session_start();
include('../ajaxconfig.php');


if (isset($_POST['noc_details'])) {
    $noc_details = $_POST['noc_details']; // value will be passed in 2 dimentional array// each first dimention has set of date,person,name, req_id
}
if (isset($_POST['table_name'])) {
    $table_name = $_POST['table_name'];
}

$user_id = $_SESSION['userid'];


if ($table_name == 'acknowlegement_documentation') {
    for ($i = 0; $i < sizeof($noc_details); $i++) {

        if ($noc_details[$i][1] == 'process') { // process means mortgage process

            $qry = $connect->query("UPDATE " . $table_name . " set mort_noc_date = DATE(now()), mort_noc_person = '" . $noc_details[$i][2] . "', mort_noc_name = '" . $noc_details[$i][3] . "', update_login_id = $user_id, updated_date = now() where id= '" . $noc_details[$i][0] . "' ");
        } else if ($noc_details[$i][1] == 'document') { // document means mortgage Document

            $qry = $connect->query("UPDATE " . $table_name . " set mort_doc_noc_date = DATE(now()), mort_doc_noc_person = '" . $noc_details[$i][2] . "', mort_doc_noc_name = '" . $noc_details[$i][3] . "', update_login_id = $user_id, updated_date = now() where id= '" . $noc_details[$i][0] . "' ");
        } else if ($noc_details[$i][1] == 'en_process') { // en_process means endorse process

            $qry = $connect->query("UPDATE " . $table_name . " set endor_noc_date = DATE(now()), endor_noc_person = '" . $noc_details[$i][2] . "', endor_noc_name = '" . $noc_details[$i][3] . "', update_login_id = $user_id, updated_date = now() where id= '" . $noc_details[$i][0] . "' ");
        } else if ($noc_details[$i][1] == 'en_rc') { // en_rc means endorse rc

            $qry = $connect->query("UPDATE " . $table_name . " set en_rc_noc_date = DATE(now()), en_rc_noc_person = '" . $noc_details[$i][2] . "', en_rc_noc_name = '" . $noc_details[$i][3] . "', update_login_id = $user_id, updated_date = now() where id= '" . $noc_details[$i][0] . "' ");
        } else if ($noc_details[$i][1] == 'en_key') { // en_key means endorse key

            $qry = $connect->query("UPDATE " . $table_name . " set en_key_noc_date = DATE(now()), en_key_noc_person = '" . $noc_details[$i][2] . "', en_key_noc_name = '" . $noc_details[$i][3] . "', update_login_id = $user_id, updated_date = now() where id= '" . $noc_details[$i][0] . "' ");
        }
    }
} else {
    for ($i = 0; $i < sizeof($noc_details); $i++) {

        $qry = $connect->query("UPDATE " . $table_name . " set noc_date = DATE(now()), noc_person = '" . $noc_details[$i][1] . "', noc_name = '" . $noc_details[$i][2] . "', update_login_id = $user_id, updated_date = now() where id= '" . $noc_details[$i][0] . "' ");
    }
}
//above code will set the noc tables as noc_person, noc_date and all
updateNOCgiven($connect, $user_id);//this function will update noc given to 1 based on selected documents

if ($qry) {
    $response = "Success";
} else {
    $response = "Error";
}

echo $response;


function updateNOCgiven($connect, $user_id)
{

    $req_id = $_POST['req_id'];

    if (isset($_POST['sign_ids'])) {
        $sign_checklist_arr = $_POST['sign_ids'];
        for ($i = 0; $i < sizeof($sign_checklist_arr); $i++) {
            $qry = $connect->query("UPDATE `signed_doc_info` SET `noc_given`='1',update_login_id = $user_id, updated_date = now() WHERE id = '" . $sign_checklist_arr[$i] . "' ");
        }
    }
    if (isset($_POST['cheque_ids'])) {
        $cheque_checklist_arr = $_POST['cheque_ids'];
        for ($i = 0; $i < sizeof($cheque_checklist_arr); $i++) {
            $qry = $connect->query("UPDATE `cheque_no_list` SET `noc_given`='1',update_login_id = $user_id, updated_date = now() WHERE id = '" . $cheque_checklist_arr[$i] . "' ");
        }
    }
    if (isset($_POST['mort_ids'])) {
        $mort_checklist_arr = $_POST['mort_ids'];
        for ($i = 0; $i < sizeof($mort_checklist_arr); $i++) {

            if ($mort_checklist_arr[$i] == 'Mortgage Process noc') {
                $qry = $connect->query("UPDATE `acknowlegement_documentation` SET `mortgage_process_noc`='1',update_login_id = $user_id, updated_date = now() WHERE req_id = '" . $req_id . "' ");
            } elseif ($mort_checklist_arr[$i] == 'Mortgage Document noc') {
                $qry = $connect->query("UPDATE `acknowlegement_documentation` SET `mortgage_document_noc`='1',update_login_id = $user_id, updated_date = now() WHERE req_id = '" . $req_id . "' ");
            }
        }
    }
    if (isset($_POST['endorse_ids'])) {
        $endorse_checklist_arr = $_POST['endorse_ids'];
        for ($i = 0; $i < sizeof($endorse_checklist_arr); $i++) {
            if ($endorse_checklist_arr[$i] == 'Endorsement Process noc') {
                $qry = $connect->query("UPDATE `acknowlegement_documentation` SET `endorsement_process_noc`='1',update_login_id = $user_id, updated_date = now() WHERE req_id = '" . $req_id . "' ");
            } elseif ($endorse_checklist_arr[$i] == 'RC noc') {
                $qry = $connect->query("UPDATE `acknowlegement_documentation` SET `en_RC_noc`='1',update_login_id = $user_id, updated_date = now() WHERE req_id = '" . $req_id . "' ");
            } elseif ($endorse_checklist_arr[$i] == 'Key noc') {
                $qry = $connect->query("UPDATE `acknowlegement_documentation` SET `en_Key_noc`='1',update_login_id = $user_id, updated_date = now() WHERE req_id = '" . $req_id . "' ");
            }
        }
    }
    if (isset($_POST['gold_ids'])) {
        $gold_checklist_arr = $_POST['gold_ids'];
        for ($i = 0; $i < sizeof($gold_checklist_arr); $i++) {
            $qry = $connect->query("UPDATE `gold_info` SET `noc_given`='1',update_login_id = $user_id, updated_date = now() WHERE id = '" . $gold_checklist_arr[$i] . "' ");
        }
    }
    if (isset($_POST['doc_ids'])) {
        $doc_checklist_arr = $_POST['doc_ids'];
        for ($i = 0; $i < sizeof($doc_checklist_arr); $i++) {
            $qry = $connect->query("UPDATE `document_info` SET `doc_info_upload_noc`='1',update_login_id = $user_id, updated_date = now() WHERE id= '" . $doc_checklist_arr[$i] . "'  ");
        }
    }
}

// Close the database connection
$connect = null;