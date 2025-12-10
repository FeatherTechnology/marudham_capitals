<?php 
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];
$screen   = $_POST['screen'];

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
$total_noc = 0;
if ($screen == 7) {
    $cond = "
        AND nc.insert_login_id IN ($user_id_str)
        AND nc.cus_status >= 22
        AND DATE(nc.created_date) BETWEEN '$from_date' AND '$to_date'
    ";
} elseif ($screen == 8) {
    $cond = "
        AND nc.update_login_id IN ($user_id_str)
        AND nc.cus_status = 24
        AND DATE(nc.updated_date) BETWEEN '$from_date' AND '$to_date'
    ";
}

foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    /* === CATEGORY WISE NOC COUNT === */
    $qry = "
        SELECT COUNT(nc.req_id) AS noc_count
        FROM noc nc 
        JOIN acknowlegement_loan_calculation alc 
            ON alc.req_id = nc.req_id
        WHERE alc.loan_category = '$cat_id' $cond
          
    ";

    $result = $connect->query($qry)->fetch(PDO::FETCH_ASSOC);

    $noc_count = (int)$result['noc_count'];
     if ($noc_count == 0) {
        continue;
    }
    $total_noc += $noc_count;

    $data[] = [
        "sno"           => $sno++,
        "fullname"      => $user_fullname,
        "loan_category" => $cat_name,
        "noc_count"     => $noc_count
    ];
}


/* TOTAL ROW */
$data[] = [
    "sno"           => "",
    "fullname"      => "Total",
    "loan_category" => "",
    "noc_count"     => $total_noc
];

echo json_encode(["data" => $data]);
?>
