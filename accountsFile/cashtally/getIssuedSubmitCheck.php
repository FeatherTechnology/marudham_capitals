<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$op_date = date('Y-m-d', strtotime($_POST['op_date']));

$li_count = 0;
$submitted_count = 0;

// $qry = $connect->query("SELECT COUNT(*) as li_count,created_date,insert_login_id FROM `loan_issue` where (agent_id = '' or agent_id = null) and date(created_date) = '$op_date' ");
$qry = $connect->query("SELECT
    COUNT(*) AS total_loan_count,

    -- Count of bank loans (payment_type 1 or 2)
    SUM(CASE WHEN li.payment_type IN ('1', '2') THEN 1 ELSE 0 END) AS bank_loan_count,

    -- Hand cash count: 1 if user has at least one hand cash loan (payment_type = 0)
    MAX(CASE WHEN li.payment_type = '0' THEN 1 ELSE 0 END) AS hand_cash_count

FROM
    loan_issue li

WHERE
    (li.agent_id = '' OR li.agent_id IS NULL)
    AND DATE(li.created_date) = '$op_date'

GROUP BY
    li.insert_login_id;
 ");
if ($qry->rowCount() > 0) {

    $row = $qry->fetch();
    $bank_loan_count = $row['bank_loan_count'];
    $hand_cash_count = $row['hand_cash_count'];
    $li_count = $bank_loan_count + $hand_cash_count;
    // $insert_login_id = $row['insert_login_id'];
    // $created_date = date('Y-m-d', strtotime($row['created_date']));

    $hissueQry = $connect->query("SELECT COUNT(*) as hissue_count from ct_db_hissued where date(created_date) = '$op_date' ");
    $bissueQry = $connect->query("SELECT COUNT(*) as bissue_count from ct_db_bissued where date(created_date) = '$op_date' ");

    $hissue_count = $hissueQry->fetch()['hissue_count'];
    $bissue_count = $bissueQry->fetch()['bissue_count'];

    $submitted_count = $hissue_count + $bissue_count;
}

if ($li_count == $submitted_count) {
    $response = "Today's Loan Issue Already Submitted";
} else {
    $response = "Today's Loan Issue Not Submitted";
}


echo $response;

// Close the database connection
$connect = null;