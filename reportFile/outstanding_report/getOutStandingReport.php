<?php
include '../../ajaxconfig.php';

$monthVal      = $_POST['monthVal'] ?? '';
$branch_id     = $_POST['branch'] ?? '';
$loan_category = $_POST['loan_category'] ?? []; // Array from multi-select

$monthStart   = date('Y-m-01', strtotime($monthVal));
$monthEnd     = date('Y-m-t', strtotime($monthVal));
$prevMonthEnd = date('Y-m-d', strtotime($monthStart . ' -1 day'));

// 1. Fetch and map all loan category names for quick lookup mapping
$loanCatsQry = $connect->query("
    SELECT loan_category_creation_id, loan_category_creation_name 
    FROM loan_category_creation
")->fetchAll(PDO::FETCH_ASSOC);

$catNames = [];
foreach ($loanCatsQry as $cat) {
    $catNames[$cat['loan_category_creation_id']] = $cat['loan_category_creation_name'];
}

// Determine if we are filtering by specific categories
$hasCategoryFilter = !empty($loan_category) && is_array($loan_category);

// Setup the base category SQL snippet
$catFilter = "";
if ($hasCategoryFilter) {
    $sanitized_cats = array_map(function ($val) use ($connect) {
        return $connect->quote($val);
    }, $loan_category);
    $catFilter = " AND alc.loan_category IN (" . implode(',', $sanitized_cats) . ") ";
}

// Define target categories to process loop entries
// If no category selected, we use a dummy index [0] representing a global branch summary
$loopCategories = $hasCategoryFilter ? $loan_category : [0];

/********************************************
 * OUTSTANDING FUNCTION (Adjusted for Category Routing)
 ********************************************/
function getOutstanding($connect, $branch_id, $to_date, $target_cat, $is_filtered)
{
    $filter = "";
    if ($is_filtered) {
        $filter = " AND alc.loan_category = '$target_cat' ";
    }

    // Step 1: Fetch active req_ids
    $qry = $connect->query("
        SELECT DISTINCT li.req_id
        FROM loan_issue li
        LEFT JOIN acknowlegement_customer_profile ack ON li.req_id = ack.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        LEFT JOIN closing_customer cc ON li.req_id = cc.req_id
        LEFT JOIN acknowlegement_loan_calculation alc ON li.req_id = alc.req_id
        WHERE agm.branch_id = '$branch_id'
        AND DATE(li.created_date) <= '$to_date'
        AND (cc.req_id IS NULL OR DATE(cc.closing_date) > '$to_date')
        $filter
    ");

    $req_ids = [];
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $req_ids[] = $row['req_id'];
    }

    // $req_count = count($req_ids); 
    if (empty($req_ids)) {
        return ['amount' => 0, 'count' => 0];
    }

    $req_id_list = implode(',', $req_ids);

    // Step 2: Sum Balances
    $qry = $connect->query("
        SELECT
            alc.req_id, alc.due_type, alc.tot_amt_cal, alc.principal_amt_cal,
            IFNULL(c.due_amt_track, 0) AS due_amt_track,
            IFNULL(c.princ_amt_track, 0) AS princ_amt_track,ii.loan_id
        FROM acknowlegement_loan_calculation alc
        LEFT JOIN (
            SELECT req_id, SUM(due_amt_track) AS due_amt_track, SUM(princ_amt_track) AS princ_amt_track
            FROM collection
            WHERE DATE(coll_date) <= '$to_date'
            GROUP BY req_id
        ) c ON c.req_id = alc.req_id
        JOIN in_issue ii  ON ii.req_id = alc.req_id
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
 * COLLECT RAW METRICS (Grouped cleanly)
 ********************************************/
$data = [];

// Initialize variables for the grand summary row
$tot_pre_os_amt = 0;
$tot_pre_os_po = 0;
$tot_coll_amt = 0;
$tot_end_po = 0;
$tot_cash_amt = 0;
$tot_profit = 0;
$tot_doc = 0;
$tot_grand_amt = 0;
$tot_issue_po = 0;
$tot_curr_os_amt = 0;
$tot_curr_os_po = 0;

foreach ($loopCategories as $cat) {

    // Set dynamic single category filters where applicable
    $singleFilter = $hasCategoryFilter ? " AND alc.loan_category = '$cat' " : "";
    $rowLabel = $hasCategoryFilter ? ($catNames[$cat] ?? $cat) : 'TOTAL';

    // 1. Outstanding Metrics
    $pre = getOutstanding($connect, $branch_id, $prevMonthEnd, $cat, $hasCategoryFilter);
    $current = getOutstanding($connect, $branch_id, $monthEnd, $cat, $hasCategoryFilter);

    // 2. Collection Query
    $collQry = $connect->query("
        SELECT IFNULL(SUM(c.due_amt_track),0)
        FROM collection c
        LEFT JOIN acknowlegement_customer_profile ack ON c.req_id = ack.req_id
        LEFT JOIN acknowlegement_loan_calculation alc ON c.req_id = alc.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        WHERE agm.branch_id = '$branch_id'
        AND DATE(c.coll_date) BETWEEN '$monthStart' AND '$monthEnd'
        $singleFilter
    ");
    $collection_amount = $collQry->fetchColumn() ?: 0;

    // 3. Issues Query
    $issueQry = $connect->query("
        SELECT
            IFNULL(SUM(li.net_cash),0) AS cash_amt,
            IFNULL(SUM(alc.int_amt_cal),0) AS profit_amt,
            IFNULL(SUM(alc.doc_charge_cal),0) AS doc_amt,
            COUNT(li.req_id) AS issue_count
        FROM loan_issue li
        LEFT JOIN acknowlegement_customer_profile ack ON li.req_id = ack.req_id
        LEFT JOIN acknowlegement_loan_calculation alc ON li.req_id = alc.req_id
        JOIN area_group_mapping_sub_area agmsa ON agmsa.sub_area_id = ack.area_confirm_subarea
        JOIN area_group_mapping agm ON agm.map_id = agmsa.group_map_id
        WHERE agm.branch_id = '$branch_id'
        AND DATE(li.created_date) BETWEEN '$monthStart' AND '$monthEnd'
        $singleFilter
    ");
    $issue = $issueQry->fetch(PDO::FETCH_ASSOC);

    $cash_amt   = $issue['cash_amt'] ?? 0;
    $profit_amt = $issue['profit_amt'] ?? 0;
    $doc_amt    = $issue['doc_amt'] ?? 0;
    $issue_count = $issue['issue_count'] ?? 0;
    $row_total  = $cash_amt + $profit_amt + $doc_amt;

    // 4. Closed Accounts (End PO)
    $endQry = $connect->query("
    SELECT COUNT(DISTINCT c1.req_id)
    FROM collection c1
    LEFT JOIN acknowlegement_customer_profile ack
        ON c1.req_id = ack.req_id
     LEFT JOIN acknowlegement_loan_calculation alc ON c1.req_id = alc.req_id
    JOIN area_group_mapping_sub_area agmsa
        ON agmsa.sub_area_id = ack.area_confirm_subarea
    JOIN area_group_mapping agm
        ON agm.map_id = agmsa.group_map_id
    WHERE agm.branch_id = '$branch_id'
    AND DATE(c1.coll_date) BETWEEN '$monthStart' AND '$monthEnd'
    AND (c1.bal_amt = c1.due_amt_track)
    $singleFilter
");
    $end_po = $endQry->fetchColumn() ?: 0;

    $calculated_curr_po  = $pre['count'] + $issue_count - $end_po;
      if (
        $pre['amount'] == 0 && $pre['count'] == 0 && 
        $collection_amount == 0 && $end_po == 0 && 
        $cash_amt == 0 && $profit_amt == 0 && $doc_amt == 0 && 
        $issue_count == 0 && $current['amount'] == 0 && $calculated_curr_po == 0
    ) {
        // Drop calculations and skip row insertion
        continue;
    }
    // Accumulate for Grand Total Row calculations
    $tot_pre_os_amt  += $pre['amount'];
    $tot_pre_os_po   += $pre['count'];
    $tot_coll_amt    += $collection_amount;
    $tot_end_po      += $end_po;
    $tot_cash_amt    += $cash_amt;
    $tot_profit      += $profit_amt;
    $tot_doc         += $doc_amt;
    $tot_grand_amt   += $row_total;
    $tot_issue_po    += $issue_count;
    $tot_curr_os_amt += $current['amount'];
    $tot_curr_os_po  += $calculated_curr_po;

    // Only add item rows if user actively targeted multiple categories
    if ($hasCategoryFilter) {
        $data[] = [
            'details'           => $rowLabel,
            'pre_os_amount'      => round($pre['amount']),
            'pre_os_po'          => $pre['count'],
            'collection_amount'  => round($collection_amount),
            'end_po'             => $end_po,
            'cash_amount'        => round($cash_amt),
            'profit'             => round($profit_amt),
            'doc'                => round($doc_amt),
            'total_amount'       => round($row_total),
            'issue_po'           => $issue_count,
            'current_os_amount'  => round($current['amount']),
            'current_os_po'      => $calculated_curr_po
        ];
    }
}

// Append the final compiled/summarized row to complete dataset mapping
$data[] = [
    'details'           => 'TOTAL',
    'pre_os_amount'      => round($tot_pre_os_amt),
    'pre_os_po'          => $tot_pre_os_po,
    'collection_amount'  => round($tot_coll_amt),
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
