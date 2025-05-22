<?php

session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = "";

if ($userid != 1) {

    $userQry = $connect->query("SELECT line_id, report_access FROM USER WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
        $line_id = $rowuser['line_id'];
        $report_access = $rowuser['report_access'];
    
    if($report_access =='1'){
        $line_id = explode(',', $line_id);
        $sub_area_list = array();
        foreach ($line_id as $line) {
            $lineQry = $connect->query("SELECT sub_area_id FROM area_line_mapping where map_id = $line ");
            $row_sub = $lineQry->fetch();
            $sub_area_list[] = $row_sub['sub_area_id'];
        }
        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = array();
        $sub_area_list = implode(',', $sub_area_ids);

        $user_based = " AND cp.area_confirm_subarea IN ($sub_area_list) AND cs.insert_login_id = '$userid' ";
    }
}

$where = "";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "AND (date(cs.created_date) >= '" . $from_date . "') AND (date(cs.created_date) <= '" . $to_date . "') ";
}

$where .= $user_based;

$closed_sts_arr = [
    '1' => 'Consider',
    '2' => 'Waiting List',
    '3' => 'Block List'
];
$closed_lvl_arr = [
    '1' => 'Bronze',
    '2' => 'Silver',
    '3' => 'Gold',
    '4' => 'Platinum',
    '5' => 'Diamond'
];

$column = array(
    'ii.id',
    'alm.line_name',
    'ii.loan_id',
    'ad.doc_id',
    'ii.updated_date',
    'cp.cus_id',
    'cp.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'lcc.loan_category_creation_name',
    'lc.sub_category',
    'ac.ag_name',
    'lc.loan_amt_cal',
    'lc.maturity_month',
    'cs.created_date',
    'ii.id',
    'cs.closed_sts',
    'cs.consider_level'
);

$query = "SELECT 
    alm.line_name AS line,
    ii.loan_id,
    ad.doc_id,
    ii.updated_date AS loan_date,
    cp.req_id,
    cp.cus_id,
    cp.cus_name,
    al.area_name,
    sal.sub_area_name,
    ac.ag_name,
    lcc.loan_category_creation_name AS loan_cat_name,
    lc.sub_category,
    lc.loan_amt_cal,
    lc.maturity_month,
    cs.created_date,
    cs.closed_sts,
    cs.consider_level,
    coll_most_frequent.coll_location
FROM 
    in_issue ii
JOIN 
    acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
JOIN 
    acknowlegement_loan_calculation lc ON ii.req_id = lc.req_id
JOIN 
    acknowlegement_documentation ad ON ii.req_id = ad.req_id
JOIN 
    area_list_creation al ON cp.area_confirm_area = al.area_id
JOIN 
    sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
JOIN
    area_line_mapping alm ON FIND_IN_SET(al.area_id, alm.area_id)
LEFT JOIN 
    loan_category_creation lcc ON lcc.loan_category_creation_id = lc.loan_category
LEFT JOIN 
    closed_status cs ON ii.req_id = cs.req_id
LEFT JOIN 
    in_verification iv ON ii.req_id = iv.req_id
LEFT JOIN 
    agent_creation ac ON iv.agent_id = ac.ag_id
LEFT JOIN (
    SELECT 
        req_id, 
        coll_location
    FROM (
        SELECT 
            req_id, 
            coll_location, 
            ROW_NUMBER() OVER (PARTITION BY req_id ORDER BY COUNT(coll_location) DESC) AS row_num
        FROM 
            collection
        GROUP BY 
            req_id, coll_location
    ) AS ranked_coll
    WHERE row_num = 1
) AS coll_most_frequent ON ii.req_id = coll_most_frequent.req_id
WHERE 
    ii.cus_status >= 20 
    $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (alm.line_name LIKE '%" . $_POST['search'] . "%' OR
            ii.loan_id LIKE '%" . $_POST['search'] . "%' OR
            ad.doc_id LIKE '%" . $_POST['search'] . "%' OR
            ii.updated_date LIKE '%" . $_POST['search'] . "%' OR
            cp.cus_id LIKE '%" . $_POST['search'] . "%' OR
            cp.cus_name LIKE '%" . $_POST['search'] . "%' OR
            al.area_name LIKE '%" . $_POST['search'] . "%' OR
            sal.sub_area_name LIKE '%" . $_POST['search'] . "%' OR
            lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%' OR
            lc.sub_category LIKE '%" . $_POST['search'] . "%' OR
            lc.maturity_month LIKE '%" . $_POST['search'] . "%' OR
            cs.closed_sts LIKE '%" . $_POST['search'] . "%' OR
            cs.consider_level LIKE '%" . $_POST['search'] . "%' OR
            cs.created_date LIKE '%" . $_POST['search'] . "%' ) ";
    }
}
if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = "";
if ($_POST['length'] != -1) {
    $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

if ($_POST['length'] != -1) {
    $statement = $connect->prepare($query . $query1);
    $statement->execute();
}
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = $row['doc_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = moneyFormatIndia($row['loan_amt_cal']);
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_month']));
    $sub_array[] = date('d-m-Y', strtotime($row['created_date']));

    $coll_location_arr = ['1' => 'By Self', '2' => 'On Spot'];
    $sub_array[] = $coll_location_arr[$row['coll_location']];

    $sub_array[] = $closed_sts_arr[$row['closed_sts']];
    $sub_array[] = $closed_lvl_arr[$row['consider_level']] ?? '';

    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query = $connect->query("SELECT count(req_id) as req_count FROM request_creation where cus_status >= 20 ");
    $statement = $query->fetch();
    return $statement['req_count'];
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

// Close the database connection
$connect = null;
