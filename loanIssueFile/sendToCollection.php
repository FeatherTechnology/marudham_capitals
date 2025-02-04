<?php
session_start();
include('../ajaxconfig.php');
if (isset($_POST['req_id'])) {
    $req_id = $_POST['req_id'];
}
if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
}

$current_date = date('Y-m-d');

//////////////////////////////////////////////////////////////////////////

$selectIC = $connect->query("SELECT loan_id FROM in_issue WHERE loan_id != '' ");
if ($selectIC->rowCount() > 0) {
    $codeAvailable = $connect->query("SELECT loan_id FROM in_issue WHERE (loan_id != '' or loan_id != NULL) ORDER BY id DESC LIMIT 1");
    while ($row = $codeAvailable->fetch()) {
        $ac2 = $row["loan_id"];
    }
    $loan_id = intval($ac2) + 1;
} else {
    $initialapp = "101";
    $loan_id = $initialapp;
}
//////////////////////////////////////////////////////////////////////////

//Issue  Completed And Move to Collection = 14.

$selectIC = $connect->query("UPDATE request_creation set cus_status = 14, updated_date = now(),update_login_id = $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on Request Table');
$selectIC = $connect->query("UPDATE customer_register set cus_status = 14 WHERE req_ref_id = '" . $req_id . "' ") or die('Error on Customer Table');
$selectIC = $connect->query("UPDATE in_verification set cus_status = 14, update_login_id = $userid WHERE req_id = '" . $req_id . "' ") or die('Error on inVerification Table');
$selectIC = $connect->query("UPDATE `in_approval` SET `cus_status`= 14,`update_login_id`= $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on in_approval Table');
$selectIC = $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 14,`update_login_id`= $userid and `updated_date`= current_date WHERE req_id = '" . $req_id . "' ") or die('Error on in_acknowledgement Table');
$insertIssue = $connect->query("UPDATE `in_issue` SET `loan_id` = '$loan_id',`cus_status`= 14,`updated_date`=now(),`update_login_id` = $userid where req_id = '" . $req_id . "' ") or die('Error on in_issue Table');

$qry = $connect->query("SELECT agent_id FROM in_verification where req_id = $req_id ");
$ag_id = $qry->fetch()['agent_id'];
$qry = $connect->query("SELECT cus_id_loan,loan_amt_cal, net_cash_cal, tot_amt_cal from acknowlegement_loan_calculation where req_id = $req_id ");
$row = $qry->fetch();
$tot_amt_cal = $row['tot_amt_cal'];
$cus_id = $row['cus_id_loan'];
if ($ag_id > 0 and $ag_id != '' and $ag_id != null) { //if agent id is mentioned for this request, then this request is directly moving to collection without issuing cash
    $loan_amt = $row['loan_amt_cal'];
    $net_cash = $row['net_cash_cal'];

    //insert query need to be places here and in cash tally issued should be edited as per this agent id. if agent id mentioned then no need to take that issued debit
    $qry = $connect->query("INSERT INTO `loan_issue` (`req_id`, `cus_id`, `issued_to`, `agent_id`, `cash`, `balance_amount`, `loan_amt`, `net_cash`, `insert_login_id`,`created_date`) 
        VALUES ('$req_id', '$cus_id', 'Agent', '$ag_id', '$net_cash', '0', '$loan_amt', '$net_cash', '$userid', now()) ");
}


    $query = $connect->query(" INSERT INTO `customer_status`( `req_id`, `cus_id`, `sub_status`, `payable_amnt`, `bal_amnt`, `insert_login_id`, `created_date`) VALUES ('$req_id','$cus_id','Current','$tot_amt_cal','$tot_amt_cal','$userid', '$current_date' ) ");


// $qry = $connect->query("SELECT customer_name, mobile1 from customer_register where req_ref_id = '$req_id' ");
// $row = $qry->fetch();
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

$response = 'Loan Issue Completed';
echo json_encode($response);

// Close the database connection
$connect = null;
