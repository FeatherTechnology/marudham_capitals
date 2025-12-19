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

$data = [];
$sno = 1;

$qry = $connect->query("
   SELECT 
    alm.line_name,
    COUNT(DISTINCT cf.req_id) AS total_count,
    SUM(CASE WHEN cf.status = 1 THEN 1 ELSE 0 END) AS completed_count,
    SUM(CASE WHEN cf.status = 2 THEN 1 ELSE 0 END) AS unavailable_count,
    SUM(CASE WHEN cf.status = 3 THEN 1 ELSE 0 END) AS reconfirmation_count
FROM confirmation_followup cf

INNER JOIN (
    SELECT req_id, MAX(created_date) AS max_date
    FROM confirmation_followup
    WHERE DATE(created_date) <= '$to_date'
      AND insert_login_id IN ($user_id_str)
    GROUP BY req_id
) latest
ON cf.req_id = latest.req_id
AND cf.created_date = latest.max_date

JOIN acknowlegement_customer_profile cp 
    ON cf.req_id = cp.req_id
JOIN area_list_creation al 
    ON cp.area_confirm_area = al.area_id
JOIN area_line_mapping alm 
    ON FIND_IN_SET(al.area_id, alm.area_id)

WHERE DATE(cf.created_date) BETWEEN '$from_date' AND '$to_date'
GROUP BY alm.line_name
ORDER BY alm.line_name;
");

$results = $qry->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $data[] = [
        "sno"                 => $sno++,
        "fullname"      => $user_fullname,
        "line"                => $row['line_name'],
        "total_count"         => (int)$row['total_count'],
        "t_completed_count"   => (int)$row['completed_count'],
        "t_unavailable_count" => (int)$row['unavailable_count'],
        "t_reconfirmation"    => (int)$row['reconfirmation_count'],
    ];
}

/* ---------- TOTAL ROW ---------- */
$data[] = [
    "sno"                 => "",
    "fullname"                 => "",
    "line"                => "Total",
    "total_count"         => array_sum(array_column($data, "total_count")),
    "t_completed_count"   => array_sum(array_column($data, "t_completed_count")),
    "t_unavailable_count" => array_sum(array_column($data, "t_unavailable_count")),
    "t_reconfirmation"    => array_sum(array_column($data, "t_reconfirmation")),
];

echo json_encode(["data" => $data]);
