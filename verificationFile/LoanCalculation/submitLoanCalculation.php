<?php
include "../../ajaxconfig.php";
session_start();

if (isset($_SESSION['userid'])) {
    $userid  = $_SESSION['userid'];
}

if (isset($_POST['cus_id_loan'])) {
    $cus_id_loan = $_POST['cus_id_loan'];
}
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_name_loan'])) {
    $cus_name_loan = $_POST['cus_name_loan'];
}
if (isset($_POST['cus_data_loan'])) {
    $cus_data_loan = $_POST['cus_data_loan'];
}
if (isset($_POST['mobile_loan'])) {
    $mobile_loan = $_POST['mobile_loan'];
}
if (isset($_POST['pic_loan'])) {
    $pic_loan = $_POST['pic_loan'];
}
if (isset($_POST['loan_category_ack'])) {
    $loan_category = $_POST['loan_category_ack'];
}
if (isset($_POST['sub_category_ack'])) {
    $sub_category = $_POST['sub_category_ack'];
}
if (isset($_POST['category_info'])) {
    $category_info = $_POST['category_info'];
}
$tot_value = '';
if (isset($_POST['tot_value'])) {
    $tot_value = str_replace([',', ' '], '', $_POST['tot_value']);
}
$ad_amt = '';
if (isset($_POST['ad_amt'])) {
    $ad_amt = str_replace([',', ' '], '', $_POST['ad_amt']);
}
if (isset($_POST['loan_amt'])) {
    $loan_amt = str_replace([',', ' '], '', $_POST['loan_amt']);
}
if (isset($_POST['profit_type_ack'])) {
    $profit_type = $_POST['profit_type_ack'];
}
$due_method_calc = '';
if (isset($_POST['due_method_calc'])) {
    $due_method_calc = $_POST['due_method_calc'];
    if ($profit_type == '2') {
        $due_method_calc = '';
    }
}
$due_type = '';
if (isset($_POST['due_type'])) {
    $due_type = $_POST['due_type'];
    if ($profit_type == '2') {
        $due_type = '';
    }
}
$profit_method = '';
if (isset($_POST['profit_method_ack'])) {
    $profit_method = $_POST['profit_method_ack'];
    if ($profit_type == '2') {
        $profit_method = '';
    }
}
$calc_method = '';
if (isset($_POST['calc_method'])) {
    $calc_method = $_POST['calc_method'];
    if ($profit_type == '2') {
        $calc_method = '';
    }
}
$due_method_scheme = '';
if (isset($_POST['due_method_scheme_ack'])) {
    $due_method_scheme = $_POST['due_method_scheme_ack'];
    if ($profit_type == '1') {
        $due_method_scheme = '';
    }
}
$day_scheme = '';
if (isset($_POST['day_scheme_ack'])) {
    $day_scheme = $_POST['day_scheme_ack'];
    if ($profit_type == '1') {
        $day_scheme = '';
    }
}
$scheme_profit_method = '';
if (isset($_POST['profit_method_scheme_ack'])) {
    $scheme_profit_method = $_POST['profit_method_scheme_ack'];
    if ($profit_type == '1') {
        $scheme_profit_method = '';
    }
}
$scheme_name = '';
if (isset($_POST['scheme_name_ack'])) {
    $scheme_name = $_POST['scheme_name_ack'];
    if ($profit_type == '1') {
        $scheme_name = '';
    }
}
if (isset($_POST['int_rate'])) {
    $int_rate = $_POST['int_rate'];
}
if (isset($_POST['due_period'])) {
    $due_period = $_POST['due_period'];
}
if (isset($_POST['doc_charge'])) {
    $doc_charge = $_POST['doc_charge'];
}
if (isset($_POST['proc_fee'])) {
    $proc_fee = $_POST['proc_fee'];
}
if (isset($_POST['loan_amt_cal'])) {
    $loan_amt_cal = str_replace([',', ' '], '', $_POST['loan_amt_cal']);
}
if (isset($_POST['principal_amt_cal'])) {
    $principal_amt_cal = str_replace([',', ' '], '', $_POST['principal_amt_cal']);
}
if (isset($_POST['int_amt_cal'])) {
    $int_amt_cal = str_replace([',', ' '], '', $_POST['int_amt_cal']);
}
$tot_amt_cal = '';
if (isset($_POST['tot_amt_cal'])) {
    $tot_amt_cal = str_replace([',', ' '], '', $_POST['tot_amt_cal']);
}
$due_amt_cal = '';
if (isset($_POST['due_amt_cal'])) {
    $due_amt_cal = str_replace([',', ' '], '', $_POST['due_amt_cal']);
}
if (isset($_POST['doc_charge_cal'])) {
    $doc_charge_cal = str_replace([',', ' '], '', $_POST['doc_charge_cal']);
}
if (isset($_POST['proc_fee_cal'])) {
    $proc_fee_cal = str_replace([',', ' '], '', $_POST['proc_fee_cal']);
}
if (isset($_POST['net_cash_cal'])) {
    $net_cash_cal = str_replace([',', ' '], '', $_POST['net_cash_cal']);
}
if (isset($_POST['due_start_from'])) {
    $due_start_from = $_POST['due_start_from'];
}
if (isset($_POST['maturity_month'])) {
    $maturity_month = $_POST['maturity_month'];
}
if (isset($_POST['collection_method'])) {
    $collection_method = $_POST['collection_method'];
}
if (isset($_POST['loan_cal_id'])) { //To check Whether it is for update 
    $loan_cal_id = $_POST['loan_cal_id'];
}

if (isset($_POST['Communitcation_to_cus_ack'])) {
    $Communitcation_to_cus = $_POST['Communitcation_to_cus_ack'];
}

$verify_audio ='';
if (!empty($_FILES['verification_audio'])) {
    
    $filesArr3 = $_FILES['verification_audio'];
    $uploadDir = "uploads/verification/verifyInfo_audio/";

    foreach ($filesArr3['name'] as $key => $val) {

        $fileName = basename($filesArr3['name'][$key]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); //get the file extention

        $uniqueFileName = uniqid() . '.' . $fileType;

        //this loop will continue until it generates a unique file name
        while (file_exists($uploadDir . $uniqueFileName)) {
            $uniqueFileName = uniqid() . '.' . $fileType;
        }

        if (
            move_uploaded_file(
                $filesArr3['tmp_name'][$key],
                $uploadDir . $uniqueFileName
            )
        ) {
            $verify_audio .= $uniqueFileName . ',';
        }
    }
}

$verify_audio = implode(',', array_filter([
    rtrim($verify_audio, ','),
    trim($_POST['verification_audio_upd'] ?? '', ',')
]));

if (isset($_POST['verifyPerson'])) {
    $verifyPerson = $_POST['verifyPerson'];
}
if (isset($_POST['verification_location_ack'])) {
    $verification_location = $_POST['verification_location_ack'];
}
if (isset($_POST['verify_remark_ack'])) {
    $verify_remark = $_POST['verify_remark_ack'];
}

if (isset($_POST['cus_profile_id'])) {
    $cus_profile_id = $_POST['cus_profile_id'];
}

try {

    if ($loan_cal_id > 0 and $loan_cal_id != '') {
        $insresult = $connect->query("UPDATE acknowlegement_loan_calculation SET cus_id_loan = '$cus_id_loan', cus_name_loan = '$cus_name_loan', cus_data_loan = '$cus_data_loan', mobile_loan = '$mobile_loan', pic_loan = '$pic_loan', loan_category = '$loan_category', sub_category = '$sub_category', tot_value = '$tot_value', ad_amt = '$ad_amt', loan_amt = '$loan_amt', profit_type = '$profit_type', due_method_calc = '$due_method_calc', due_type = '$due_type', profit_method = '$profit_method', calc_method = '$calc_method', due_method_scheme = '$due_method_scheme', profit_method_scheme = '$scheme_profit_method', day_scheme = '$day_scheme', scheme_name = '$scheme_name', int_rate = '$int_rate', due_period = '$due_period', doc_charge = '$doc_charge', proc_fee = '$proc_fee', loan_amt_cal = '$loan_amt_cal', principal_amt_cal = '$principal_amt_cal', int_amt_cal = '$int_amt_cal', tot_amt_cal = '$tot_amt_cal', due_amt_cal = '$due_amt_cal', doc_charge_cal = '$doc_charge_cal', proc_fee_cal = '$proc_fee_cal', net_cash_cal = '$net_cash_cal', due_start_from = '$due_start_from', maturity_month = '$maturity_month', collection_method = '$collection_method', communication = '$Communitcation_to_cus', com_audio = '$verify_audio', verification_person = '$verifyPerson', verification_location = '$verification_location', verify_remark = '$verify_remark', cus_status = 12, update_login_id = $userid, update_date = CURRENT_TIMESTAMP() WHERE req_id = $req_id ");

        $connect->query("DELETE FROM acknowledgement_loan_cal_category WHERE req_id = '$req_id'");

        for ($i = 0; $i < sizeof($category_info); $i++) {
            $insertCategory = $connect->query("INSERT INTO acknowledgement_loan_cal_category(req_id, loan_cal_id, category) VALUES ('$req_id', '$loan_cal_id', '$category_info[$i]' )");
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($insresult) {
    $response['info'] = 'Success';
} else {
    $response['info'] = 'Error';
}

echo json_encode($response);

// Close the database connection
$connect = null;

?>