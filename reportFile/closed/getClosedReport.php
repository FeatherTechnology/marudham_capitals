<?php

session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //if super Admin login use need to show overall.
}

$user_based = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT report_access FROM user WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') { //Report access individual.
        $user_based = " AND cs.insert_login_id = '$userid' ";
    }
}

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d 00:00:00', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d 23:59:59', strtotime($_POST['to_date']));
    $where = "AND cs.created_date BETWEEN '$from_date' AND '$to_date'";
}

$branch_name = is_array($_POST['branch'] ?? null)
    ? implode(',', $_POST['branch'])
    : '';
$loan_cat_id = is_array($_POST['loan_category'] ?? null)
    ? implode(',', $_POST['loan_category'])
    : '';

if($branch_name !='' && $loan_cat_id !=''){ //Branch & Loan category.
    $where .= " AND bc.branch_id IN ($branch_name) && lcc.loan_category_creation_id IN ($loan_cat_id)";

} else if($branch_name !='' && $loan_cat_id ==''){ //Branch
    $where .= " AND bc.branch_id IN ($branch_name)";

} else if($branch_name =='' && $loan_cat_id !=''){ //Loan Category
    $where .= " AND lcc.loan_category_creation_id IN ($loan_cat_id)";

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

$coll_location_arr = [
    '1' => 'By Self',
    '2' => 'On Spot'
];

$column = array(
    'cs.id',
    'alm.line_name',
    'agm.group_name',
    'bc.branch_name',
    'ii.loan_id',
    'ad.doc_id',
    'ii.updated_date',
    'cp.cus_id',
    'cr.autogen_cus_id',
    'cp.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'lcc.loan_category_creation_name',
    'lc.sub_category',
    'ac.ag_name',
    'lc.loan_amt_cal',
    'lc.maturity_month',
    'cs.created_date',
    'u.role',
    'u.fullname',
    'ii.id',
    'cs.closed_sts',
    'cs.consider_level'
);

$baseQuery = "FROM in_issue ii
            JOIN customer_register cr ON ii.cus_id = cr.cus_id
            JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
            JOIN acknowlegement_loan_calculation lc ON ii.req_id = lc.req_id
            JOIN acknowlegement_documentation ad ON ii.req_id = ad.req_id
            JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
            JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
            JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sal.sub_area_id
            JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
            JOIN branch_creation bc ON agm.branch_id = bc.branch_id
            JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sal.sub_area_id
            JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id
            LEFT JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = lc.loan_category
            LEFT JOIN closed_status cs ON ii.req_id = cs.req_id
            LEFT JOIN user u ON u.user_id = cs.insert_login_id
            LEFT JOIN in_verification iv ON ii.req_id = iv.req_id
            LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id
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
            WHERE ii.cus_status >= 20 AND lc.due_type != 'Interest'
            $where";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $search = $_POST['search'];
        $baseQuery .= " and (agm.group_name LIKE '%" . $search . "%'OR 
            alm.line_name LIKE '".$search."%' OR
            bc.branch_name LIKE '%" . $search . "%' OR
            ii.loan_id LIKE '%" . $search . "%' OR
            ad.doc_id LIKE '%" . $search . "%' OR
            ii.updated_date LIKE '%" . $search . "%' OR
            cp.cus_id LIKE '%" . $search . "%' OR
            cr.autogen_cus_id LIKE '%" . $search . "%' OR
            cp.cus_name LIKE '%" . $search . "%' OR
            al.area_name LIKE '%" . $search . "%' OR
            sal.sub_area_name LIKE '%" . $search . "%' OR
            lcc.loan_category_creation_name LIKE '%" . $search . "%' OR
            lc.sub_category LIKE '%" . $search . "%' OR
            ac.ag_name LIKE '%" . $search . "%' OR
            lc.maturity_month LIKE '%" . $search . "%' OR
            u.role LIKE '%" . $search . "%' OR
            u.fullname LIKE '%" . $search . "%' OR
            cs.closed_sts LIKE '%" . $search . "%' OR
            cs.consider_level LIKE '%" . $search . "%' OR
            cs.created_date LIKE '%" . $search . "%' ) ";
    }
}

/* ---------- ORDER ---------- */
$orderBy = '';
if (isset($_POST['order'])) {
    $orderBy = " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
}

/* ---------- Pagination ---------- */
$limit = '';
if (!isset($_POST['download'])) {
    if ($_POST['length'] != -1) {
        $limit = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
    }
}

/* ---------- Filtered records ---------- */
$countStmt = $connect->prepare("SELECT COUNT(*) $baseQuery");
$countStmt->execute();
$recordsFiltered = (int) $countStmt->fetchColumn();

$dataQuery = "SELECT 
        agm.group_name,
        alm.line_name AS line,
        bc.branch_name,
        ii.loan_id,
        ad.doc_id,
        ii.updated_date AS loan_date,
        cp.req_id,
        cp.cus_id,
        cr.autogen_cus_id,
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
        coll_most_frequent.coll_location,
        u.fullname AS closed_user_name,
        CASE u.role
            WHEN 1 THEN 'Director'
            WHEN 2 THEN 'Agent'
            WHEN 3 THEN 'Staff'
            ELSE ''
        END AS closed_user_type
        $baseQuery
        $orderBy
        $limit
    ";

$statement = $connect->prepare($dataQuery);
$statement->execute();
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $sub_array[] = $sno++;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['group_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = $row['doc_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = moneyFormatIndia($row['loan_amt_cal']);
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_month']));
    $sub_array[] = date('d-m-Y', strtotime($row['created_date']));
    $sub_array[] = $row['closed_user_type'];
    $sub_array[] = $row['closed_user_name'];
    $sub_array[] = $coll_location_arr[$row['coll_location']];
    $sub_array[] = $closed_sts_arr[$row['closed_sts']];
    $sub_array[] = $closed_lvl_arr[$row['consider_level']] ?? '';

    $data[]      = $sub_array;
}

$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0, // ✅ safe for both table & download
    'recordsFiltered' => $recordsFiltered,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;