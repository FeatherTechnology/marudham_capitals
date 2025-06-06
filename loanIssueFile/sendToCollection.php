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
try {
    // Begin transaction
    $connect->beginTransaction();

    $issueresult = $connect->query("SELECT loan_id FROM in_issue WHERE req_id = '$req_id' AND loan_id != '' ");

    if ($issueresult && $issueresult->rowCount() == 0) {

        // Get the latest loan ID
        $selectIC = $connect->query("SELECT MAX(CAST(loan_id AS UNSIGNED)) AS loan_id FROM in_issue WHERE loan_id IS NOT NULL AND loan_id != '' FOR UPDATE");
        $row = $selectIC->fetch();
        $loan_id = $row["loan_id"] ? $row["loan_id"] + 1 : 101;

    } elseif ($issueresult && $issueresult->rowCount() > 0) {
        $loan_row = $issueresult->fetch();
        $loan_id = $loan_row['loan_id'];
    }

    //////////////////////////////////////////////////////////////////////////

    //Issue  Completed And Move to Collection = 14.

    $connect->query("UPDATE request_creation set cus_status = 14, updated_date = now(),update_login_id = $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on Request Table');
    $connect->query("UPDATE customer_register set cus_status = 14 WHERE req_ref_id = '" . $req_id . "' ") or die('Error on Customer Table');
    $connect->query("UPDATE in_verification set cus_status = 14, update_login_id = $userid WHERE req_id = '" . $req_id . "' ") or die('Error on inVerification Table');
    $connect->query("UPDATE `in_approval` SET `cus_status`= 14,`update_login_id`= $userid WHERE  req_id = '" . $req_id . "' ") or die('Error on in_approval Table');
    $connect->query("UPDATE `in_acknowledgement` SET `cus_status`= 14,`update_login_id`= $userid and `updated_date`= current_date WHERE req_id = '" . $req_id . "' ") or die('Error on in_acknowledgement Table');
    $insertIssue = $connect->query("UPDATE `in_issue` SET `loan_id` = '$loan_id',`cus_status`= 14,`updated_date`=now(),`update_login_id` = $userid where req_id = '" . $req_id . "' ") or die('Error on in_issue Table');

    $qry = $connect->query("SELECT agent_id FROM in_verification where req_id = $req_id ");
    $ag_id = $qry->fetch()['agent_id'];

    $qry = $connect->query("SELECT cus_id_loan, loan_amt_cal, net_cash_cal, tot_amt_cal, due_amt_cal, due_start_from from acknowlegement_loan_calculation where req_id = $req_id ");
    $row = $qry->fetch();
    $tot_amt_cal = $row['tot_amt_cal'];
    $due_amt_cal = $row['due_amt_cal'];
    $cus_id = $row['cus_id_loan'];
    $dueStartDate = $row['due_start_from'];

    if ($ag_id > 0 and $ag_id != '' and $ag_id != null) { //if agent id is mentioned for this request, then this request is directly moving to collection without issuing cash
        $loan_amt = $row['loan_amt_cal'];
        $net_cash = $row['net_cash_cal'];

        //insert query need to be places here and in cash tally issued should be edited as per this agent id. if agent id mentioned then no need to take that issued debit
        $qry = $connect->query("INSERT INTO `loan_issue` (`req_id`, `cus_id`, `issued_to`, `agent_id`, `cash`, `balance_amount`, `loan_amt`, `net_cash`, `insert_login_id`,`created_date`) 
        VALUES ('$req_id', '$cus_id', 'Agent', '$ag_id', '$net_cash', '0', '$loan_amt', '$net_cash', '$userid', now()) ");
    }
    
    if((strtotime($dueStartDate) > strtotime($current_date))){
        $cus_payable = '0';
    
    } else{
        $cus_payable = $due_amt_cal;
        
    }

    $query = $connect->query(" INSERT INTO `customer_status`( `req_id`, `cus_id`, `sub_status`, `payable_amnt`, `bal_amnt`, `insert_login_id`, `created_date`) VALUES ('$req_id','$cus_id','Current','$cus_payable','$tot_amt_cal','$userid', '$current_date' ) ");
    
    // Commit transaction
    $connect->commit();
    $response = 'Loan Issue Completed';
    
} catch (Exception $e) {
    // Rollback the transaction on error
    $connect->rollBack();
    $loan_id = "";
    $response = "Error: " . $e->getMessage();
    exit;
}

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

echo json_encode(["response" => $response, "loanid" => $loan_id]);

// Close the database connection
$connect = null;
