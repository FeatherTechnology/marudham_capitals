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

$userids = (!empty($user_id_str)) ? "AND insert_login_id IN ($user_id_str)" : '';

$selectedType = $_POST['selectedType'] ?? '';
$selectedVal = $_POST['selectedVal'] ?? '';

if(is_array($selectedVal)) {
    $selectedVal = implode(',', $selectedVal);
}

$loanCatVal = $_POST['loanCatVal'] ?? '';

if(is_array($loanCatVal)) {
    $loanCatVal = implode(',', $loanCatVal);
}

$colname ='';
$group_order ='alm.line_name';
$joinTable ='';
$mapidcondition = '';
$condtn = '';

if ($selectedType == '2') { //Sector
    $colname = ", agm.group_name AS mapname";
    $group_order = "agm.group_name";
    $joinTable  = "  JOIN area_group_mapping_sub_area agmsa ON iv.sub_area = agmsa.sub_area_id
    JOIN area_group_mapping agm ON agmsa.group_map_id = agm.map_id";
    $mapidcondition  = "AND agmsa.group_map_id IN ($selectedVal)";

} else if ($selectedType == '3') { //Region
    $colname = ", alm.line_name AS mapname";
    $group_order = "alm.line_name";
    $joinTable = "  JOIN area_line_mapping_sub_area almsa ON iv.sub_area = almsa.sub_area_id";
    $mapidcondition = "AND almsa.line_map_id IN ($selectedVal)";
    
} else if ($selectedType == '4') { //Zone
    $colname = ", adm.duefollowup_name AS mapname";
    $group_order = "adm.duefollowup_name";
    $joinTable = "  JOIN area_duefollowup_mapping_area adma ON iv.area = adma.area_id
    JOIN area_duefollowup_mapping adm ON adma.duefollowup_map_id = adm.map_id";
    $mapidcondition = "AND adma.duefollowup_map_id IN ($selectedVal)";
} 

if($selectedType =='2' || $selectedType =='3' || $selectedType =='4'){
    $condtn  = "AND iv.loan_category IN ($loanCatVal)";
}

$data = [];
$sno = 1;

$qry = $connect->query("
   SELECT 
        u.fullname,
        lcc.loan_category_creation_name,
        alm.line_name,
        COUNT(DISTINCT cf.req_id) AS total_count,
        SUM(CASE WHEN cf.status = 1 THEN 1 ELSE 0 END) AS completed_count,
        SUM(CASE WHEN cf.status = 2 THEN 1 ELSE 0 END) AS unavailable_count,
        SUM(CASE WHEN cf.status = 3 THEN 1 ELSE 0 END) AS reconfirmation_count
        $colname
    FROM confirmation_followup cf
    JOIN user u ON cf.insert_login_id = u.user_id

    INNER JOIN (
        SELECT req_id, MAX(created_date) AS max_date
        FROM confirmation_followup
        WHERE DATE(created_date) <= '$to_date'
        $userids
        GROUP BY req_id
    ) latest
    ON cf.req_id = latest.req_id
    AND cf.created_date = latest.max_date

    JOIN in_verification iv ON cf.req_id = iv.req_id
    JOIN loan_category_creation lcc ON iv.loan_category = lcc.loan_category_creation_id
    JOIN area_list_creation al ON iv.area = al.area_id
    JOIN area_line_mapping_area alma ON al.area_id = alma.area_id
    JOIN area_line_mapping alm ON alma.line_map_id = alm.map_id
    $joinTable

    WHERE (DATE(cf.created_date) BETWEEN '$from_date' AND '$to_date')
    $mapidcondition
    $condtn
    GROUP BY iv.loan_category, $group_order
    ORDER BY $group_order ASC;
    ");

$results = $qry->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $data[] = [
        "sno"                 => $sno++,
        "fullname"            => ($selectedType =='1') ? $row['fullname'] : $row['mapname'],
        "loan_category"       => $row['loan_category_creation_name'],
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
    "fullname"            => "",
    "loan_category"       => "",
    "line"                => "Total",
    "total_count"         => array_sum(array_column($data, "total_count")),
    "t_completed_count"   => array_sum(array_column($data, "t_completed_count")),
    "t_unavailable_count" => array_sum(array_column($data, "t_unavailable_count")),
    "t_reconfirmation"    => array_sum(array_column($data, "t_reconfirmation")),
];

echo json_encode(["data" => $data]);
