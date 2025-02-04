<?php

session_start();
include '../../ajaxconfig.php';

$where = "1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = "(date(coll.coll_date) >= '" . $from_date . "') and (date(coll.coll_date) <= '" . $to_date . "') ";
}

if (isset($_SESSION["userid"])) {
    $userid = $_SESSION["userid"];
}
if ($userid != 1) {

    $userQry = $connect->query("SELECT * FROM USER WHERE user_id = $userid ");
    while ($rowuser = $userQry->fetch()) {
        $group_id = $rowuser['group_id'];
        $line_id = $rowuser['line_id'];
    }

    $line_id = explode(',', $line_id);
    $sub_area_list = array();
    foreach ($line_id as $line) {
        $lineQry = $connect->query("SELECT * FROM area_line_mapping where map_id = $line ");
        $row_sub = $lineQry->fetch();
        $sub_area_list[] = $row_sub['sub_area_id'];
    }
    $sub_area_ids = array();
    foreach ($sub_area_list as $subarray) {
        $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
    }
    $sub_area_list = array();
    $sub_area_list = implode(',', $sub_area_ids);
}

$statusObj = [
    '14' => 'Current',
    '15' => 'Error',
    '16' => 'Legal',
    '17' => 'Current',
    '20' => 'In Closed',
    '21' => 'Closed',
];
$consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
$role_arr = [1=>'Director',2=>'Agent',3=>'Staff'];
$coll_arr = [1=>'Cash',2=>'Cheque',3=>'ECS',4=>'IMPS/NEFT/RTGS',5=>'UPI Transaction'];

$column = array(
    'cp.id',
    'cp.id',
    'ii.loan_id',
    'ii.updated_date',
    'coll.cus_id',
    'coll.cus_name',
    'al.area_name',
    'sal.sub_area_name',
    'cp.id',
    'cp.id',
    'cp.id',
    'u.role',
    'u.fullname',
    'coll.coll_date',
    'coll.coll_mode',
    'SUM(coll.due_amt_track)',
    'ii.id',
    'ii.id',
    'SUM(coll.penalty_track)',
    'SUM(coll.coll_charge_track)',
    'SUM(coll.total_paid_track)',
    'ii.id',
    'ii.id'
);

$query = "SELECT 
                cp.area_line AS line,
                ii.loan_id,
                ii.updated_date AS loan_date,
                coll.cus_id,
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
                coll.coll_date,
                coll.trans_date,
                SUM(coll.due_amt_track) AS due_amt_track,
                SUM(coll.princ_amt_track) AS princ_amt_track,
                SUM(coll.int_amt_track) AS int_amt_track,
                SUM(coll.penalty_track) AS penalty_track,
                SUM(coll.coll_charge_track) AS coll_charge_track,
                SUM(coll.total_paid_track) AS total_paid_track,
                req.cus_status,
                cls.closed_sts,
                cls.consider_level

            FROM collection coll
            JOIN acknowlegement_customer_profile cp ON coll.req_id = cp.req_id
            JOIN in_issue ii ON coll.req_id = ii.req_id
            JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
            JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
            JOIN acknowlegement_loan_calculation lc ON coll.req_id = lc.req_id
            JOIN request_creation req ON coll.req_id = req.req_id
            JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
             JOIN user u ON coll.insert_login_id = u.user_id
            LEFT JOIN agent_creation ac ON req.agent_id = ac.ag_id
            LEFT JOIN closed_status cls ON req.req_id = cls.req_id

            WHERE req.cus_status >= 14 
            AND $where
            AND cp.area_confirm_subarea IN ($sub_area_list) ";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {
        $query .= " and (ii.loan_id LIKE '%" . $_POST['search'] . "%'
                    OR ii.updated_date LIKE '%" . $_POST['search'] . "%'
                    OR coll.cus_id LIKE '%" . $_POST['search'] . "%'
                    OR coll.cus_name LIKE '%" . $_POST['search'] . "%'
                    OR al.area_name LIKE '%" . $_POST['search'] . "%'
                    OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%'
                    OR u.role LIKE '%" . $_POST['search'] . "%'
                    OR u.fullname LIKE '%" . $_POST['search'] . "%'
                    OR coll.coll_date LIKE '%" . $_POST['search'] . "%') ";
    }
}

$query .= " GROUP BY coll.req_id ";


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

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    $sub_array[] = $sno;
    $sub_array[] = $row['line'];
    $sub_array[] = $row['loan_id'];
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = $role_arr[$row['role']];
    $sub_array[] = $row['fullname'];
    if ($row['trans_date'] != '0000-00-00') {
        $sub_array[] = date('d-m-Y', strtotime($row['trans_date']));
    } else {
        $sub_array[] = date('d-m-Y', strtotime($row['coll_date']));
    }
    $sub_array[] = $coll_arr[$row['coll_mode']];
    if ($row['due_type'] != 'Interest') {
        //to get the principal and interest amt separate in due amt paid
        $response = calculatePrincipalAndInterest(intVal($row['principal_amt_cal']) / $row['due_period'], intVal($row['int_amt_cal']) / $row['due_period'], intVal($row['due_amt_track']));

        $sub_array[] = moneyFormatIndia(intVal($row['due_amt_track']));
        $sub_array[] = moneyFormatIndia(intVal($response['principal_paid']));
        $rounderd_int = intVal($row['due_amt_track']) - $response['principal_paid'];
        $sub_array[] = moneyFormatIndia(intVal($rounderd_int));
    } else {
        //else if its interest loan we can empty due amt coz it will not be paid on that loan, direclty show princ and int
        $sub_array[] = '';
        $sub_array[] = moneyFormatIndia(intval($row['princ_amt_track']));
        $sub_array[] = moneyFormatIndia(intval($row['int_amt_track']));
    }
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
            } else
                    if ($rclosed == '2') {
                $sub_array[] = 'Waiting List';
            } else
                    if ($rclosed == '3') {
                $sub_array[] = 'Block List';
            }
        } else {
            $sub_array[] = $statusObj[$row['cus_status']];
        }
    } else {
        $sub_array[] = 'Present';
        $sub_array[] = $statusObj[$row['cus_status']];
    }

    $data[]      = $sub_array;
    $sno = $sno + 1;
}
function count_all_data($connect)
{
    $query = $connect->query("SELECT COUNT(subquery.coll_id) AS count_result FROM ( SELECT coll.coll_id FROM collection coll JOIN request_creation req ON coll.req_id = req.req_id WHERE req.cus_status >= 14 GROUP BY coll.req_id ) AS subquery ");
    $statement = $query->fetch();
    return intVal($statement['count_result']);
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
    $isNegative = false;
    if ($num < 0) {
        $isNegative = true;
        $num = abs($num);
    }

    $explrestunits = "";
    if (strlen((string)$num) > 3) {
        $lastthree = substr((string)$num, -3);
        $restunits = substr((string)$num, 0, -3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        foreach ($expunit as $index => $value) {
            if ($index == 0) {
                $explrestunits .= (int)$value . ",";
            } else {
                $explrestunits .= $value . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }

    $thecash = $isNegative ? "-" . $thecash : $thecash;
    $thecash = $thecash == 0 ? "" : $thecash;
    return $thecash;
}

function calculatePrincipalAndInterest($principal,  $interest,  $paidAmount): array
{
    $principal_paid = 0;
    $interest_paid = 0;

    while ($paidAmount > 0) {
        if ($paidAmount >= $principal) {
            $principal_paid += $principal;
            $paidAmount -= $principal;
        } else {
            $principal_paid += $paidAmount;
            break;
        }

        if ($paidAmount >= $interest) {
            $interest_paid += $interest;
            $paidAmount -= $interest;
        } else {
            $interest_paid += $paidAmount;
            break;
        }
    }

    return [
        'principal_paid' => (int) $principal_paid,
        'interest_paid' => (int) $interest_paid
    ];
}

// Close the database connection
$connect = null;
