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

foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    /* ================================
       CATEGORY + SUBSTATUS WISE COUNT
    ================================== */
    $qry = "
        SELECT 
            cl.coll_sub_status,
            COUNT(cl.req_id) AS total_bill,
            SUM(COALESCE(cl.total_paid_track, 0)) AS total_amount
        FROM collection cl
        JOIN acknowlegement_loan_calculation alc ON cl.req_id = alc.req_id
        WHERE alc.loan_category = '$cat_id'
          AND cl.insert_login_id IN ($user_id_str)
          AND DATE(cl.coll_date) BETWEEN '$from_date' AND '$to_date'
        GROUP BY cl.coll_sub_status
    ";

    $result = $connect->query($qry)->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {

        $data[] = [
            "sno"           => $sno++,
            "fullname"      => $user_fullname,
            "loan_category" => $cat_name,
            "status"        => $row['coll_sub_status'] ?: "N/A",
            "total_bill"    => $row['total_bill'],
            "total_amount"  => $row['total_amount']
        ];
    }
}

/* TOTAL ROW */
$data[] = [
    "sno"           => "",
    "fullname"      => "Total",
    "loan_category" => "",
    "status"        => "",
    "total_bill"    => array_sum(array_column($data, "total_bill")),
    "total_amount"  => array_sum(array_column($data, "total_amount"))
];

echo json_encode(["data" => $data]);
?>
