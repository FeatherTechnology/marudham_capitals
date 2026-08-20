<?php
session_start();
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
    $report_access = '2'; //Report Access Overall
}

$user_based = '';
if ($userid != 1) {

    $userQry = $connect->query("SELECT report_access FROM user WHERE user_id = $userid ");
    $rowuser = $userQry->fetch();
    $report_access = $rowuser['report_access'];

    if ($report_access == '1') { //Report access individual.
        
        $user_based = "AND coll.insert_login_id = '$userid' ";
    }
}

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']. ' +1 day'));

    $where  .= " AND (coll.coll_date >= '$from_date' and coll.coll_date < '$to_date')";
}

$collection_type = $_POST['collection_type'] ?? '';
$cash_type ="";
if($collection_type == '1'){ //Cash
    $cash_type = " AND coll.coll_mode = '1'";
} else if($collection_type == '2'){ //Bank
    $cash_type = " AND coll.coll_mode != '1'";
}


$where  .= $cash_type . $user_based;

$statusObj = [
    '14' => 'Current',
    '15' => 'Error',
    '16' => 'Legal',
    '17' => 'Current',
    '20' => 'In Closed',
    '21' => 'Closed',
];
$consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
$role_arr = [1 => 'Director', 2 => 'Agent', 3 => 'Staff'];
$coll_arr = [1 => 'Cash', 2 => 'Cheque', 3 => 'ECS', 4 => 'IMPS/NEFT/RTGS', 5 => 'UPI Transaction'];
$coll_method = [1 => 'By Self', 2 => 'On Spot'];

$column = array(
    'coll.coll_id',  
    'alm.line_name',
    'agm.group_name',
    'adm.duefollowup_name',
    'bc.branch_name',
    'ii.loan_id',
    'ii.updated_date',
    'coll.cus_id',
    'cr.autogen_cus_id',
    'coll.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'lcc.loan_category_creation_name',
    'lc.sub_category',
    'ac.ag_name',
    'u.role',
    'u.fullname',
    'coll.coll_location',
    'coll.coll_date',
    'coll.coll_mode',
    'b.bank_name',
    'coll.trans_date',
    'coll.due_amt_track',
    'coll.penalty_track',
    'coll.coll_charge_track',
    'coll.total_paid_track',
    'ii.id',
    'iv.cus_status',
    'coll.pre_close_waiver',
    'coll.penalty_waiver',
    'coll.coll_charge_waiver',
    'coll.total_waiver'
);


$baseQuery = "FROM collection coll
        JOIN in_verification iv ON coll.req_id = iv.req_id AND iv.cus_status >= 14
        JOIN acknowlegement_loan_calculation lc ON coll.req_id = lc.req_id AND lc.due_type != 'Interest'
        JOIN customer_register cr ON coll.cus_id = cr.cus_id
        JOIN acknowlegement_customer_profile cp ON coll.req_id = cp.req_id
        JOIN in_issue ii ON coll.req_id = ii.req_id
        JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
        LEFT JOIN area_group_mapping_sub_area agmsa ON sal.sub_area_id = agmsa.sub_area_id
        LEFT JOIN area_group_mapping agm ON agmsa.group_map_id = agm.map_id
        LEFT JOIN area_line_mapping_sub_area almsa ON sal.sub_area_id = almsa.sub_area_id
        LEFT JOIN area_line_mapping alm ON almsa.line_map_id = alm.map_id
        LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
        LEFT JOIN area_duefollowup_mapping_area adma ON al.area_id = adma.area_id
        LEFT JOIN area_duefollowup_mapping adm ON adma.duefollowup_map_id = adm.map_id
        LEFT JOIN bank_creation b ON coll.bank_id = b.id
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
        JOIN user u ON coll.insert_login_id = u.user_id
        LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id
        LEFT JOIN closed_status cls ON iv.req_id = cls.req_id

        WHERE $where ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $baseQuery .= " AND (ii.loan_id LIKE '%" . $_POST['search'] . "%'
                    OR agm.group_name LIKE '%" . $_POST['search'] . "%' 
                    OR alm.line_name LIKE '%" . $_POST['search'] . "%'
                    OR bc.branch_name LIKE '%" . $_POST['search'] . "%'
                    OR adm.duefollowup_name LIKE '%" . $_POST['search'] . "%'
                    OR ii.updated_date LIKE '%" . $_POST['search'] . "%'
                    OR coll.cus_id LIKE '%" . $_POST['search'] . "%'
                    OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
                    OR coll.cus_name LIKE '%" . $_POST['search'] . "%'
                    OR al.area_name LIKE '%" . $_POST['search'] . "%'
                    OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%'
                    OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%'
                    OR lc.sub_category LIKE '%" . $_POST['search'] . "%'
                    OR ac.ag_name LIKE '%" . $_POST['search'] . "%'
                    OR u.role LIKE '%" . $_POST['search'] . "%'
                    OR u.fullname LIKE '%" . $_POST['search'] . "%'
                    OR coll.coll_location LIKE '%" . $_POST['search'] . "%'
                    OR b.bank_name LIKE '%" . $_POST['search'] . "%'
                    OR coll.trans_date LIKE '%" . $_POST['search'] . "%'
                    OR coll.coll_date LIKE '%" . $_POST['search'] . "%') ";
    }
}

// $query .= " GROUP BY coll.coll_id ";

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


/* ---------- Data query ---------- */
$dataQuery = "SELECT 
            agm.group_name,
            alm.line_name AS line,
            bc.branch_name,
            adm.duefollowup_name,
            ii.loan_id,
            ii.updated_date AS loan_date,
            coll.cus_id,
            cr.autogen_cus_id,
            coll.req_id,
            coll.cus_name,
            coll.coll_mode,
            al.area_name,
            sal.sub_area_name,
            lcc.loan_category_creation_name AS loan_cat_name,
            lc.sub_category,
            lc.due_type,
            lc.due_period,
            lc.principal_amt_cal,
            lc.int_amt_cal,
            ac.ag_name,
            u.role,
            u.fullname,
            coll.coll_location,
            coll.coll_date,
            coll.trans_date,
            b.bank_name,
            coll.due_amt_track,
            coll.princ_amt_track,
            coll.int_amt_track,
            coll.penalty_track,
            coll.coll_charge_track,
            coll.total_paid_track,
            coll.pre_close_waiver,
            coll.penalty_waiver,
            coll.coll_charge_waiver,
            coll.total_waiver,
            iv.cus_status,
            cls.closed_sts,
            cls.consider_level
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
    $sub_array[] = $row['duefollowup_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['autogen_cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = $role_arr[$row['role']];
    $sub_array[] = $row['fullname'];
    $sub_array[] = $coll_method[$row['coll_location']];
    $sub_array[] = date('d-m-Y', strtotime($row['coll_date']));
    $sub_array[] = $coll_arr[$row['coll_mode']];
    if ($row['coll_mode'] != 1) {
        $sub_array[] = $row['bank_name'];
        $sub_array[] = date('d-m-Y', strtotime($row['trans_date']));
    } else {
        $sub_array[] = '';
        $sub_array[] = '';
    }
    $sub_array[] = moneyFormatIndia(intVal($row['due_amt_track']));
    $sub_array[] = moneyFormatIndia(intval($row['penalty_track']));
    $sub_array[] = moneyFormatIndia(intval($row['coll_charge_track']));
    $sub_array[] = moneyFormatIndia(intval($row['total_paid_track']));

    if ($row['cus_status'] >= '20') {
        $sub_array[] = 'Closed';
        if ($row['closed_sts'] != '' && $row['closed_sts'] != NULL) {
            $rclosed = $row['closed_sts'];
            $consider_lvl = $row['consider_level'];
            if ($rclosed == '1') {
                $sub_array[] = 'Consider - ' . $consider_lvl_arr[$consider_lvl];
            } else if ($rclosed == '2') {
                $sub_array[] = 'Waiting List';
            } else if ($rclosed == '3') {
                $sub_array[] = 'Block List';
            }
        } else {
            $sub_array[] = $statusObj[$row['cus_status']];
        }
    } else {
        $sub_array[] = 'Present';
        $sub_array[] = $statusObj[$row['cus_status']];
    }

    $sub_array[] = moneyFormatIndia(intVal($row['pre_close_waiver']));
    $sub_array[] = moneyFormatIndia(intval($row['penalty_waiver']));
    $sub_array[] = moneyFormatIndia(intval($row['coll_charge_waiver']));
    $sub_array[] = moneyFormatIndia(intval($row['total_waiver']));

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