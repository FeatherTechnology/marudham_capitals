<?php
session_start();
$user_id = $_SESSION['userid'];

include('../../ajaxconfig.php');

$op_date = date('Y-m-d', strtotime($_POST['op_date']));

$li_count = 0;
$submitted_count = 0;

$qry = $connect->query("
    SELECT
        li.insert_login_id,
        COUNT(*) AS total_loan_count,
        SUM(CASE WHEN li.payment_type IN ('1', '2') THEN 1 ELSE 0 END) AS bank_loan_count,
        MAX(CASE WHEN li.payment_type = '0' THEN 1 ELSE 0 END) AS hand_cash_count
    FROM
        loan_issue li
    WHERE
        (li.agent_id = '' OR li.agent_id IS NULL)
        AND DATE(li.created_date) = '$op_date'
    GROUP BY
        li.insert_login_id
");

$total_bank_loan_count = 0;
$total_hand_cash_count = 0;

if ($qry->rowCount() > 0) {
    while ($row = $qry->fetch()) {
        $total_bank_loan_count += $row['bank_loan_count'];
        $total_hand_cash_count += $row['hand_cash_count'];
    }
}

$li_count = $total_bank_loan_count + $total_hand_cash_count;

// Now check submitted count
$hissueQry = $connect->query("SELECT COUNT(*) as hissue_count FROM ct_db_hissued WHERE DATE(created_date) = '$op_date'");
$bissueQry = $connect->query("SELECT COUNT(*) as bissue_count FROM ct_db_bissued WHERE DATE(created_date) = '$op_date'");

$hissue_count = $hissueQry->fetch()['hissue_count'];
$bissue_count = $bissueQry->fetch()['bissue_count'];

$submitted_count = $hissue_count + $bissue_count;

if ($li_count == $submitted_count) {
    $response = "Today's Loan Issue Already Submitted";
} else {
    $response = "Today's Loan Issue Not Submitted";
}

echo $response;

$connect = null;
?>
