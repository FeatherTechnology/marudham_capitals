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

/* GET ALL LOAN CATEGORIES */
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    $qry = "
        SELECT 
            SUM(CASE WHEN closed_sts = 1 THEN 1 ELSE 0 END) AS consider_count,
            SUM(CASE WHEN closed_sts = 2 THEN 1 ELSE 0 END) AS waiting_count,
            SUM(CASE WHEN closed_sts = 3 THEN 1 ELSE 0 END) AS block_count,

            SUM(CASE WHEN closed_sts = 1 AND consider_level = 1 THEN 1 ELSE 0 END) AS bronze,
            SUM(CASE WHEN closed_sts = 1 AND consider_level = 2 THEN 1 ELSE 0 END) AS silver,
            SUM(CASE WHEN closed_sts = 1 AND consider_level = 3 THEN 1 ELSE 0 END) AS gold,
            SUM(CASE WHEN closed_sts = 1 AND consider_level = 4 THEN 1 ELSE 0 END) AS platinum,
            SUM(CASE WHEN closed_sts = 1 AND consider_level = 5 THEN 1 ELSE 0 END) AS diamond

        FROM closed_status cs
        JOIN acknowlegement_loan_calculation alc ON alc.req_id = cs.req_id
        WHERE alc.loan_category = '$cat_id'
          AND cs.insert_login_id IN ($user_id_str)
          AND DATE(cs.created_date) BETWEEN '$from_date' AND '$to_date'
    ";

    $row = $connect->query($qry)->fetch(PDO::FETCH_ASSOC);

    /* SKIP if closed = 0 */
    $closed_total = intval($row["consider_count"] + $row["waiting_count"] + $row["block_count"]);
    if ($closed_total == 0) {
        continue;
    }

    $data[] = [
        "sno"              => $sno++,
        "fullname"         => $user_fullname,
        "loan_category"    => $cat_name,
        "closed"           => $closed_total,
        "bronze"           => intval($row["bronze"]),
        "silver"           => intval($row["silver"]),
        "gold"             => intval($row["gold"]),
        "platinum"         => intval($row["platinum"]),
        "diamond"          => intval($row["diamond"]),
        "total_consider"   => intval($row["consider_count"]),
        "waiting"          => intval($row["waiting_count"]),
        "block"            => intval($row["block_count"])
    ];
}

/* TOTAL ROW */
$data[] = [
    "sno" => "",
    "fullname" => "Total",
    "loan_category" => "",
    "closed" => array_sum(array_column($data, "closed")),
    "bronze" => array_sum(array_column($data, "bronze")),
    "silver" => array_sum(array_column($data, "silver")),
    "gold" => array_sum(array_column($data, "gold")),
    "platinum" => array_sum(array_column($data, "platinum")),
    "diamond" => array_sum(array_column($data, "diamond")),
    "total_consider" => array_sum(array_column($data, "total_consider")),
    "waiting" => array_sum(array_column($data, "waiting")),
    "block" => array_sum(array_column($data, "block"))
];

echo json_encode(["data" => $data]);
