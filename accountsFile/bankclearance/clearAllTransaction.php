<?php
include("../../ajaxconfig.php");
session_start();
$user_id = $_SESSION['userid'];

$bank_id = $_POST['bank_id'];
$bank_stmt = $_POST['bank_stmt']; // Correct variable
$success = false;

// Loop through each bank statement entry
foreach ($bank_stmt as $stmt) {  // Corrected variable
    $type = $stmt['type'];  // 'cr' or 'dr'
    $trans_id = $stmt['trans_id'];
    $bank_stmt_id = $stmt['bank_stmt_id'];

    if ($type == 'cr') { // Credit Transactions
        $query = "
            SELECT SUM(total_paid_track) AS paid FROM collection WHERE trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_boti WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND to_bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_bexchange WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND to_bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_cash_deposit WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND to_bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_bag WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_binvest WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_bdeposit WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_cr_bel WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_exf WHERE insert_login_id = '$user_id' AND ucl_trans_id = '$trans_id' AND bank_id = '$bank_id'
        ";
    } else if ($type == 'dr') { // Debit Transactions
        $query = "
            SELECT SUM(amt) AS paid FROM ct_db_bexchange WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND from_acc_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_cash_withdraw WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND from_bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_bag WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_binvest WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_bdeposit WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_bel WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_exf WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(netcash) AS paid FROM ct_db_bissued WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND li_bank_id = '$bank_id'
            UNION ALL
            SELECT SUM(amt) AS paid FROM ct_db_bexpense WHERE insert_login_id = '$user_id' AND trans_id = '$trans_id' AND bank_id = '$bank_id'
        ";
    }

    // Execute the query
    $qry = $connect->query($query);

    if ($qry->rowCount() > 0) {
        if (clearTransaction($connect, $bank_stmt_id)) {
            $success = true; // At least one transaction is cleared
        }
    }
}

// Function to clear transaction
function clearTransaction($connect, $bank_stmt_id) {
    $qry = $connect->prepare("UPDATE bank_stmt SET clr_status = 1 WHERE id = ?");
    return $qry->execute([$bank_stmt_id]);
}

// Close database connection
$connect = null;

// Return JSON response
echo json_encode(["status" => $success ? "1" : "2"]);
?>
