<?php
include '../../ajaxconfig.php';

//$originName = ['renewal' => 1, 're_active' => 4, 'new_promotion' => 2, 'repromotion' => 3]
//$promo_type = ['Direct' => 1, 'Mobile' => 2]
$where = "1=1";

$condition = "";

$user_type = $_POST['user_type'] ?? '';

if ($user_type == '2') {
    $condition .= " AND u.status = 0";
} elseif ($user_type == '3') {
    $condition .= " AND u.status = 1";
}

$selectedType = $_POST['selectedType'] ?? '';
$selectedVal = $_POST['selectedVal'] ?? '';

if(is_array($selectedVal)) {
    $selectedVal = implode(',', $selectedVal);
}

$joinTable ='';
$mapidcondition = '';

if ($selectedType == '2') { //Sector
    $joinTable  = "  JOIN area_group_mapping_sub_area agmsa ON cr.sub_area = agmsa.sub_area_id";
    $mapidcondition  = "AND agmsa.group_map_id IN ($selectedVal)";

} else if ($selectedType == '3') { //Region
    $joinTable = "  JOIN area_line_mapping_sub_area almsa ON cr.sub_area = almsa.sub_area_id";
    $mapidcondition = "AND almsa.line_map_id IN ($selectedVal)";
    
} else if ($selectedType == '4') { //Zone
    $joinTable = "  JOIN area_duefollowup_mapping_area adma ON cr.area = adma.area_id";
    $mapidcondition = "AND adma.duefollowup_map_id IN ($selectedVal)";
} 

/* ---------- DATES ---------- */
if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = $_POST['from_date'] . " 00:00:00";
    $to_date   = date('Y-m-d', strtotime($_POST['to_date'] . ' +1 day')) . " 00:00:00";

    $where .= " AND np.created_date >= '$from_date' AND np.created_date < '$to_date'";
}

/* ---------- USER ID ---------- */
$user_ids = $_POST['user_id'] ?? '';
if($user_ids != '0' && !empty($user_id)){
    $user_ids = preg_replace('/[^0-9,]/', '', $user_ids); // clean
    $id_list = implode(',', array_filter(explode(',', $user_ids), 'is_numeric'));
    if (!empty($id_list)) {
        $where .= " AND np.insert_login_id IN ($id_list) ";
    }
}

/* ---------- COLUMN ---------- */
$column = array(
    'u.fullname',
    'total'
);

/* ---------- BASE QUERY ---------- */
$base_query = "FROM new_promotion np
LEFT JOIN user u ON np.insert_login_id = u.user_id
LEFT JOIN customer_register cr ON np.cus_id = cr.cus_id
$joinTable
WHERE $where $condition $mapidcondition";

/* ---------- GROUP BY ---------- */
$group_by = "GROUP BY np.insert_login_id, np.promo_type, np.status, np.orgin_table";
$filter_group_by = "GROUP BY np.insert_login_id";

/* ---------- SEARCH ---------- */
if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $base_query .= " AND (np.created_date LIKE '%" . $_POST['search'] . "%' 
            OR u.fullname LIKE '%" . $_POST['search'] . "%')";
    }
}

/* ---------- ORDER ---------- */
$orderBy ='';
if (isset($_POST['order'])) {
    $orderBy .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
}

/* ---------- PAGINATION ---------- */
$limit = '';
if ($_POST['length'] != -1) {
    $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

/* ---------- TOTAL RECORDS ---------- */
$totalStmt = $connect->prepare("SELECT COUNT(DISTINCT np.insert_login_id) FROM new_promotion np");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

/* ---------- FILTERED RECORDS ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) FROM (SELECT np.id $base_query $filter_group_by) AS sq");
$countStmt->execute();
$recordsFiltered = (int) $countStmt->fetchColumn();

/* ---------- DATA QUERY ---------- */
$data_query = "SELECT 
    np.insert_login_id,
    u.fullname,
    np.promo_type,
    np.status,
    np.orgin_table,
    COUNT(*) as total
    $base_query
    $group_by
    $orderBy
    $limit";

$statement = $connect->prepare($data_query);
$statement->execute();
$result = $statement->fetchAll();

$finalData = [];

foreach ($result as $row) {

    $user = $row['insert_login_id'];

    if (!isset($finalData[$user])) {
        $finalData[$user] = [
            'name' => $row['fullname'],

            // initialize all columns = 0
            'mobile_interest_new' => 0,
            'mobile_interest_renewal' => 0,
            'mobile_interest_reactive' => 0,
            'mobile_interest_repromotion' => 0,

            'mobile_not_interest_new' => 0,
            'mobile_not_interest_renewal' => 0,
            'mobile_not_interest_reactive' => 0,
            'mobile_not_interest_repromotion' => 0,

            'direct_interest_new' => 0,
            'direct_interest_renewal' => 0,
            'direct_interest_reactive' => 0,
            'direct_interest_repromotion' => 0,

            'direct_not_interest_new' => 0,
            'direct_not_interest_renewal' => 0,
            'direct_not_interest_reactive' => 0,
            'direct_not_interest_repromotion' => 0
        ];
    }

    $key = '';

    // promo_type
    $type = ($row['promo_type'] == 1) ? 'direct' : 'mobile';

    // status
    $status = ($row['status'] == 'Interested') ? 'interest' : 'not_interest';

    // origin
    switch ($row['orgin_table']) {
        case 1: $origin = 'renewal'; break;
        case 2: $origin = 'new'; break;
        case 3: $origin = 'repromotion'; break;
        case 4: $origin = 'reactive'; break;
        default: $origin = ''; break;
    }

    $key = "{$type}_{$status}_{$origin}";

    $finalData[$user][$key] = $row['total'];
}

$data = [];

foreach ($finalData as $row) {
    $sub_array = [];

    $sub_array[] = $row['name'];

    //Mobile interest
    $sub_array[] = $row['mobile_interest_new'];
    $sub_array[] = $row['mobile_interest_renewal'];
    $sub_array[] = $row['mobile_interest_reactive'];
    $sub_array[] = $row['mobile_interest_repromotion'];
    $sub_array[] = array_sum([
        $row['mobile_interest_new'],
        $row['mobile_interest_renewal'],
        $row['mobile_interest_reactive'],
        $row['mobile_interest_repromotion']
    ]);

    //Mobile not interest
    $sub_array[] = $row['mobile_not_interest_new'];
    $sub_array[] = $row['mobile_not_interest_renewal'];
    $sub_array[] = $row['mobile_not_interest_reactive'];
    $sub_array[] = $row['mobile_not_interest_repromotion'];
    $sub_array[] = array_sum([
        $row['mobile_not_interest_new'],
        $row['mobile_not_interest_renewal'],
        $row['mobile_not_interest_reactive'],
        $row['mobile_not_interest_repromotion']
    ]);

    //Direct interest
    $sub_array[] = $row['direct_interest_new'];
    $sub_array[] = $row['direct_interest_renewal'];
    $sub_array[] = $row['direct_interest_reactive'];
    $sub_array[] = $row['direct_interest_repromotion'];
    $sub_array[] = array_sum([
        $row['direct_interest_new'],
        $row['direct_interest_renewal'],
        $row['direct_interest_reactive'],
        $row['direct_interest_repromotion']
    ]);

    //Direct not interest
    $sub_array[] = $row['direct_not_interest_new'];
    $sub_array[] = $row['direct_not_interest_renewal'];
    $sub_array[] = $row['direct_not_interest_reactive'];
    $sub_array[] = $row['direct_not_interest_repromotion'];
    $sub_array[] = array_sum([
        $row['direct_not_interest_new'],
        $row['direct_not_interest_renewal'],
        $row['direct_not_interest_reactive'],
        $row['direct_not_interest_repromotion']
    ]);

    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
