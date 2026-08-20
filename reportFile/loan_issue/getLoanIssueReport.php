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
        $user_based = " AND ii.insert_login_id = '$userid' ";
    }
}

$where = "1=1";

if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != '' && $_POST['to_date'] != '') {
    $from_date = date('Y-m-d 00:00:00', strtotime($_POST['from_date']));
    $to_date = date('Y-m-d 23:59:59', strtotime($_POST['to_date']));
    $where = " ii.updated_date BETWEEN '$from_date' AND '$to_date'";
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

$where  .= $user_based;

$column = array(
    'ii.updated_date',
    'ii.loan_id',
    'ad.doc_id',
    'ii.cus_id',
    'cr.autogen_cus_id',
    'cp.cus_name',
    'fam.famname',
    'fam.relationship',
    'al.area_name',
    'sal.sub_area_name',
    'alm.line_name',
    'agm.group_name',
    'adfm.duefollowup_name',
    'bc.branch_name',
    'lcc.loan_category_creation_name',
    'lc.sub_category',
    'ac.ag_name',
    'iv.responsible',
    'ii.updated_date',
    'li.payment_type',
    'li.bank_id',
    'li.created_date',
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
    'u.role',
    'u.fullname',
    'cus_type',
    'cp.cus_exist_type',
    'cs.sub_status',
    'ad.doc_sts',
    'us.fullname'
);

$query = "SELECT 
        ii.loan_id,
        ad.doc_id,
        cp.cus_id,
        cr.autogen_cus_id,
        cp.cus_name,
        fam.famname,
        fam.relationship,
        al.area_name,
        sal.sub_area_name,
        agm.group_name,
        alm.line_name,
        adfm.duefollowup_name,
        bc.branch_name,
        lcc.loan_category_creation_name as loan_cat_name,
        lc.sub_category,
        ac.ag_name,
        iv.responsible,
        ii.updated_date as loan_date,
        GROUP_CONCAT(li.payment_type) AS combinedPaymentType,
        li.req_id,
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
        li.relationship as rec_relationship,
        vfi_received_by.famname as received_by,
        vfi_received_by.relationship as rel_name,
        u.fullname AS loan_issue_user_name,
        CASE u.role
            WHEN 1 THEN 'Director'
            WHEN 2 THEN 'Agent'
            WHEN 3 THEN 'Staff'
            ELSE ''
        END AS loan_issue_user_type,
        cp.cus_type,
        cp.cus_exist_type,
        cs.sub_status,
        ad.doc_sts,
        us.fullname AS doc_holder_name

        FROM in_issue ii
        JOIN customer_register cr ON ii.cus_id = cr.cus_id
        LEFT JOIN acknowlegement_customer_profile cp ON ii.req_id = cp.req_id
        LEFT JOIN acknowlegement_documentation ad ON ii.req_id = ad.req_id
        LEFT JOIN acknowlegement_loan_calculation lc ON ii.req_id = lc.req_id
        LEFT JOIN in_verification iv ON ii.req_id = iv.req_id
        LEFT JOIN verification_family_info fam ON cp.guarentor_name = fam.id
        LEFT JOIN area_list_creation al ON cp.area_confirm_area = al.area_id
        LEFT JOIN sub_area_list_creation sal ON cp.area_confirm_subarea = sal.sub_area_id
        LEFT JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = sal.sub_area_id
        LEFT JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id  
        LEFT JOIN branch_creation bc ON agm.branch_id = bc.branch_id
        LEFT JOIN area_line_mapping_sub_area almsa ON almsa.sub_area_id = sal.sub_area_id
        LEFT JOIN area_line_mapping alm ON alm.map_id = almsa.line_map_id 
        LEFT JOIN area_duefollowup_mapping_area adfma ON adfma.area_id = al.area_id
        LEFT JOIN area_duefollowup_mapping adfm ON adfm.map_id = adfma.duefollowup_map_id
        LEFT JOIN request_creation req ON ii.req_id = req.req_id
        LEFT JOIN loan_issue li ON li.req_id = ii.req_id
        LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
        LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id
        LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
        LEFT JOIN document_track dt ON dt.req_id = ii.req_id
        LEFT JOIN user u ON u.user_id = dt.insert_login_id
        LEFT JOIN user us ON us.user_id = dt.update_login_id
        LEFT JOIN verification_family_info vfi_received_by ON li.relationship !='Customer' AND li.cash_guarentor_name = vfi_received_by.relation_aadhar AND li.cus_id = vfi_received_by.cus_id

        WHERE $where AND ii.cus_status >= 14 AND lc.due_type != 'Interest'";

if (isset($_POST['search'])) {
    if ($_POST['search'] != "") {

        $query .= " and (ii.loan_id LIKE '" . $_POST['search'] . "%' 
            OR ad.doc_id LIKE '%" . $_POST['search'] . "%'
            OR ii.cus_id LIKE '%" . $_POST['search'] . "%'
            OR cr.autogen_cus_id LIKE '%" . $_POST['search'] . "%'
            OR cp.cus_name LIKE '%" . $_POST['search'] . "%' 
            OR fam.famname LIKE '%" . $_POST['search'] . "%' 
            OR fam.relationship LIKE '%" . $_POST['search'] . "%' 
            OR al.area_name LIKE '%" . $_POST['search'] . "%' 
            OR sal.sub_area_name LIKE '%" . $_POST['search'] . "%' 
            OR agm.group_name LIKE '%" . $_POST['search'] . "%' 
            OR alm.line_name LIKE '%" . $_POST['search'] . "%' 
            OR adfm.duefollowup_name LIKE '%" . $_POST['search'] . "%' 
            OR bc.branch_name LIKE '%" . $_POST['search'] . "%' 
            OR lcc.loan_category_creation_name LIKE '%" . $_POST['search'] . "%' 
            OR lc.sub_category LIKE '%" . $_POST['search'] . "%' 
            OR ac.ag_name LIKE '%" . $_POST['search'] . "%' 
            OR iv.responsible LIKE '%" . $_POST['search'] . "%' 
            OR cp.cus_type LIKE '%" . $_POST['search'] . "%' 
            OR cp.cus_exist_type LIKE '%" . $_POST['search'] . "%' 
            OR ii.updated_date LIKE '%" . $_POST['search'] . "%') ";
    }
}

$query .= " GROUP BY ii.req_id";

if (isset($_POST['order'])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'];
} else {
    $query .= ' ';
}
$query1 = '';
if (!isset($_POST['download'])) {
    if ($_POST['length'] != -1) {
        $query1 = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
    }
}
$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();
$sno = 1;
$paymentTypeArr = [0 => 'Cash', 1 => 'Cheque', 2 => 'Account Transfer'];

foreach ($result as $row) {
    $combinedPaymentType = explode(',', $row['combinedPaymentType']);

    $combinedtypeStr = '';
    if (empty($row['ag_name'])) {
        // Convert each number to text
        $combinedtype = array_map(function ($pt) use ($paymentTypeArr) {
            return $paymentTypeArr[$pt];
        }, $combinedPaymentType);

        // Join them into a single string "Cash, Account Transfer"
        $combinedtypeStr = implode(', ', $combinedtype);
    }

    $bank_name = '';
    if (in_array('1', $combinedPaymentType) || in_array('2', $combinedPaymentType)) {
        $qry = $connect->query("SELECT bank_id, created_date FROM loan_issue WHERE req_id = '" . $row['req_id'] . "' AND payment_type != 0");
        $qryfetch = $qry->fetch();

        $bank_name = getBankName($qryfetch['bank_id'], $connect);
    }

    if ($row['rec_relationship'] == 'Customer' || in_array('1', $combinedPaymentType) || in_array('2', $combinedPaymentType)) {
        //if loan issued to customer then direclty place customer name from cp table
        $receivedBy = $row['cus_name'];
        $relation_name = 'Customer';
    } else {
        //else place received by and relation name from fam table
        $receivedBy = $row['received_by'];
        $relation_name = $row['rel_name'];
    }

    $data[]      = [
        $sno++,
        $row['loan_id'],
        $row['doc_id'],
        $row['cus_id'],
        $row['autogen_cus_id'],
        $row['cus_name'],
        $row['famname'],
        $row['relationship'],
        $row['area_name'],
        $row['sub_area_name'],
        $row['line_name'],
        $row['group_name'],
        $row['duefollowup_name'],
        $row['branch_name'],
        $row['loan_cat_name'],
        $row['sub_category'],
        $row['ag_name'],
        (!empty($row['ag_name'])) ? (($row['responsible'] == '0') ? 'Yes' : 'No') : '',
        date('d-m-Y', strtotime($row['loan_date'])),
        $combinedtypeStr,
        $bank_name,
        (in_array('1', $combinedPaymentType) || in_array('2', $combinedPaymentType)) ? date('d-m-Y', strtotime($qryfetch['created_date'])) : '',
        moneyFormatIndia($row['loan_amt_cal']),
        moneyFormatIndia($row['principal_amt_cal']),
        moneyFormatIndia($row['int_amt_cal']),
        moneyFormatIndia($row['doc_charge_cal']),
        moneyFormatIndia($row['proc_fee_cal']),
        moneyFormatIndia($row['tot_amt_cal']),
        moneyFormatIndia($row['net_cash_cal']),
        moneyFormatIndia($row['due_amt_cal']),
        $row['due_period'],
        date('d-m-Y', strtotime($row['due_start_from'])),
        date('d-m-Y', strtotime($row['maturity_month'])),
        $receivedBy,
        $relation_name,    
        $row['loan_issue_user_type'],
        $row['loan_issue_user_name'],
        $row['cus_type'],
        $row['cus_exist_type'],
        $row['sub_status'],
        ($row['doc_sts'] == 'YES') ? 'Document Completed' : 'Document Pending',    
        $row['doc_holder_name'] ?? $row['loan_issue_user_name'],
    ];
}

function getBankName($bankid, $connect)
{
    $stmt = $connect->prepare("SELECT bank_name FROM bank_creation WHERE id = ? ");
    $stmt->execute([$bankid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['bank_name'] : '';
}

$output = array(
    'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0, // ✅ safe for both table & download,
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);