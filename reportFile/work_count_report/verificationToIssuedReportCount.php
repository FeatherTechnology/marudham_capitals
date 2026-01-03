<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];
$screen    = $_POST['screen']; // 1=Request, 2=Verification, 3=Approval

/* -----------------------------
   Prepare User IDs
--------------------------------*/
if (!is_array($user_id)) {
    $user_id = explode(',', $user_id);
}
$user_id = array_map('intval', $user_id);
$user_id_str = implode(',', $user_id);

if (empty($user_id)) {
    echo json_encode(["data" => []]);
    exit;
}

/* -----------------------------
   Fetch USER NAME (single name)
--------------------------------*/
$userNameQry = $connect->query("
    SELECT fullname 
    FROM user 
    WHERE user_id IN ($user_id_str) AND status = 0 
    LIMIT 1
");
$user_fullname = $userNameQry->fetchColumn();

/* -----------------------------
   Fetch LOAN CATEGORIES
--------------------------------*/
$loanCats = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
$sno = 1;

/* -----------------------------
   LOOP CATEGORIES ONLY
--------------------------------*/
foreach ($loanCats as $cat) {

    $cat_id   = $cat['loan_category_creation_id'];
    $cat_name = $cat['loan_category_creation_name'];

    /* =====================================
       1️⃣ FETCH REQUEST / VERIFICATION / APPROVAL IDS
    ===================================== */
//     $base_req_qry = "
//     SELECT req_id 
//     FROM request_creation
//     WHERE loan_category = '$cat_id'
//       AND insert_login_id IN ($user_id_str)
//       AND DATE(created_date) BETWEEN '$from_date' AND '$to_date'
// ";

// $base_req_ids = array_column(
//     $connect->query($base_req_qry)->fetchAll(PDO::FETCH_ASSOC),
//     'req_id'
// );

// if (empty($base_req_ids)) continue;

// $base_req_id_list = implode(',', $base_req_ids);

    if ($screen == 2) {

        $req_qry = "
            SELECT ia.req_id 
            FROM verification_loan_calculation vlc
            JOIN in_approval ia ON ia.req_id = vlc.req_id
            WHERE vlc.loan_category = '$cat_id'
              AND ia.insert_login_id IN ($user_id_str)
              AND DATE(vlc.create_date) BETWEEN '$from_date' AND '$to_date'
        ";

    } else if ($screen == 3) {

        $req_qry = "
            SELECT ia.req_id 
            FROM verification_loan_calculation vlc
            JOIN in_acknowledgement ia ON ia.req_id = vlc.req_id
            WHERE vlc.loan_category = '$cat_id'
              AND ia.inserted_user IN ($user_id_str)
              AND DATE(ia.inserted_date) BETWEEN '$from_date' AND '$to_date'
        ";
    }

    $req_ids = array_column($connect->query($req_qry)->fetchAll(PDO::FETCH_ASSOC), 'req_id');
    $req_count = count($req_ids);

    if ($req_count == 0) continue;

    $req_id_list = implode(',', $req_ids);
    if ($req_id_list == "") $req_id_list = 0;

    /* =====================================
       2️⃣ CANCEL COUNT
    ===================================== */
    if ($screen == 2) $cancel_status = "5,6,7";
    if ($screen == 3) $cancel_status = "6,7";

    $cancel_count = $connect->query("
        SELECT COUNT(*) 
        FROM request_creation
        WHERE req_id IN ($req_id_list)
          AND cus_status IN ($cancel_status)
         
    ")->fetchColumn();


    /* =====================================
       3️⃣ REVOKE COUNT
    ===================================== */
    if ($screen == 3) {
        $revoke_count = 0;
    } else {
        $revoke_status = "9";
        $revoke_count = $connect->query("
            SELECT COUNT(*) 
            FROM request_creation
            WHERE req_id IN ($req_id_list)
              AND cus_status IN ($revoke_status)
              
        ")->fetchColumn();
    }

    /* =====================================
       4️⃣ ISSUED COUNT
    ===================================== */
    $issued_count = $connect->query("
        SELECT COUNT(*) 
        FROM in_issue
        WHERE req_id IN ($req_id_list)
          AND cus_status >= 14
    ")->fetchColumn();


    /* =====================================
       5️⃣ PROCESS COUNT
    ===================================== */
    $process_count = $req_count - ($cancel_count + $revoke_count + $issued_count);
    if ($process_count < 0) $process_count = 0;

    /* =====================================
       FINAL DATA
    ===================================== */
    $data[] = [
        "sno"            => $sno++,
        "fullname"       => $user_fullname,
        "loan_category"  => $cat_name,
        "total_count"    => $req_count,
        "t_cancel_count" => $cancel_count,
        "t_revoke_count" => $revoke_count,
        "t_process"      => $process_count,
        "t_issued"       => $issued_count
    ];
}



/* -----------------------------
   TOTAL ROW
--------------------------------*/
$data[] = [
    "sno"            => "",
    "fullname"       => "Total",
    "loan_category"  => "",
    "total_count"    => array_sum(array_column($data, "total_count")),
    "t_cancel_count" => array_sum(array_column($data, "t_cancel_count")),
    "t_revoke_count" => array_sum(array_column($data, "t_revoke_count")),
    "t_process"      => array_sum(array_column($data, "t_process")),
    "t_issued"       => array_sum(array_column($data, "t_issued"))
];

echo json_encode(["data" => $data]);
