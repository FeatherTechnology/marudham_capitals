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

        $user_based = " AND cp.area_confirm_subarea IN ($sub_area_list) AND ii.insert_login_id = '$userid' ";
    }
}

$where = "";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d', strtotime($_POST['to_date']));
    $where  = " AND (date(ii.updated_date) >= '" . $from_date . "') AND (date(ii.updated_date) <= '" . $to_date . "') ";
}

$where  .= $user_based;

$column = array(
    'ii.id',
    'ii.loan_id',
    'ad.doc_id',
    'ii.cus_id',
    'cp.cus_name',
    'fam.famname',
    'fam.relationship',
    'al.area_name',
    'sal.sub_area_name',
    'alm.line_name',
    'bc.branch_name',
    'lcc.loan_category_creation_name',
    'lc.sub_category',
    'ac.ag_name',
    'iv.responsible',
    'ii.updated_date',
    'lc.loan_amt_cal',
    'lc.principal_amt_cal',
    'lc.int_amt_cal',
    'lc.doc_charge_cal',
    'lc.proc_fee_cal',
    'lc.tot_amt_cal',
    'lc.net_cash_cal',
    'lc.due_amt_cal',
    'lc.due_period',
    'lc.due_start_from',
    'lc.maturity_month',
    'vfi_received_by.famname',
    'vfi_received_by.relationship',
);

$query = "SELECT 
        ii.loan_id,
        ad.doc_id,
        cp.cus_id,
        cp.cus_name,
        fam.famname,
        fam.relationship,
        al.area_name,
        sal.sub_area_name,
        alm.line_name,
        bc.branch_name,
        lcc.loan_category_creation_name as loan_cat_name,
        lc.sub_category,
        ac.ag_name,
        iv.responsible,
        ii.updated_date as loan_date,
        lc.loan_amt_cal,
        lc.principal_amt_cal,
        lc.int_amt_cal,
        lc.doc_charge_cal,
        lc.proc_fee_cal,
        lc.tot_amt_cal,
        lc.net_cash_cal,
        lc.due_amt_cal,
        lc.due_period,
        lc.due_start_from,
        lc.maturity_month,
        li.payment_type,
        li.relationship as rec_relationship,
        vfi_received_by.famname as received_by,
        vfi_received_by.relationship as rel_name

        FROM in_issue ii
        LEFT JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
        LEFT JOIN acknowlegement_documentation ad ON ii.req_id = ad.req_id
        LEFT JOIN acknowlegement_loan_calculation lc ON ii.req_id = lc.req_id
        LEFT JOIN in_verification iv ON ii.req_id = iv.req_id
        LEFT JOIN verification_family_info fam ON cp.guarentor_name = fam.id
        LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        LEFT JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
        LEFT JOIN area_group_mapping ag ON FIND_IN_SET(sal.sub_area_id, ag.sub_area_id)
        LEFT JOIN branch_creation bc ON ag.branch_id = bc.branch_id
        LEFT JOIN area_line_mapping alm ON FIND_IN_SET(sal.sub_area_id, alm.sub_area_id)
        LEFT JOIN request_creation req ON ii.req_id = req.req_id
        LEFT JOIN loan_issue li ON li.req_id = ii.req_id
        LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
        LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id
        LEFT JOIN verification_family_info vfi_received_by ON li.relationship !='Customer' AND li.cash_guarentor_name = vfi_received_by.relation_aadhar

        WHERE ii.cus_status >= 14 
        $where";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " and (ii.loan_id LIKE '" . $_POST['search'] . "%' 
            OR ad.doc_id LIKE '%" . $_POST['search'] . "%'
            OR ii.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%' 
            OR fam.famname LIKE '%" . $_POST['search'] . "%' 
            OR fam.relationship LIKE '%" . $_POST['search'] . "%' 
            OR al.area_name LIKE '%" . $_POST['search'] . "%' 
            OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%' 
            OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%' 
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%' 
            OR lc.sub_category LIKE '%" . $_POST['search'] . "%' 
            OR ac.ag_name LIKE '%" . $_POST['search'] . "%' 
            OR iv.responsible LIKE '%" . $_POST['search'] . "%' 
            OR ii.updated_date LIKE '%" . $_POST['search'] . "%') ";
    }
}

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}

$query1 = '';
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
    $sub_array[] = $row['loan_id'];
    $sub_array[] = $row['doc_id'];
    $sub_array[] = $row['cus_id'];
    $sub_array[] = $row['cus_name'];
    $sub_array[] = $row['famname'];
    $sub_array[] = $row['relationship'];
    $sub_array[] = $row['area_name'];
    $sub_array[] = $row['sub_area_name'];
    $sub_array[] = $row['line_name'];
    $sub_array[] = $row['branch_name'];
    $sub_array[] = $row['loan_cat_name'];
    $sub_array[] = $row['sub_category'];
    $sub_array[] = $row['ag_name'];
    $sub_array[] = (!empty($row['ag_name'])) ? (($row['responsible'] == '0') ? 'Yes': 'No') : '';
    $sub_array[] = date('d-m-Y', strtotime($row['loan_date']));
    $sub_array[] = moneyFormatIndia($row['loan_amt_cal']);
    $sub_array[] = moneyFormatIndia($row['principal_amt_cal']);
    $sub_array[] = moneyFormatIndia($row['int_amt_cal']);
    $sub_array[] = moneyFormatIndia($row['doc_charge_cal']);
    $sub_array[] = moneyFormatIndia($row['proc_fee_cal']);
    $sub_array[] = moneyFormatIndia($row['tot_amt_cal']);
    $sub_array[] = moneyFormatIndia($row['net_cash_cal']);
    $sub_array[] = moneyFormatIndia($row['due_amt_cal']);
    $sub_array[] = $row['due_period'];
    $sub_array[] = date('d-m-Y', strtotime($row['due_start_from']));
    $sub_array[] = date('d-m-Y', strtotime($row['maturity_month']));

    if ($row['rec_relationship'] == 'Customer' || $row['payment_type'] == '1' || $row['payment_type'] == '2') {
        //if loan issued to customer then direclty place customer name from cp table
        $sub_array[] = $row['cus_name'];
        $sub_array[] = 'Customer';
    } else {
        //else place received by and relation name from fam table
        $sub_array[] = $row['received_by'];
        $sub_array[] = $row['rel_name'];
    }

    $data[]      = $sub_array;
    $sno = $sno + 1;
}


$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

function count_all_data($connect)
{
    $query = "SELECT ii.id from in_issue ii JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id WHERE ii.cus_status >= 14 ";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}


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
