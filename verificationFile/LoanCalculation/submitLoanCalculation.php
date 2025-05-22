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
    $tot_value = $_POST['tot_value'];
}
$ad_amt = '';
if (isset($_POST['ad_amt'])) {
    $ad_amt = $_POST['ad_amt'];
}
if (isset($_POST['loan_amt'])) {
    $loan_amt = $_POST['loan_amt'];
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
    $loan_amt_cal = $_POST['loan_amt_cal'];
}
if (isset($_POST['principal_amt_cal'])) {
    $principal_amt_cal = $_POST['principal_amt_cal'];
}
if (isset($_POST['int_amt_cal'])) {
    $int_amt_cal = $_POST['int_amt_cal'];
}
$tot_amt_cal = '';
if (isset($_POST['tot_amt_cal'])) {
    $tot_amt_cal = $_POST['tot_amt_cal'];
}
$due_amt_cal = '';
if (isset($_POST['due_amt_cal'])) {
    $due_amt_cal = $_POST['due_amt_cal'];
}
if (isset($_POST['doc_charge_cal'])) {
    $doc_charge_cal = $_POST['doc_charge_cal'];
}
if (isset($_POST['proc_fee_cal'])) {
    $proc_fee_cal = $_POST['proc_fee_cal'];
}
if (isset($_POST['net_cash_cal'])) {
    $net_cash_cal = $_POST['net_cash_cal'];
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

if (!empty($_FILES['verification_audio']['name'])) {
    $verify_audio = $_FILES['verification_audio']['name'];
    $audio_temp = $_FILES['verification_audio']['tmp_name'];
    $path = "../../uploads/verification/verifyInfo_audio/";
    $audiofolder = $path . $verify_audio;

    $fileExtension = pathinfo($audiofolder, PATHINFO_EXTENSION); //get the file extention
    $verify_audio = uniqid() . '.' . $fileExtension;
    while (file_exists($path . $verify_audio)) {
        //this loop will continue until it generates a unique file name
        $verify_audio = uniqid() . '.' . $fileExtension;
    }

    move_uploaded_file($audio_temp, $path . $verify_audio);
} else {
    $verify_audio = $_POST['verification_audio_upd'];
}
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

if ($loan_cal_id > 0 and $loan_cal_id != '') {
   $insresult = $connect->query("UPDATE acknowlegement_loan_calculation SET cus_id_loan = '" . strip_tags($cus_id_loan) . "', cus_name_loan = '" . strip_tags($cus_name_loan) . "', cus_data_loan = '" . strip_tags($cus_data_loan) . "', mobile_loan = '" . strip_tags($mobile_loan) . "', pic_loan = '" . strip_tags($pic_loan) . "', loan_category = '" . strip_tags($loan_category) . "', sub_category = '" . strip_tags($sub_category) . "', tot_value = '" . strip_tags($tot_value) . "', ad_amt = '" . strip_tags($ad_amt) . "', loan_amt = '" . strip_tags($loan_amt) . "', profit_type = '" . strip_tags($profit_type) . "', due_method_calc = '" . strip_tags($due_method_calc) . "', due_type = '" . strip_tags($due_type) . "', profit_method = '" . strip_tags($profit_method) . "', calc_method = '" . strip_tags($calc_method) . "', due_method_scheme = '" . strip_tags($due_method_scheme) . "', profit_method_scheme = '" . strip_tags($scheme_profit_method) . "', day_scheme = '" . strip_tags($day_scheme) . "', scheme_name = '" . strip_tags($scheme_name) . "', int_rate = '" . strip_tags($int_rate) . "', due_period = '" . strip_tags($due_period) . "', doc_charge = '" . strip_tags($doc_charge) . "', proc_fee = '" . strip_tags($proc_fee) . "', loan_amt_cal = '" . strip_tags($loan_amt_cal) . "', principal_amt_cal = '" . strip_tags($principal_amt_cal) . "', int_amt_cal = '" . strip_tags($int_amt_cal) . "', tot_amt_cal = '" . strip_tags($tot_amt_cal) . "', due_amt_cal = '" . strip_tags($due_amt_cal) . "', doc_charge_cal = '" . strip_tags($doc_charge_cal) . "', proc_fee_cal = '" . strip_tags($proc_fee_cal) . "', net_cash_cal = '" . strip_tags($net_cash_cal) . "', due_start_from = '" . strip_tags($due_start_from) . "', maturity_month = '" . strip_tags($maturity_month) . "', collection_method = '" . strip_tags($collection_method) . "', cus_status = 12, update_login_id = $userid, update_date = current_timestamp() WHERE req_id = $req_id ");

    $connect->query("DELETE FROM acknowledgement_loan_cal_category where req_id = '" . strip_tags($req_id) . "' and loan_cal_id='" . strip_tags($loan_cal_id) . "'");

    for ($i = 0; $i < sizeof($category_info); $i++) {
        $insertCategory = $connect->query("INSERT INTO `acknowledgement_loan_cal_category`(`req_id`, `loan_cal_id`, `category`) VALUES ('" . strip_tags($req_id) . "', '" . strip_tags($loan_cal_id) . "', '" . strip_tags($category_info[$i]) . "' )");
    }

} else {
    $insresult = $connect->query("INSERT INTO acknowlegement_loan_calculation (`req_id`, `cus_id_loan`, `cus_name_loan`,`cus_data_loan`, `mobile_loan`, `pic_loan`, `loan_category`, `sub_category`, `tot_value`, `ad_amt`, `loan_amt`, `profit_type`, `due_method_calc`, `due_type`, `profit_method`, `calc_method`, `due_method_scheme`, `profit_method_scheme`,`day_scheme`, `scheme_name`,  `int_rate`, `due_period`, `doc_charge`, `proc_fee`, `loan_amt_cal`, `principal_amt_cal`, `int_amt_cal`, `tot_amt_cal`, `due_amt_cal`, `doc_charge_cal`, `proc_fee_cal`, `net_cash_cal`, `due_start_from`, `maturity_month`, `collection_method`, `cus_status`, `insert_login_id`,`create_date`) VALUES ('" . strip_tags($req_id) . "', '" . strip_tags($cus_id_loan) . "', '" . strip_tags($cus_name_loan) . "', '" . strip_tags($cus_data_loan) . "','" . strip_tags($mobile_loan) . "', '" . strip_tags($pic_loan) . "', '" . strip_tags($loan_category) . "', '" . strip_tags($sub_category) . "', '" . strip_tags($tot_value) . "', '" . strip_tags($ad_amt) . "', '" . strip_tags($loan_amt) . "', '" . strip_tags($profit_type) . "', '" . strip_tags($due_method_calc) . "', '" . strip_tags($due_type) . "', '" . strip_tags($profit_method) . "', '" . strip_tags($calc_method) . "', '" . strip_tags($due_method_scheme) . "', '" . strip_tags($scheme_profit_method) . "', '" . strip_tags($day_scheme) . "', '" . strip_tags($scheme_name) . "', '" . strip_tags($int_rate) . "', '" . strip_tags($due_period) . "', '" . strip_tags($doc_charge) . "', '" . strip_tags($proc_fee) . "', '" . strip_tags($loan_amt_cal) . "', '" . strip_tags($principal_amt_cal) . "', '" . strip_tags($int_amt_cal) . "', '" . strip_tags($tot_amt_cal) . "', '" . strip_tags($due_amt_cal) . "', '" . strip_tags($doc_charge_cal) . "', '" . strip_tags($proc_fee_cal) . "', '" . strip_tags($net_cash_cal) . "', '" . strip_tags($due_start_from) . "', '" . strip_tags($maturity_month) . "', '" . strip_tags($collection_method) . "', 12, $userid, current_timestamp()) ");
    $loan_cal_id = $connect->lastInsertId();

    for ($i = 0; $i < sizeof($category_info); $i++) {
        $insertCategory = $connect->query("INSERT INTO `acknowledgement_loan_cal_category`(`req_id`, `loan_cal_id`, `category`) VALUES ('" . strip_tags($req_id) . "','" . strip_tags($loan_cal_id) . "', '" . strip_tags($category_info[$i]) . "' )");
    }
}

$cusUpd = "UPDATE `acknowlegement_customer_profile` SET `communication`='" . strip_tags($Communitcation_to_cus) . "',`com_audio`='" . strip_tags($verify_audio) . "',`verification_person`='" . strip_tags($verifyPerson) . "',`verification_location`='" . strip_tags($verification_location) . "',`update_login_id`='" . $userid . "',`updated_date`= now() WHERE `id`='" . strip_tags($cus_profile_id) . "' ";

try {
    $updateCus = $connect->query($cusUpd);
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