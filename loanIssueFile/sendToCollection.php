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

        //if from account loan issue to loan issue moved then no need to change the updated date in in_issue becuz it is the loan date.
        $in_issue_column = ", updated_date = NOW()";
    } elseif ($issueresult && $issueresult->rowCount() > 0) {
        $loan_row = $issueresult->fetch();
        $loan_id = $loan_row['loan_id'];
        $in_issue_column = "";
    }

    //////////////////////////////////////////////////////////////////////////

    //Issue  Completed And Move to Collection = 14.

    // Update request_creation
    $stmt = $connect->prepare(
        "UPDATE request_creation 
         SET cus_status = 14, updated_date = NOW(), update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update customer_register
    $stmt = $connect->prepare(
        "UPDATE customer_register 
         SET cus_status = 14
         WHERE req_ref_id = ?"
    );
    $stmt->execute([$req_id]);

    // Update in_verification
    $stmt = $connect->prepare(
        "UPDATE in_verification 
         SET cus_status = 14, update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_approval
    $stmt = $connect->prepare(
        "UPDATE in_approval 
         SET cus_status = 14, update_login_id = ?
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_acknowledgement
    $stmt = $connect->prepare(
        "UPDATE in_acknowledgement 
         SET cus_status = 14, update_login_id = ?, updated_date = NOW()
         WHERE req_id = ?"
    );
    $stmt->execute([$userid, $req_id]);

    // Update in_issue
    $stmt = $connect->prepare(
        "UPDATE in_issue 
         SET loan_id = ?, cus_status = 14 $in_issue_column, update_login_id = ? 
         WHERE req_id = ?"
    );
    $stmt->execute([$loan_id, $userid, $req_id]);

    //Doc id will generate while Loan id generate because both id have to same for a customer.
    if($loan_id){
        $doc_id = "DOC-" . "$loan_id";
        // Update acknowlegement_documentation
        $stmt = $connect->prepare(
            "UPDATE acknowlegement_documentation SET doc_id = ?, update_login_id = ?, updated_date = NOW()
            WHERE req_id = ?"
        );
        $stmt->execute([$doc_id, $userid, $req_id]);
    }
    
    $qry = $connect->query("SELECT agent_id FROM in_verification WHERE req_id = $req_id ");
    $ag_id = $qry->fetch()['agent_id'];

    $qry = $connect->query("SELECT cus_id_loan, loan_amt_cal, net_cash_cal, tot_amt_cal, due_amt_cal, due_start_from from acknowlegement_loan_calculation WHERE req_id = $req_id ");
    $row = $qry->fetch();
    $tot_amt_cal = $row['tot_amt_cal'];
    $due_amt_cal = $row['due_amt_cal'];
    $cus_id = $row['cus_id_loan'];
    $dueStartDate = $row['due_start_from'];

    if ($ag_id > 0 and $ag_id != '' and $ag_id != null) { //if agent id is mentioned for this request, then this request is directly moving to collection without issuing cash
        $loan_amt = $row['loan_amt_cal'];
        $net_cash = $row['net_cash_cal'];

        //insert query need to be places here and in cash tally issued should be edited as per this agent id. if agent id mentioned then no need to take that issued debit

        // Insert into loan_issue
        $stmt = $connect->prepare(
            "INSERT INTO loan_issue (req_id, cus_id, issued_to, agent_id, cash, balance_amount, loan_amt, net_cash, insert_login_id, created_date) VALUES (?, ?, 'Agent', ?, ?, '0', ?, ?, ?, NOW())"
        );
        $stmt->execute([$req_id, $cus_id, $ag_id, $net_cash,  $loan_amt, $net_cash, $userid]);
    }
    
    if((strtotime($dueStartDate) > strtotime($current_date))){
        $cus_payable = '0';
    
    } else{
        $cus_payable = $due_amt_cal;
        
    }

    // Insert into customer_status
    $stmt = $connect->prepare(
        "INSERT INTO customer_status(req_id, cus_id, sub_status, payable_amnt, bal_amnt, insert_login_id, created_date) VALUES (?, ?, 'Current', ?, ?, ?, ?)"
    );
    $stmt->execute([$req_id, $cus_id, $cus_payable, $tot_amt_cal, $userid, $current_date]);

    // Insert into document_track
    $stmt = $connect->prepare(
        "INSERT INTO document_track(req_id, cus_id, track_status, insert_login_id, created_date) VALUES(?, ?, '1', ?, NOW())"
    );
    $stmt->execute([$req_id, $cus_id, $userid]);

    // Commit transaction
    $connect->commit();
    $response = 'Loan Issue Completed';
    
} catch (Exception $e) {
    // Rollback the transaction on error
    $connect->rollBack();
    $loan_id = "";
    $doc_id = "";
    $response = "Error: " . $e->getMessage();
}

// $qry = $connect->query("SELECT customer_name, mobile1 from customer_register WHERE req_ref_id = '$req_id' ");
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

echo json_encode(["response" => $response, "loanid" => $loan_id, "docid" => $doc_id]);

// Close the database connection
$connect = null;
