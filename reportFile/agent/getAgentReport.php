<?php
include "../../ajaxconfig.php";
include "../../moneyFormatIndia.php";

@session_start();
$user_id = $_SESSION['userid'] ?? 0;

/* -----------------------------------------------------------
   DATE FILTER (SAFE DEFAULT)
----------------------------------------------------------- */

if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
    $fromdate = date('Y-m-d', strtotime($_POST['from_date']));
    $todate   = date('Y-m-d', strtotime($_POST['to_date']));
} else {
    $fromdate = date('Y-m-d');
    $todate   = date('Y-m-d');
}

$fromDateTime = $fromdate . ' 00:00:00';
$toDateTime   = $todate . ' 23:59:59';

$where = " c.created_date BETWEEN '$fromDateTime' AND '$toDateTime'";
/* -----------------------------------------------------------
   USER DETAILS
----------------------------------------------------------- */
$userQry = $connect->query("
    SELECT role, report_access, agentforstaff, ag_id 
    FROM user 
    WHERE user_id = '$user_id'
");
$userRow = $userQry->fetch(PDO::FETCH_ASSOC);

$role            = $userRow['role'];
$report_access   = $userRow['report_access'];
$agentforstaff   = $userRow['agentforstaff'];
$ag_id           = $userRow['ag_id'];

/* -----------------------------------------------------------
   GET AGENT USER IDS
----------------------------------------------------------- */
$ids = [];
if (!empty($agentforstaff)) {
    $ag_userid_qry = $connect->query("
        SELECT user_id FROM user 
        WHERE FIND_IN_SET(ag_id, '$agentforstaff')
    ");
    while ($row = $ag_userid_qry->fetch(PDO::FETCH_ASSOC)) {
        $ids[] = $row['user_id'];
    }
}
$ag_user_id = !empty($ids) ? implode(',', $ids) : '0';

/* -----------------------------------------------------------
   ROLE BASED CONDITIONS
----------------------------------------------------------- */
$agentCollection = "";
$agentCondition  = "";
$agentCreditDebit = "";

if ($report_access == '1') {

    if ($role != 2) { // Director / Staff
        $agentCondition   = " AND FIND_IN_SET(c.agent_id, '$agentforstaff')";
        $agentCreditDebit = " AND c.insert_login_id = '$user_id'";
    } else { // Agent
        $ag_user_id       = $user_id;
        $agentCondition   = " AND FIND_IN_SET(c.agent_id, '$ag_id')";
        $agentCreditDebit = " AND c.ag_id = '$ag_id'";
    }

    $agentCollection = " AND FIND_IN_SET(c.insert_login_id, '$ag_user_id')";
} 

    $opclbal = getOpClBal($connect, $fromDateTime, $toDateTime, $agentCollection, $agentCondition, $agentCreditDebit);

/* -----------------------------------------------------------
   DATATABLES COLUMN MAP
----------------------------------------------------------- */
$column = [
    0 => 'tdate',
    1 => 'ag_name',
    2 => 'tdate',
    3 => 'details',
    4 => 'coll_amt',
    5 => 'netcash',
    6 => 'Credit',
    7 => 'Debit'
];

/* -----------------------------------------------------------
   MAIN QUERY
----------------------------------------------------------- */
$query = "
SELECT * FROM (

    SELECT 
        ac.ag_name,
        c.created_date AS tdate,
        c.cus_name AS details,
        c.total_paid_track AS coll_amt,
        0 AS netcash,
        0 AS Credit,
        0 AS Debit
    FROM collection c
    JOIN user u ON c.insert_login_id = u.user_id
    JOIN agent_creation ac ON u.ag_id = ac.ag_id
    WHERE $where AND c.total_paid_track != '' $agentCollection

    UNION ALL

    SELECT 
        ac.ag_name,
        c.created_date AS tdate,
        acp.cus_name AS details,
        0 AS coll_amt,
        (IFNULL(c.cash,0) + IFNULL(c.cheque_value,0) + IFNULL(c.transaction_value,0)) AS netcash,
        0 AS Credit,
        0 AS Debit
    FROM loan_issue c
    JOIN acknowlegement_customer_profile acp ON acp.req_id = c.req_id
    JOIN agent_creation ac ON c.agent_id = ac.ag_id
    WHERE $where $agentCondition

    UNION ALL

    SELECT ac.ag_name, c.created_date AS tdate, 'File Cash' AS details, 0 AS coll_amt, 0 AS netcash, 0 AS Credit, amt AS Debit
    FROM ct_db_hag c
    JOIN agent_creation ac ON c.ag_id = ac.ag_id
    WHERE $where $agentCreditDebit

    UNION ALL

    SELECT ac.ag_name, c.created_date AS tdate, 'File Cash' AS details, 0 AS coll_amt, 0 AS netcash, 0 AS Credit, amt AS Debit
    FROM ct_db_bag c
    JOIN agent_creation ac ON c.ag_id = ac.ag_id
    WHERE $where $agentCreditDebit

    UNION ALL

    SELECT ac.ag_name, c.created_date AS tdate, 'In Cash' AS details, 0 AS coll_amt, 0 AS netcash, amt AS Credit, 0 AS Debit
    FROM ct_cr_hag c
    JOIN agent_creation ac ON c.ag_id = ac.ag_id
    WHERE $where $agentCreditDebit

    UNION ALL

    SELECT ac.ag_name, c.created_date AS tdate, 'In Cash' AS details, 0 AS coll_amt, 0 AS netcash, amt AS Credit, 0 AS Debit
    FROM ct_cr_bag c
    JOIN agent_creation ac ON c.ag_id = ac.ag_id
    WHERE $where $agentCreditDebit
) temp";

/* -----------------------------------------------------------
   SEARCH
----------------------------------------------------------- */
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $query .= " WHERE ag_name LIKE '%$search%' 
                OR details LIKE '%$search%' 
                OR tdate LIKE '%$search%'";
}

/* -----------------------------------------------------------
   ORDER
----------------------------------------------------------- */
if (isset($_POST['order'])) {
    $query .= " ORDER BY " .
        $column[$_POST['order'][0]['column']] . " " .
        $_POST['order'][0]['dir'];
} else {
    $query .= " ORDER BY tdate DESC";
}

/* -----------------------------------------------------------
   PAGINATION
----------------------------------------------------------- */
$limit = "";
if ($_POST['length'] != -1) {
    $limit = " LIMIT ".$_POST['start'].", ".$_POST['length'];
}

/* -----------------------------------------------------------
   EXECUTION
----------------------------------------------------------- */
$countQuery = "SELECT COUNT(*) FROM ($query) x";
$countStmt = $connect->prepare($countQuery);
$countStmt->execute();
$recordsFiltered = $countStmt->fetchColumn();

$stmt = $connect->prepare($query.$limit);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* -----------------------------------------------------------
   DATA FORMAT
----------------------------------------------------------- */
$data = [];
$sno = 1;
foreach ($result as $row) {
    $data[] = [
        $sno++,
        $row['ag_name'],
        date('d-m-Y', strtotime($row['tdate'])),
        $row['details'],
        $row['coll_amt'] ? moneyFormatIndia($row['coll_amt']) : 0,
        $row['netcash'] ? moneyFormatIndia($row['netcash']) : 0,
        $row['Credit']  ? moneyFormatIndia($row['Credit'])  : 0,
        $row['Debit']   ? moneyFormatIndia($row['Debit'])   : 0
    ];
}

/* -----------------------------------------------------------
   OUTPUT
----------------------------------------------------------- */
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $recordsFiltered,
    "recordsFiltered" => $recordsFiltered,
    "opening_balance" => $opclbal['op_bal'],
    "closing_balance" => $opclbal['cl_bal'],
    "data" => $data
]);

/* -----------------------------------------------------------
   FUNCTIONS 
----------------------------------------------------------- */

/* ---------------- OPENING / CLOSING BALANCE ---------------- */

function getOpClBal($connect, $opdate, $cldate, $collCndtn, $issuedCndtn, $agentCrDr) {
    $monthStart = date('Y-m-d', strtotime($opdate));
    $opening = getBal($connect, "c.created_date < '$monthStart'", $collCndtn, $issuedCndtn, $agentCrDr);
    $closing = getBal($connect, "c.created_date <= '$cldate'", $collCndtn, $issuedCndtn, $agentCrDr);
    return ['op_bal'=>$opening, 'cl_bal'=>$closing];
}

function getBal($connect, $dateCond, $collCndtn, $issuedCndtn, $agentCrDr) {

    $qry = $connect->query("
        SELECT 
            IFNULL(SUM(CASE WHEN grp='coll_loan' THEN Credit-Debit END),0) +
            IFNULL(SUM(CASE WHEN grp='ct_tables' THEN Debit-Credit END),0) bal
        FROM (
            SELECT total_paid_track Credit,0 Debit,'coll_loan' grp
            FROM collection c 
            JOIN user u ON c.insert_login_id = u.user_id
            JOIN agent_creation ac ON u.ag_id = ac.ag_id
            WHERE $dateCond $collCndtn

            UNION ALL
            SELECT 0,(IFNULL(cash,0)+IFNULL(cheque_value,0)+IFNULL(transaction_value,0)),'coll_loan'
            FROM loan_issue c 
            JOIN agent_creation ac ON c.agent_id = ac.ag_id
            WHERE $dateCond $issuedCndtn

            UNION ALL
            SELECT 0,amt,'ct_tables' FROM ct_db_hag c WHERE $dateCond $agentCrDr
            UNION ALL
            SELECT amt,0,'ct_tables' FROM ct_cr_hag c WHERE $dateCond $agentCrDr
            UNION ALL
            SELECT 0,amt,'ct_tables' FROM ct_db_bag c WHERE $dateCond $agentCrDr
            UNION ALL
            SELECT amt,0,'ct_tables' FROM ct_cr_bag c WHERE $dateCond $agentCrDr
        ) x
    ");
    return $qry->fetchColumn() ?? 0;
}

$connect = null;