<?php
include '../../ajaxconfig.php';

$from_date = $_POST['from_date'];
$to_date   = $_POST['to_date'];
$user_id   = $_POST['user_id'];

$data = [];
$sno = 1;

/* -----------------------------
   USER FILTER
--------------------------------*/

$user_condition = "";

if ($user_id != 'all') {

    if (!is_array($user_id)) {
        $user_id = explode(',', $user_id);
    }

    $user_id = array_map('intval', $user_id);
    $user_id_str = implode(',', $user_id);

    $user_condition = "AND c.insert_login_id IN ($user_id_str)";
}


/* -----------------------------
   MAIN QUERY
--------------------------------*/

$qry = $connect->query("
SELECT 
u.fullname,

COUNT(DISTINCT c.cus_id) AS total_customer,
COUNT(c.id) AS total_entries,

/* MOBILE */
SUM(CASE WHEN c.ftype = 2 AND c.fstatus = 1 THEN 1 ELSE 0 END) AS mobile_commitment,
SUM(CASE WHEN c.ftype = 2 AND c.fstatus BETWEEN 2 AND 7 THEN 1 ELSE 0 END) AS mobile_unavailable,
SUM(CASE WHEN c.ftype = 2 AND c.fstatus = 8 THEN 1 ELSE 0 END) AS mobile_paid,
SUM(CASE WHEN c.ftype = 2 THEN 1 ELSE 0 END) AS mobile_total,

/* DIRECT */
SUM(CASE WHEN c.ftype = 1 AND c.fstatus = 1 THEN 1 ELSE 0 END) AS direct_commitment,
SUM(CASE WHEN c.ftype = 1 AND c.fstatus BETWEEN 2 AND 7 THEN 1 ELSE 0 END) AS direct_unavailable,
SUM(CASE WHEN c.ftype = 1 AND c.fstatus = 8 THEN 1 ELSE 0 END) AS direct_paid,
SUM(CASE WHEN c.ftype = 1 THEN 1 ELSE 0 END) AS direct_total

FROM commitment c
LEFT JOIN user u ON u.user_id = c.insert_login_id

WHERE DATE(c.created_date) BETWEEN '$from_date' AND '$to_date'
$user_condition

GROUP BY c.insert_login_id
ORDER BY u.fullname
");


while ($row = $qry->fetch()) {

    $data[] = [

        "sno" => $sno++,
        "fullname" => $row['fullname'],

        "total_customer" => $row['total_customer'],
        "total_entries" => $row['total_entries'],

        "mobile" => [
            "commitment" => $row['mobile_commitment'],
            "unavailable" => $row['mobile_unavailable'],
            "paid" => $row['mobile_paid'],
            "total" => $row['mobile_total']
        ],

        "direct" => [
            "commitment" => $row['direct_commitment'],
            "unavailable" => $row['direct_unavailable'],
            "paid" => $row['direct_paid'],
            "total" => $row['direct_total']
        ]
    ];
}


/* -----------------------------
   TOTAL ROW
--------------------------------*/

$total_customer = array_sum(array_column($data,'total_customer'));
$total_entries = array_sum(array_column($data,'total_entries'));

$mobile_commitment = 0;
$mobile_unavailable = 0;
$mobile_paid = 0;
$mobile_total = 0;

$direct_commitment = 0;
$direct_unavailable = 0;
$direct_paid = 0;
$direct_total = 0;

foreach($data as $row){

    $mobile_commitment += $row['mobile']['commitment'];
    $mobile_unavailable += $row['mobile']['unavailable'];
    $mobile_paid += $row['mobile']['paid'];
    $mobile_total += $row['mobile']['total'];

    $direct_commitment += $row['direct']['commitment'];
    $direct_unavailable += $row['direct']['unavailable'];
    $direct_paid += $row['direct']['paid'];
    $direct_total += $row['direct']['total'];
}

$data[] = [

    "sno" => "",
    "fullname" => "Total",

    "total_customer" => $total_customer,
    "total_entries" => $total_entries,

    "mobile" => [
        "commitment" => $mobile_commitment,
        "unavailable" => $mobile_unavailable,
        "paid" => $mobile_paid,
        "total" => $mobile_total
    ],

    "direct" => [
        "commitment" => $direct_commitment,
        "unavailable" => $direct_unavailable,
        "paid" => $direct_paid,
        "total" => $direct_total
    ]
];

echo json_encode(["data" => $data]);

?>