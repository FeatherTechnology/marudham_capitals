<?php
include '../../ajaxconfig.php';

$monthVal      = $_POST['monthVal'] ?? '';
$agent_id      = $_POST['agent'] ?? ''; // Passed from frontend AJAX post data

$monthStart   = date('Y-m-01', strtotime($monthVal));
$monthEnd     = date('Y-m-t', strtotime($monthVal));
$prevMonthEnd = date('Y-m-d', strtotime($monthStart . ' -1 day'));

// 1. Fetch and map all branch names for quick lookup mapping
$branchQry = $connect->query("
    SELECT branch_id, branch_name 
    FROM branch_creation 
    WHERE status = 0
")->fetchAll(PDO::FETCH_ASSOC);

$branchNames = [];
foreach ($branchQry as $b) {
    $branchNames[$b['branch_id']] = $b['branch_name'];
}

// 2. Find all branches where this agent has active or legacy records to process loop rows
$loopBranchesQry = $connect->query("
    SELECT DISTINCT agm.branch_id
    FROM loan_issue li
    LEFT JOIN acknowlegement_customer_profile ack ON li.req_id = ack.req_id
    JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
    JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
    WHERE li.agent_id = '$agent_id'
")->fetchAll(PDO::FETCH_COLUMN);

// Fallback if agent doesn't have any attached entries yet
$loopBranches = !empty($loopBranchesQry) ? $loopBranchesQry : [];

/********************************************
 * AGENT-SPECIFIC OUTSTANDING FUNCTION
 ********************************************/
function getAgentOutstanding($connect, $branch_id, $agent_id, $to_date)
{
    // Step 1: Fetch active req_ids issued under this agent inside this branch boundary
    $qry = $connect->query("
        SELECT DISTINCT li.req_id
        FROM loan_issue li
        LEFT JOIN acknowlegement_customer_profile ack ON li.req_id = ack.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        LEFT JOIN closing_customer cc ON li.req_id = cc.req_id
        WHERE agm.branch_id = '$branch_id'
        AND li.agent_id = '$agent_id'
        AND DATE(li.created_date) <= '$to_date'
        AND (cc.req_id IS NULL OR DATE(cc.closing_date) > '$to_date')
    ");

    $req_ids = $qry->fetchAll(PDO::FETCH_COLUMN);

    if (empty($req_ids)) {
        return ['amount' => 0, 'count' => 0];
    }

    $req_id_list = implode(',', $req_ids);

    // Step 2: Sum Balances
    $qry = $connect->query("
        SELECT
            alc.req_id, alc.due_type, alc.tot_amt_cal, alc.principal_amt_cal,
            IFNULL(c.due_amt_track, 0) AS due_amt_track,
            IFNULL(c.princ_amt_track, 0) AS princ_amt_track
        FROM acknowlegement_loan_calculation alc
        LEFT JOIN (
            SELECT req_id, SUM(due_amt_track) AS due_amt_track, SUM(princ_amt_track) AS princ_amt_track
            FROM collection
            WHERE DATE(coll_date) <= '$to_date'
            GROUP BY req_id
        ) c ON c.req_id = alc.req_id
        WHERE alc.req_id IN ($req_id_list)
    ");

    $balance_amount = 0;
    $balance_count  = 0;
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $balance = ($row['due_type'] != 'Interest')
            ? intval($row['tot_amt_cal']) - intval($row['due_amt_track'])
            : intval($row['principal_amt_cal']) - intval($row['princ_amt_track']);

        if ($balance > 0) {
            $balance_amount += $balance;
            $balance_count++;
        }
    }

    return ['amount' => $balance_amount, 'count' => $balance_count];
}

/********************************************
 * COLLECT ROW METRICS BY BRANCH FOR AGENT
 ********************************************/
$data = [];

// Initialize summation pools for the Grand Total row
$tot_pre_os_amt = 0;
$tot_pre_os_po = 0;
$tot_coll_amt = 0;
$tot_waiver_amt = 0;
$tot_end_po = 0;
$tot_cash_amt = 0;
$tot_profit = 0;
$tot_doc = 0;
$tot_grand_amt = 0;
$tot_issue_po = 0;
$tot_curr_os_amt = 0;
$tot_curr_os_po = 0;

foreach ($loopBranches as $b_id) {
    
    $rowLabel = $branchNames[$b_id] ?? '';

    // 1. Previous Outstanding metrics specifically for this Agent + Branch combo
    $pre = getAgentOutstanding($connect, $b_id, $agent_id, $prevMonthEnd);

    // 2. Collection Query: Filtered by the Agent ID assigned at Loan Issue
    $collQry = $connect->query("
        SELECT  IFNULL(SUM(c.due_amt_track),0) AS due_amt_track,
        IFNULL(SUM(c.pre_close_waiver),0) AS pre_close_waiver
        FROM collection c
        JOIN loan_issue li ON c.req_id = li.req_id
        LEFT JOIN acknowlegement_customer_profile ack ON c.req_id = ack.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        WHERE agm.branch_id = '$b_id'
        AND li.agent_id = '$agent_id'
        AND DATE(c.coll_date) BETWEEN '$monthStart' AND '$monthEnd'
    ");
    $collection = $collQry->fetch(PDO::FETCH_ASSOC);

    $due_amt_track    = $collection['due_amt_track'] ?? 0;
    $pre_close_waiver = $collection['pre_close_waiver'] ?? 0;
    // 3. Issues Query explicitly filtered by agent_id
    $issueQry = $connect->query("
        SELECT
            IFNULL(SUM(li.net_cash), 0) AS cash_amt,
            IFNULL(SUM(alc.int_amt_cal), 0) AS profit_amt,
            IFNULL(SUM(alc.doc_charge_cal), 0) AS doc_amt,
            COUNT(li.req_id) AS issue_count
        FROM loan_issue li
        LEFT JOIN acknowlegement_customer_profile ack ON li.req_id = ack.req_id
        LEFT JOIN acknowlegement_loan_calculation alc ON li.req_id = alc.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        WHERE agm.branch_id = '$b_id'
        AND li.agent_id = '$agent_id'
        AND DATE(li.created_date) BETWEEN '$monthStart' AND '$monthEnd'
    ");
    $issue = $issueQry->fetch(PDO::FETCH_ASSOC);
    
    $cash_amt    = $issue['cash_amt'] ?? 0;
    $profit_amt  = $issue['profit_amt'] ?? 0;
    $doc_amt     = $issue['doc_amt'] ?? 0;
    $issue_count = $issue['issue_count'] ?? 0;
    $row_total   = $cash_amt + $profit_amt + $doc_amt;

    // 4. Closed Accounts (End PO): Filtered by the Agent ID assigned at Loan Issue
    $endQry = $connect->query("
        SELECT COUNT(DISTINCT c1.req_id)
        FROM collection c1
        JOIN loan_issue li ON c1.req_id = li.req_id
        LEFT JOIN acknowlegement_customer_profile ack ON c1.req_id = ack.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        WHERE agm.branch_id = '$b_id'
        AND li.agent_id = '$agent_id'
        AND DATE(c1.coll_date) BETWEEN '$monthStart' AND '$monthEnd'
        AND ((c1.bal_amt = c1.due_amt_track) OR (c1.bal_amt = c1.pre_close_waiver))
    ");
    $end_po = $endQry->fetchColumn() ?: 0;

    // 5. Apply standard bookkeeping pipeline formulas
    $calculated_curr_amt = $pre['amount'] + $row_total - $due_amt_track - $pre_close_waiver;
    $calculated_curr_po  = $pre['count'] + $issue_count - $end_po;

    // --- NEW MODIFICATION: CHECK IF ALL VALUES ARE ZERO ---
    if (
        $pre['amount'] == 0 && $pre['count'] == 0 &&
        $due_amt_track == 0 && $pre_close_waiver == 0 && $end_po == 0 &&
        $cash_amt == 0 && $profit_amt == 0 && $doc_amt == 0 &&
        $issue_count == 0 && $calculated_curr_amt == 0 && $calculated_curr_po == 0
    ) {
        // Skip adding this branch to the report entirely
        continue;
    }
    // -----------------------------------------------------

    // Accumulate metrics for Grand Total calculations
    $tot_pre_os_amt  += $pre['amount'];
    $tot_pre_os_po   += $pre['count'];
    $tot_coll_amt    += $due_amt_track;
    $tot_waiver_amt  += $pre_close_waiver;
    $tot_end_po      += $end_po;
    $tot_cash_amt    += $cash_amt;
    $tot_profit      += $profit_amt;
    $tot_doc         += $doc_amt;
    $tot_grand_amt   += $row_total;
    $tot_issue_po    += $issue_count;
    $tot_curr_os_amt += $calculated_curr_amt;
    $tot_curr_os_po  += $calculated_curr_po;

    $data[] = [
        'details'            => $rowLabel,
        'pre_os_amount'      => round($pre['amount']),
        'pre_os_po'          => $pre['count'],
        'collection_amount'  => round($due_amt_track),
        'waiver_amount'      => round($pre_close_waiver),
        'end_po'             => $end_po,
        'cash_amount'        => round($cash_amt),
        'profit'             => round($profit_amt),
        'doc'                => round($doc_amt),
        'total_amount'       => round($row_total),
        'issue_po'           => $issue_count,
        'current_os_amount'  => round($calculated_curr_amt),
        'current_os_po'      => $calculated_curr_po
    ];
}

// Append compiled Grand Total row
$data[] = [
    'details'            => 'TOTAL',
    'pre_os_amount'      => round($tot_pre_os_amt),
    'pre_os_po'          => $tot_pre_os_po,
    'collection_amount'  => round($tot_coll_amt),
    'waiver_amount'      => round($tot_waiver_amt),
    'end_po'             => $tot_end_po,
    'cash_amount'        => round($tot_cash_amt),
    'profit'             => round($tot_profit),
    'doc'                => round($tot_doc),
    'total_amount'       => round($tot_grand_amt),
    'issue_po'           => $tot_issue_po,
    'current_os_amount'  => round($tot_curr_os_amt),
    'current_os_po'      => $tot_curr_os_po
];

echo json_encode([
    'data' => $data
]);
