<?php

require '../ajaxconfig.php';
session_start();

$user_id = $_SESSION['userid'];
$module = $_POST['module'] ?? '';

$loan_category_arr = [];

    switch ($module) {
        case 'verification':
            $column = 'ver_loan_cat';
            break;
        case 'approval':
            $column = 'app_loan_cat';
            break;
        case 'acknowledgement':
            $column = 'ack_loan_cat';
            break;
        // Request, Loan Issue, Closed, NOC,NOC Handover
        default:
            $column = '';
            break;
    }

    if ($column == '') {

        $sql = "SELECT DISTINCT lcc.loan_category_creation_id,lcc.loan_category_creation_name
            FROM loan_category_creation lcc
            JOIN request_creation rc ON rc.loan_category = lcc.loan_category_creation_id
            WHERE lcc.status = 0 ORDER BY lcc.loan_category_creation_name";
        $stmt = $connect->query($sql);

    } else {

        $userStmt = $connect->prepare("SELECT $column FROM user WHERE user_id=?");
        $userStmt->execute([$user_id]);
        $loanCats = $userStmt->fetchColumn();
        if (empty($loanCats)) {
            echo json_encode([]);
            exit;
        }
        $sql = "SELECT loan_category_creation_id,loan_category_creation_name FROM loan_category_creation
            WHERE status = 0 AND FIND_IN_SET(loan_category_creation_id, ?) ORDER BY loan_category_creation_name";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$loanCats]);
    }


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $loan_category_arr[] = [
        'loan_category_creation_id' => $row['loan_category_creation_id'],
        'loan_category_creation_name' => $row['loan_category_creation_name']
    ];
}

echo json_encode($loan_category_arr);
$connect = null;