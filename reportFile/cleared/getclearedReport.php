<?php
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

$from_date = date('Y-m-d', strtotime($_POST['from_date']));
$to_date = date('Y-m-d', strtotime($_POST['to_date']. ' +1 day'));
$stmt_type = $_POST['stmt_type'] ?? '1';

$column = array(
    'bs.id',
    'bc.bank_name',
    'bs.trans_date',
    'bs.narration',
    'bs.trans_id',
    'bs.credit',
    'bs.debit',
    'bs.balance',
    'bs.clr_status',
    'a.cleared_date',
    'a.cleared_user',
    'a.cleared_screens',
    'lc.loan_category'
);

$condition = ($stmt_type =='1') ? " AND bs.clr_status = '1' " : '';

$based_query = "FROM 
    bank_stmt bs
    LEFT JOIN bank_creation bc ON bs.bank_id = bc.id
    LEFT JOIN (
            SELECT 
                cbsh.bank_stmt_id,

                GROUP_CONCAT(DISTINCT DATE(cbsh.created_date) 
                    ORDER BY DATE(cbsh.created_date) SEPARATOR ', ') AS cleared_date,

                GROUP_CONCAT(DISTINCT u.fullname 
                    ORDER BY u.fullname SEPARATOR ', ') AS cleared_user,

                GROUP_CONCAT(DISTINCT cbsh.screens 
                    ORDER BY cbsh.screens SEPARATOR ', ') AS cleared_screens

            FROM cleared_bank_stmt_history cbsh
            JOIN user u ON cbsh.insert_login_id = u.user_id
            GROUP BY cbsh.bank_stmt_id
        ) a ON bs.id = a.bank_stmt_id
    LEFT JOIN (
            SELECT trans_id, GROUP_CONCAT(DISTINCT cat) AS loan_category
            FROM (

                -- CREDIT TABLES
                SELECT c.trans_id, lcc.loan_category_creation_name AS cat
                FROM collection c 
                JOIN loan_category_creation lcc ON c.loan_category = lcc.loan_category_creation_id 

                UNION ALL

                SELECT trans_id, category FROM ct_cr_boti

                UNION ALL

                SELECT ccrbexc.trans_id, bc.bank_name
                FROM ct_cr_bexchange ccrbexc 
                JOIN bank_creation bc ON ccrbexc.to_bank_id = bc.id

                UNION ALL

                SELECT ccrcd.trans_id, bc.bank_name
                FROM ct_cr_cash_deposit ccrcd 
                JOIN bank_creation bc ON ccrcd.to_bank_id = bc.id 

                UNION ALL

                SELECT ccrbag.trans_id, ac.ag_name
                FROM ct_cr_bag ccrbag 
                JOIN agent_creation ac ON ccrbag.ag_id = ac.ag_id 

                UNION ALL

                SELECT ccrbi.trans_id, ndc.name
                FROM ct_cr_binvest ccrbi 
                JOIN name_detail_creation ndc ON ccrbi.name_id = ndc.name_id 

                UNION ALL

                SELECT ccrbd.trans_id, ndc.name
                FROM ct_cr_bdeposit ccrbd 
                JOIN name_detail_creation ndc ON ccrbd.name_id = ndc.name_id 

                UNION ALL

                SELECT ccrel.trans_id, ndc.name
                FROM ct_cr_bel ccrel 
                JOIN name_detail_creation ndc ON ccrel.name_id = ndc.name_id 

                -- DEBIT TABLES
                UNION ALL

                SELECT cdbbexc.trans_id, bc.bank_name
                FROM ct_db_bexchange cdbbexc 
                JOIN bank_creation bc ON cdbbexc.from_acc_id = bc.id

                UNION ALL

                SELECT cdbcw.trans_id, bc.bank_name
                FROM ct_db_cash_withdraw cdbcw 
                JOIN bank_creation bc ON cdbcw.from_bank_id = bc.id 

                UNION ALL

                SELECT cdbbag.trans_id, ac.ag_name
                FROM ct_db_bag cdbbag 
                JOIN agent_creation ac ON cdbbag.ag_id = ac.ag_id 

                UNION ALL

                SELECT cdbbi.trans_id, ndc.name
                FROM ct_db_binvest cdbbi 
                JOIN name_detail_creation ndc ON cdbbi.name_id = ndc.name_id 

                UNION ALL

                SELECT cdbbd.trans_id, ndc.name
                FROM ct_db_bdeposit cdbbd 
                JOIN name_detail_creation ndc ON cdbbd.name_id = ndc.name_id 

                UNION ALL

                SELECT cdbel.trans_id, ndc.name
                FROM ct_db_bel cdbel 
                JOIN name_detail_creation ndc ON cdbel.name_id = ndc.name_id 

                UNION ALL

                SELECT cdbexf.trans_id, bc.bank_name
                FROM ct_db_exf cdbexf 
                JOIN bank_creation bc ON cdbexf.bank_id = bc.id 

                UNION ALL

                SELECT li.transaction_id AS trans_id, lcc.loan_category_creation_name
                FROM loan_issue li
                JOIN acknowlegement_loan_calculation alc ON li.req_id = alc.req_id 
                JOIN loan_category_creation lcc ON alc.loan_category = lcc.loan_category_creation_id 

                UNION ALL

                SELECT cdbexp.trans_id, ec.category
                FROM ct_db_bexpense cdbexp 
                JOIN expense_category ec ON cdbexp.cat = ec.id 

            ) x
            GROUP BY trans_id
        ) lc ON bs.trans_id = lc.trans_id

WHERE 
    bs.trans_date >= :from_date AND bs.trans_date < :to_date $condition ";  

$search = '';
$params = [];
// Add date params first
$params = [
    ':from_date' => $from_date,
    ':to_date' => $to_date
];

if (!empty($_POST['search'])) {
    $search = "%" . $_POST['search'] . "%";

    $based_query .= " AND (
        bc.bank_name LIKE :search OR
        bs.trans_date LIKE :search OR
        bs.narration LIKE :search OR
        bs.trans_id LIKE :search OR
        bs.credit LIKE :search OR
        bs.debit LIKE :search OR
        a.cleared_user LIKE :search OR
        lc.loan_category LIKE :search OR
        a.cleared_screens LIKE :search
    )";

    // push same search multiple times
    $params[':search'] = $search;
}

$orderby_query = "";

if (isset($_POST['order'])) {
    $col_index = $_POST['order'][0]['column'];
    $dir = $_POST['order'][0]['dir'];

    // whitelist direction
    $dir = ($dir === 'asc') ? 'ASC' : 'DESC';

    if (isset($column[$col_index])) {
        $orderby_query = " ORDER BY " . $column[$col_index] . " " . $dir;
    }
}

$limit_query = "";
if ($_POST['length'] != -1) {
    $limit_query = " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
}

$totalStmt = $connect->query("SELECT COUNT(*) FROM bank_stmt");
$totalStmt->execute();
$recordsTotal = (int) $totalStmt->fetchColumn();

$countStmt = $connect->prepare("SELECT COUNT(*) $based_query");
$countStmt->execute($params);
$recordsFiltered = (int) $countStmt->fetchColumn();

$data_query = "SELECT 
    bc.bank_name,
    bs.trans_date,
    bs.narration,
    bs.trans_id,
    bs.credit,
    bs.debit,
    bs.balance,
    bs.clr_status, 
    a.cleared_date, 
    a.cleared_user, 
    a.cleared_screens,
    lc.loan_category
    $based_query
    $orderby_query
    $limit_query";
$statement = $connect->prepare($data_query);
$statement->execute($params);
$result = $statement->fetchAll();

$data = array();
$sno = 1;
foreach ($result as $row) {
    $clearedDate = '';

    if (!empty($row['cleared_date'])) {
        $dates = explode(',', $row['cleared_date']);
        
        $formattedDates = array_map(function($date) {
            return date('d-m-Y', strtotime($date));
        }, $dates);

        $clearedDate = implode(', ', $formattedDates);
    }

    $status = ($row['clr_status'] == '1') 
                ? 'Cleared' 
                : (($clearedDate == '') ? 'UnCleared' : 'Partial Cleared');

    $sub_array   = array();
    $sub_array[] = $sno++;
    $sub_array[] = $row['bank_name'];
    $sub_array[] = date('d-m-Y H:i', strtotime($row['trans_date']));
    $sub_array[] = $row['narration'] ?? '';
    $sub_array[] = $row['trans_id'] ?? '';
    $sub_array[] = moneyFormatIndia($row['credit'] ?? '');
    $sub_array[] = moneyFormatIndia($row['debit'] ?? '');
    $sub_array[] = moneyFormatIndia($row['balance'] ?? '');
    $sub_array[] = $status;
    $sub_array[] = $clearedDate;
    $sub_array[] = $row['cleared_user'] ?? '';
    $sub_array[] = $row['cleared_screens'] ?? '';
    $sub_array[] = $row['loan_category'] ?? '';

    $data[]      = $sub_array;
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
