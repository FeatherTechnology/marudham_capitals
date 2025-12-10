<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];

if (!is_array($user_id)) {
    $user_id = explode(',', $user_id);
}
$user_id = array_map('intval', $user_id);
$user_id_str = implode(',', $user_id);

/* USER NAME */
$userNameQry = $connect->query("
    SELECT fullname 
    FROM user 
    WHERE user_id IN ($user_id_str) AND status = 0 
    LIMIT 1
");
$user_fullname = $userNameQry->fetchColumn();

/* LOAN CATEGORIES */
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

/* LOOP CATEGORYWISE */
foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    /* ================================
       CATEGORY + AGENT WISE REPORT
    ================================== */
    $qry = "
        SELECT 
            ac.ag_name AS agent_name,
            COUNT(li.req_id) AS issued_count,
            SUM(
                COALESCE(li.cash, 0) + 
                COALESCE(li.cheque_value, 0) + 
                COALESCE(li.transaction_value, 0)
            ) AS loan_amt
        FROM loan_issue li
        JOIN acknowlegement_loan_calculation alc 
            ON alc.req_id = li.req_id
        LEFT JOIN agent_creation ac
            ON ac.ag_id = li.agent_id
        WHERE alc.loan_category = '$cat_id'
          AND li.insert_login_id IN ($user_id_str)
          AND DATE(li.created_date) BETWEEN '$from_date' AND '$to_date'
        GROUP BY li.agent_id
        ORDER BY ac.ag_name
    ";

    $result = $connect->query($qry)->fetchAll(PDO::FETCH_ASSOC);

    if (empty($result)) {
        continue; // no loan in this category
    }

    foreach ($result as $row) {

        $data[] = [
            "sno"           => $sno++,
            "fullname"      => $user_fullname,
            "loan_category" => $cat_name,
            "agent_name"    => $row['agent_name'] ?: "",
            "total_count"   => $row['issued_count'],
            "issued_amount" => $row['loan_amt']
        ];
    }
}

/* TOTAL ROW */
$data[] = [
    "sno"           => "",
    "fullname"      => "Total",
    "loan_category" => "",
    "agent_name"    => "",
    "total_count"   => array_sum(array_column($data, "total_count")),
    "issued_amount" => array_sum(array_column($data, "issued_amount"))
];

echo json_encode(["data" => $data]);
?>
