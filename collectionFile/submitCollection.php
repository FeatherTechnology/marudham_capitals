<?php
session_start();
include_once '../ajaxconfig.php';
$cur_date = date('Y-m-d');

if (isset($_SESSION['userid'])) {
    $userid  = $_SESSION['userid'];
}
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}
if (isset($_POST['cus_name'])) {
    $cus_name = $_POST['cus_name'];
}
if (isset($_POST['area_id'])) {
    $area_id =  $_POST['area_id'];
}
if (isset($_POST['sub_area_id'])) {
    $sub_area_id = $_POST['sub_area_id'];
}
if (isset($_POST['branch_id'])) {
    $branch_id = $_POST['branch_id'];
}
if (isset($_POST['line_id'])) {
    $line_id = $_POST['line_id'];
}
if (isset($_POST['mobile1'])) {
    $mobile1 = $_POST['mobile1'];
}
if (isset($_POST['cus_image'])) {
    $cus_image = $_POST['cus_image'];
}
if (isset($_POST['loan_category_id'])) {
    $loan_category_id = $_POST['loan_category_id'];
}
if (isset($_POST['sub_category_id'])) {
    $sub_category_id = $_POST['sub_category_id'];
}
if (isset($_POST['status'])) {
    $status = $_POST['status'];
}
if (isset($_POST['sub_status'])) {
    $sub_status = $_POST['sub_status'];
}
if (isset($_POST['tot_amt'])) {
    $tot_amt = $_POST['tot_amt'];
}
if (isset($_POST['paid_amt'])) {
    $paid_amt = $_POST['paid_amt'];
}
if (isset($_POST['bal_amt'])) {
    $bal_amt = $_POST['bal_amt'];
}
if (isset($_POST['due_amt'])) {
    $due_amt = $_POST['due_amt'];
}
if (isset($_POST['pending_amt'])) {
    $pending_amt = $_POST['pending_amt'];
}
if (isset($_POST['payable_amt'])) {
    $payable_amt = $_POST['payable_amt'];
}
if (isset($_POST['penalty'])) {
    $penalty = $_POST['penalty'];
}
if (isset($_POST['coll_charge'])) {
    $coll_charge = $_POST['coll_charge'];
}
if (isset($_POST['collection_mode'])) {
    $collection_mode = $_POST['collection_mode'];
}
if (isset($_POST['bank_id'])) {
    $bank_id = $_POST['bank_id'];
}
if (isset($_POST['cheque_no'])) {
    $cheque_no = $_POST['cheque_no'];
}
if (isset($_POST['trans_id'])) {
    $trans_id = $_POST['trans_id'];
}
if (isset($_POST['trans_date'])) {
    $trans_date = ($_POST['trans_date'] !='') ? $_POST['trans_date'] : '0000-00-00';
}
if (isset($_POST['collection_loc'])) {
    $collection_loc = $_POST['collection_loc'];
}
if (isset($_POST['collection_date'])) {
    $current_time = date('H:i:s');
    $collctn_date = date('Y-m-d', strtotime($_POST['collection_date'])); 
    $collection_date = $collctn_date.' '.$current_time; 
}
// if (isset($_POST['collection_id'])) {
//     $collection_id = $_POST['collection_id'];
// }
if (isset($_POST['due_amt_track'])) {
    $due_amt_track = $_POST['due_amt_track'];
}
if (isset($_POST['princ_amt_track'])) {
    $princ_amt_track = $_POST['princ_amt_track'];
}
if (isset($_POST['int_amt_track'])) {
    $int_amt_track = $_POST['int_amt_track'];
}
$penalty_track = '';
if (isset($_POST['penalty_track'])) {
    $penalty_track = $_POST['penalty_track'];
}
$coll_charge_track = '';
if (isset($_POST['coll_charge_track'])) {
    $coll_charge_track = $_POST['coll_charge_track'];
}
if (isset($_POST['total_paid_track'])) {
    $total_paid_track = $_POST['total_paid_track'];
}
$pre_close_waiver = '';
if (isset($_POST['pre_close_waiver'])) {
    $pre_close_waiver = $_POST['pre_close_waiver'];
}
$penalty_waiver = '';
if (isset($_POST['penalty_waiver'])) {
    $penalty_waiver = $_POST['penalty_waiver'];
}
$coll_charge_waiver = '';
if (isset($_POST['coll_charge_waiver'])) {
    $coll_charge_waiver = $_POST['coll_charge_waiver'];
}
$total_waiver = '';
if (isset($_POST['total_waiver'])) {
    $total_waiver = $_POST['total_waiver'];
}

try{
    // Begin transaction
    $connect->beginTransaction();

    $myStr = 'COL';
    $selectIC = $connect->query("SELECT coll_code FROM collection WHERE coll_code != '' ORDER BY coll_id DESC LIMIT 1 FOR UPDATE");
    if($selectIC -> rowCount() > 0) {
        $row = $selectIC->fetch();
        $ac2 = $row["coll_code"];
        
        $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
        $coll_code = $myStr."-". "$appno2";
    } else {
        $coll_code = $myStr."-101";
    }

    $insertQry = "INSERT INTO `collection`(  `coll_code`, `req_id`, `cus_id`, `cus_name`, `branch`, `area`, `sub_area`, `line`, `loan_category`, `sub_category`, `coll_status`, 
        `coll_sub_status`, `tot_amt`, `paid_amt`, `bal_amt`, `due_amt`, `pending_amt`, `payable_amt`, `penalty`, `coll_charge`, `coll_mode`, `bank_id`, `cheque_no`, `trans_id`, `trans_date`, 
        `coll_location`, `coll_date`, `due_amt_track`,`princ_amt_track`,`int_amt_track`, `penalty_track`, `coll_charge_track`, `total_paid_track`, `pre_close_waiver`, `penalty_waiver`, `coll_charge_waiver`, 
        `total_waiver`, `insert_login_id`,`created_date`)  VALUES('" . strip_tags($coll_code) . "','" . strip_tags($req_id) . "','" . strip_tags($cus_id) . "','" . strip_tags($cus_name) . "',
        '" . strip_tags($branch_id) . "', '" . strip_tags($area_id) . "', '" . strip_tags($sub_area_id) . "', '" . strip_tags($line_id) . "','" . strip_tags($loan_category_id) . "',
        '" . strip_tags($sub_category_id) . "','" . strip_tags($status) . "','" . strip_tags($sub_status) . "', '" . strip_tags($tot_amt) . "', '" . strip_tags($paid_amt) . "', 
        '" . strip_tags($bal_amt) . "','" . strip_tags($due_amt) . "','" . strip_tags($pending_amt) . "','" . strip_tags($payable_amt) . "','" . strip_tags($penalty) . "','" . strip_tags($coll_charge) . "',
        '" . strip_tags($collection_mode) . "','" . strip_tags($bank_id) . "','" . strip_tags($cheque_no) . "','" . strip_tags($trans_id) . "','" . strip_tags($trans_date) . "','" . strip_tags($collection_loc) . "',
        '" . strip_tags($collection_date) . "','" . strip_tags($due_amt_track) . "','" . strip_tags($princ_amt_track) . "','" . strip_tags($int_amt_track) . "','" . strip_tags($penalty_track) . "','" . strip_tags($coll_charge_track) . "','" . strip_tags($total_paid_track) . "',
        '" . strip_tags($pre_close_waiver) . "','" . strip_tags($penalty_waiver) . "','" . strip_tags($coll_charge_waiver) . "','" . strip_tags($total_waiver) . "',$userid,current_timestamp )";

    $insresult = $connect->query($insertQry);

    if ($penalty_track != '' or $penalty_waiver != '') {
        $qry = $connect->query("INSERT INTO penalty_charges (`req_id`,`paid_date`,`paid_amnt`,`waiver_amnt`)VALUES('" . strip_tags($req_id) . "','" . strip_tags($collctn_date) . "',
            '" . strip_tags($penalty_track) . "','" . strip_tags($penalty_waiver) . "')");
    }
    if ($coll_charge_track != '' or $coll_charge_waiver != '') {
        $qry = $connect->query("INSERT INTO collection_charges (`req_id`,`paid_date`,`paid_amnt`,`waiver_amnt`)VALUES('" . strip_tags($req_id) . "','" . strip_tags($collctn_date) . "',
            '" . strip_tags($coll_charge_track) . "','" . strip_tags($coll_charge_waiver) . "')");
    }

    if ($cheque_no != '') {
        $qry = $connect->query("UPDATE `cheque_no_list` SET `used_status`='1' WHERE `id`=$cheque_no "); //If cheque has been used change status to 1
    }

    $check = intval($due_amt_track) + intval($pre_close_waiver) - intval($bal_amt);
    $collected_amnt = intval($due_amt_track) + intval($pre_close_waiver);

    if (($princ_amt_track != '' or $int_amt_track != '') and ($due_amt_track == '' or $due_amt_track == 0 or $due_amt_track == null)) {
        // if this condition is true then it will be the interest based loan. coz thats where we able to give princ/int amt track and not able to give due amt track
        //if yes then $check variable should check with principal amt
        $check = intVal($princ_amt_track) + intVal($pre_close_waiver) - intval($bal_amt);
        $collected_amnt = intval($princ_amt_track) + intval($pre_close_waiver);
    }

    $penalty_check = intval($penalty_track) + intval($penalty_waiver) - intval($penalty);
    $coll_charge_check = intval($coll_charge_track) + intval($coll_charge_waiver) - intval($coll_charge);

    if ($check == 0 && $penalty_check == 0 && $coll_charge_check == 0) {
        $cus_status = 20;
        $connect->query("UPDATE request_creation set cus_status = $cus_status,updated_date=now(), update_login_id = $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on Request Table');
        // $connect->query("UPDATE customer_register set cus_status = 14 WHERE req_ref_id = '".$req_id."' ")or die('Error on Customer Table');
        $connect->query("UPDATE in_verification set cus_status = $cus_status,updated_date=now(), update_login_id = $userid WHERE req_id = '" . $req_id . "' ") or die('Error on inVerification Table');
        $connect->query("UPDATE `in_approval` SET `cus_status`= $cus_status,updated_date=now(),`update_login_id`= $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on in_approval Table');
        $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= $cus_status,updated_date=now(),`update_login_id`= $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on in_acknowledgement Table');
        $connect->query("UPDATE `in_issue` SET `cus_status`= $cus_status, `update_login_id` = $userid where req_id = '" . $req_id . "' ") or die('Error on in_issue Table');
        $connect->query("INSERT INTO `closing_customer` (req_id,cus_id,closing_date) VALUES ($req_id, $cus_id, DATE(now()) ) ") or die('Error on closing_customer Table');
    }

    $pending_amount = intval($pending_amt) - $collected_amnt;
    $pending_amnts = ($pending_amount > 0) ? $pending_amount : 0;

    $payable_amount = intval($payable_amt) - $collected_amnt;
    $payable_amnts = ($payable_amount > 0) ? $payable_amount : 0;

    $bal_amount = intval($bal_amt) - $collected_amnt;
    $bal_amnts = ($bal_amount > 0) ? $bal_amount : 0;

    if ($check == 0 && $penalty_check == 0 && $coll_charge_check == 0) {
        $substs = 'Closed';

    } else {

        if ($sub_status == 'OD') {
            if ($check == 0 && $penalty_check == 0 && $coll_charge_check == 0) {
                $substs = 'Closed';
            } else {
                $substs = ($check == 0 && ($penalty_check > 0 || $coll_charge_check > 0)) ? 'Due Nil' : 'OD';
            }
            
        } elseif ($sub_status == 'Pending') {
            $substs = ($pending_amnts == 0) ? 'Current' : 'Pending';

            $result = $connect->query("SELECT maturity_month, due_method_calc, due_method_scheme FROM `acknowlegement_loan_calculation` WHERE req_id = $req_id ");
            $row = $result->fetch();

            if($row['due_method_calc'] == 'Monthly' || $row['due_method_scheme'] == '1'){
                $maturity_month = date('Y-m', strtotime($row['maturity_month']));
                $date_format = 'Y-m';
                $current_date = date('Y-m');
            }else{
                $maturity_month = $row['maturity_month'];
                $date_format = 'Y-m-d';
                $current_date = date('Y-m-d');

            }

            $end_date_obj = DateTime::createFromFormat($date_format, $maturity_month);
            $current_date_obj = DateTime::createFromFormat($date_format, $current_date);

            if ($current_date_obj > $end_date_obj) {
                $substs = 'OD';
            }

        } elseif($sub_status == 'Error' || $sub_status == 'Legal'){
            $substs = $sub_status;

        }else {    
            $substs = 'Current';
        }

    }

    $cur_day = date('j'); // Day of the month without leading zeros

    if ($cur_day >= 1 && $cur_day <= 10) {
        $lpd = '1';

    } elseif ($cur_day >= 11 && $cur_day <= 15) {
        $lpd = '2';

    } elseif ($cur_day >= 16 && $cur_day <= 20) {
        $lpd = '3';

    } elseif ($cur_day >= 21 && $cur_day <= 25) {
        $lpd = '4';

    } elseif ($cur_day >= 26 && $cur_day <= 31) {
        $lpd = '5';

    } else {
        $lpd = '0';

    }
    //Current_month_paid = YES/1, if either partial amount or full amount of payable paid in collection. 
    
    $query = $connect->query("UPDATE `customer_status` SET `cus_id`='$cus_id',`sub_status`='$substs',`payable_amnt` = '$payable_amnts', `bal_amnt`='$bal_amnts', `last_paid_date`= '$lpd', `current_month_paid`='1', `insert_login_id`='$userid',`created_date`='$cur_date' WHERE `req_id`='$req_id' ");
    

    // $qry = $connect->query("SELECT customer_name, mobile1 from customer_register where req_ref_id = '$req_id' ");
    // $row = $qry->fetch_assoc();
    // $customer_name = $row['customer_name'];
    // $cus_mobile1 = $row['mobile1'];

    // $message = "";
    // $templateid	= ''; //FROM DLT PORTAL.
    // // Account details
    // $apiKey = '';
    // // Message details
    // $sender = '';
    // // Prepare data for POST request
    // $data = 'access_token='.$apiKey.'&to='.$cus_mobile1.'&message='.$message.'&service=T&sender='.$sender.'&template_id='.$templateid;
    // // Send the GET request with cURL
    // $url = 'https://sms.messagewall.in/api/v2/sms/send?'.$data; 
    // $response = file_get_contents($url);  
    // // Process your response here
    // return $response; 

    // Commit the transaction
    $connect->commit();

    if ($insresult) {
        $response['info'] = 'Success';
        $response['coll_id'] = $coll_code;
    } else {
        $response['info'] = 'Error';
    }

} catch(Exception $e){
    $connect -> rollBack();
    echo "Error while insert: ".$e->getMessage(); 
}

echo json_encode($response);

// Close the database connection
$connect = null;