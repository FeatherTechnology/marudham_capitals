<?php
session_start();
$user_id = $_SESSION["userid"];
include('../ajaxconfig.php');
include('../moneyFormatIndia.php');

if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$sql = "SELECT base.*, lcc.loan_category_creation_name, cs.closed_sts, cs.consider_level
        FROM (
            SELECT req.req_id, req.prompt_remark, req.cus_status, req.cus_id, ad.doc_id,
                CASE WHEN req.cus_status >= 14 THEN ii.updated_date ELSE req.dor END AS updated_date,
                CASE WHEN req.cus_status >= 14 THEN ii.loan_id ELSE req.req_code END AS code,
                CASE WHEN req.cus_status IN (12,2,6,7) THEN vlc.loan_category
                     WHEN req.cus_status IN (3,13,14,15,16,17,20,21,22,23,24,25) THEN alc.loan_category
                     ELSE req.loan_category END AS loan_category,
                CASE WHEN req.cus_status IN (12,2,6,7) THEN vlc.sub_category
                     WHEN req.cus_status IN (3,13,14,15,16,17,20,21,22,23,24,25) THEN alc.sub_category
                     ELSE req.sub_category END AS sub_category,
                CASE WHEN req.cus_status IN (12,2,6,7) THEN vlc.loan_amt
                     WHEN req.cus_status IN (3,13,14,15,16,17,20,21,22,23,24,25) THEN alc.loan_amt
                     ELSE req.loan_amt END AS loan_amt,
                CASE WHEN req.cus_status IN (12,2,6,7,3,13,14,15,16,17,20,21,22,23,24,25) THEN cp.cus_name
                     ELSE req.cus_name END AS cus_name,
                req.created_date
            FROM request_creation req
            LEFT JOIN customer_profile cp ON req.req_id = cp.req_id
            LEFT JOIN verification_loan_calculation vlc ON req.req_id = vlc.req_id
            LEFT JOIN acknowlegement_loan_calculation alc ON req.req_id = alc.req_id
            LEFT JOIN in_issue ii ON req.req_id = ii.req_id
            LEFT JOIN acknowlegement_documentation ad ON ii.req_id = ad.req_id
            WHERE req.cus_id = :cus_id
        ) base
        LEFT JOIN loan_category_creation lcc ON lcc.loan_category_creation_id = base.loan_category
        LEFT JOIN closed_status cs ON cs.req_id = base.req_id AND cs.cus_id = base.cus_id
        ORDER BY base.created_date DESC";

$stmt = $connect->prepare($sql);
$stmt->execute([':cus_id' => $cus_id]);
$rows = $stmt->fetchAll();

// Precompute collection status for ALL of this customer's issued loans in one query
$collectionStatusMap = getCollectionStatusMap($connect, $cus_id, $user_id);

$closed_status_labels = ['', 'Consider', 'Waiting List', 'Block List'];
$statusMapping = getStatusMapping();
$records = [];

foreach ($rows as $i => $row) {
    $req_id     = $row['req_id'];
    $cus_status = (int) $row['cus_status'];

    $records[$i] = [
        'updated_date'  => date('d-m-Y', strtotime($row['updated_date'])),
        'code'          => $row['code'],
        'doc_id'        => $row['doc_id'],
        'loan_category' => $row['loan_category_creation_name'],
        'sub_category'  => $row['sub_category'],
        'loan_amt'      => $row['loan_amt'],
        'remark'        => $row['prompt_remark'] ?? '',
    ];

    if (isset($statusMapping[$cus_status])) {
        $records[$i]['status']     = $statusMapping[$cus_status]['status'];
        $records[$i]['sub_status'] = $statusMapping[$cus_status]['sub_status'];

        if (in_array($cus_status, [14, 15, 16, 17], true)) {
            $records[$i]['sub_status'] = $collectionStatusMap[$req_id] ?? 'Current';
        }

        if ($cus_status >= 21) {
            $records[$i]['sub_status'] = $closed_status_labels[(int) ($row['closed_sts'] ?? 0)];
        }
    }

    if ($cus_status >= 14 && $cus_status < 21) {
        $records[$i]['doc_status'] = getDocumentStatus($connect, $req_id) === 'pending'
            ? 'Document Pending' : 'Document Completed';
    } elseif ($cus_status >= 21 && $cus_status <= 23) {
        $records[$i]['doc_status'] = $cus_status === 21 ? 'NOC Pending' : 'NOC Completed';
    } elseif ($cus_status === 24) {
        $records[$i]['doc_status'] = 'NOC Handovered';
    } elseif ($cus_status === 25) {
        $records[$i]['doc_status'] = 'Agent Handovered';
    } else {
        $records[$i]['doc_status'] = '';
    }

    $records[$i]['info_action']    = buildInfoActions($cus_id, $req_id, $cus_status);
    $records[$i]['chart_action']   = buildChartActions($cus_id, $req_id, $cus_status);
    $records[$i]['summary_action'] = buildSummaryActions($cus_id, $req_id, $cus_status, $row['cus_name']);
}
?>
<table class="table table-bordered" id="custStatusTable">
    <thead>
        <tr>
            <th rowspan="2">S.No</th>
            <th rowspan="2">Date</th>
            <th rowspan="2">Req ID/Loan ID</th>
            <th rowspan="2">Document ID</th>
            <th rowspan="2">Loan Category</th>
            <th rowspan="2">Sub Category</th>
            <th rowspan="2">Loan Amount</th>
            <th colspan="2">Loan Status</th>
            <th colspan="4">Document Status</th>
        </tr>
        <tr>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Status</th>
            <th>Info</th>
            <th>Chart</th>
            <th>Summary</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $i => $record) { ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo $record['updated_date']; ?></td>
                <td><?php echo $record['code']; ?></td>
                <td><?php echo $record['doc_id']; ?></td>
                <td><?php echo $record['loan_category']; ?></td>
                <td><?php echo $record['sub_category']; ?></td>
                <td><?php echo moneyFormatIndia($record['loan_amt']); ?></td>
                <td><?php echo $record['status'] ?? ''; ?></td>
                <td><?php echo $record['sub_status'] ?? ''; ?></td>
                <td><?php echo $record['doc_status']; ?></td>
                <td><?php echo $record['info_action']; ?></td>
                <td><?php echo $record['chart_action']; ?></td>
                <td><?php echo $record['summary_action']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<input type="hidden" name="docSts" id="docSts">
<div id="printcollection" style="display: none"></div>

<?php
function getStatusMapping()
{
    static $map = null;
    if ($map === null) {
        $map = [
            0  => ['status' => 'Request', 'sub_status' => 'Requested'],
            1  => ['status' => 'Verification', 'sub_status' => 'In Verification'],
            2  => ['status' => 'Approval', 'sub_status' => 'In Approval'],
            3  => ['status' => 'Acknowledgement', 'sub_status' => 'In Acknowledgement'],
            4  => ['status' => 'Request', 'sub_status' => 'Cancelled'],
            5  => ['status' => 'Verification', 'sub_status' => 'Cancelled'],
            6  => ['status' => 'Approval', 'sub_status' => 'Cancelled'],
            7  => ['status' => 'Acknowledgement', 'sub_status' => 'Cancelled'],
            8  => ['status' => 'Request', 'sub_status' => 'Revoked'],
            9  => ['status' => 'Verification', 'sub_status' => 'Revoked'],
            10 => ['status' => 'Verification', 'sub_status' => 'In Verification'],
            11 => ['status' => 'Verification', 'sub_status' => 'In Verification'],
            12 => ['status' => 'Verification', 'sub_status' => 'In Verification'],
            13 => ['status' => 'Loan Issue', 'sub_status' => 'In Issue'],
            14 => ['status' => 'Present', 'sub_status' => ''],
            15 => ['status' => 'Present', 'sub_status' => ''],
            16 => ['status' => 'Present', 'sub_status' => ''],
            17 => ['status' => 'Present', 'sub_status' => ''],
            20 => ['status' => 'Closed', 'sub_status' => 'In Closed'],
            21 => ['status' => 'Closed', 'sub_status' => 'In Closed'],
            22 => ['status' => 'Closed', 'sub_status' => 'NOC Completed'],
            23 => ['status' => 'Closed', 'sub_status' => 'NOC Completed'],
            24 => ['status' => 'Closed', 'sub_status' => 'NOC Completed'],
            25 => ['status' => 'Closed', 'sub_status' => 'NOC Completed'],
        ];
    }
    return $map;
}

function getCollectionStatusMap($connect, $cus_id, $user_id)
{
    $pending_sts = array_map('boolFromPost', explodeOrEmpty($_POST["pending_sts"] ?? ''));
    $od_sts      = array_map('boolFromPost', explodeOrEmpty($_POST["od_sts"] ?? ''));
    $due_nil_sts = array_map('boolFromPost', explodeOrEmpty($_POST["due_nil_sts"] ?? ''));
    $closed_sts  = array_map('boolFromPost', explodeOrEmpty($_POST["closed_sts"] ?? ''));
    $bal_amt     = array_map(
        fn($v) => trim($v) === '' ? 0.0 : (float) trim($v),
        explodeOrEmpty($_POST["bal_amt"] ?? '')
    );

    $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];

    $sql = "SELECT lc.due_start_from, ii.req_id, ii.cus_status
            FROM acknowlegement_loan_calculation lc
            JOIN in_issue ii ON lc.req_id = ii.req_id
            WHERE lc.cus_id_loan = :cus_id AND ii.cus_status >= 14 AND ii.cus_status < 20
            ORDER BY CAST(ii.req_id AS UNSIGNED) ASC";
    $stmt = $connect->prepare($sql);
    $stmt->execute([':cus_id' => $cus_id]);
    $loanRows = $stmt->fetchAll();

    $closedReqIds = array_column(
        array_filter($loanRows, fn($r) => (int) $r['cus_status'] > 20),
        'req_id'
    );
    $closedMap = [];
    if ($closedReqIds) {
        $in = implode(',', array_fill(0, count($closedReqIds), '?'));
        $cstmt = $connect->prepare("SELECT req_id, closed_sts, consider_level FROM closed_status WHERE req_id IN ($in)");
        $cstmt->execute($closedReqIds);
        foreach ($cstmt->fetchAll() as $r) {
            $closedMap[$r['req_id']] = $r;
        }
    }

    $curdate = date('Y-m-d');
    $result = [];

    foreach ($loanRows as $index => $row) {
        $req_id     = $row['req_id'];
        $cus_status = (int) $row['cus_status'];
        $currentBal = $bal_amt[$index] ?? 0;
        $isPending  = $pending_sts[$index] ?? false;
        $isOd       = $od_sts[$index] ?? false;
        $isDueNil   = $due_nil_sts[$index] ?? false;
        $isClosed   = $closed_sts[$index] ?? false;

        $status = 'Current';

        if (date('Y-m-d', strtotime($row['due_start_from'])) > $curdate && $currentBal != 0) {
            $status = statusByCusStatus($cus_status, 'Current');
        } elseif ($cus_status <= 20) {
            if ($isPending && !$isOd) {
                $status = statusByCusStatus($cus_status, 'Pending');
            } elseif ($isOd && !$isDueNil) {
                $status = statusByCusStatus($cus_status, 'OD');
            } elseif ($isDueNil) {
                $status = statusByCusStatus($cus_status, 'Due Nil');
            } elseif (!$isPending) {
                $status = statusByCusStatus($cus_status, $isClosed ? 'Closed' : 'Current');
            }
        } else {
            $c = $closedMap[$req_id] ?? null;
            if ($c) {
                $status = match ((string) $c['closed_sts']) {
                    '1'     => 'Consider - ' . ($consider_lvl_arr[(int) $c['consider_level']] ?? ''),
                    '2'     => 'Waiting List',
                    '3'     => 'Block List',
                    default => $status,
                };
            }
        }

        $result[$req_id] = $status;
    }

    return $result;
}

function statusByCusStatus($cus_status, $default)
{
    return match ((string) $cus_status) {
        '15'    => 'Error',
        '16'    => 'Legal',
        default => $default,
    };
}

function explodeOrEmpty($val)
{
    return $val !== '' ? explode(',', $val) : [];
}

function boolFromPost($value): bool
{
    return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes'], true);
}

function getDocumentStatus($connect, $req_id)
{
    $response1 = 'completed';
    $response2 = 'completed';

    $response3 = 'completed';
    $sts_qry = $connect->prepare("SELECT doc_sts FROM acknowlegement_documentation WHERE req_id = :req_id");
    $sts_qry->execute([':req_id' => $req_id]);
    foreach ($sts_qry->fetchAll() as $sts_row) {
        if ($sts_row['doc_sts'] == 'NO') {
            $response3 = 'pending';
        }
    }

    $response4 = 'completed';

    return ($response1 === 'completed' && $response2 === 'completed'
        && $response3 === 'completed' && $response4 === 'completed')
        ? 'completed' : 'pending';
}

function buildInfoActions($cus_id, $req_id, $cus_status)
{
    $cus_id = htmlspecialchars((string) $cus_id, ENT_QUOTES);
    $req_id = htmlspecialchars((string) $req_id, ENT_QUOTES);

    $html = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";
    $html .= "<a class='personal-info' data-toggle='modal' data-target='#personalInfoModal' data-cusid='{$cus_id}'><span>Personal Info</span></a>";

    if ($cus_status >= 2 && !in_array($cus_status, [4, 5, 8, 9], true)) {
        $html .= "<a class='cust-profile' data-reqid='{$req_id}' data-cusid='{$cus_id}'><span>Customer Profile</span></a>
            <a class='documentation' data-reqid='{$req_id}' data-cusid='{$cus_id}'><span>Documentation</span></a>
            <a class='loan-calc' data-reqid='{$req_id}' data-cusid='{$cus_id}'><span>Loan Calculation</span></a>";
    }

    $html .= "</div></div>";
    return $html;
}

function buildChartActions($cus_id, $req_id, $cus_status)
{
    $cus_id = htmlspecialchars((string) $cus_id, ENT_QUOTES);
    $req_id = htmlspecialchars((string) $req_id, ENT_QUOTES);

    $html = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

    if ($cus_status >= 14) {
        $html .= "<a><span data-toggle='modal' data-target='.DueChart' class='due-chart' value='{$req_id}' data-cusid='{$cus_id}'> Due Chart</span></a>
            <a><span data-toggle='modal' data-target='.PenaltyChart' class='penalty-chart' value='{$req_id}' data-cusid='{$cus_id}'> Penalty Chart</span></a>
            <a><span data-toggle='modal' data-target='.collectionChargeChart' class='coll-charge-chart' value='{$req_id}' data-cusid='{$cus_id}'> Fine Chart</span></a>";
    }
    if ($cus_status >= 14 && $cus_status <= 20) {
        $html .= "<a><span data-toggle='modal' data-target='#commitmentChart' class='commitment-chart' data-reqid='{$req_id}' data-cusid='{$cus_id}'> Commitment Chart </span></a>";
    }

    $html .= "</div></div>";
    return $html;
}

function buildSummaryActions($cus_id, $req_id, $cus_status, $cus_name)
{
    $cus_id   = htmlspecialchars((string) $cus_id, ENT_QUOTES);
    $req_id   = htmlspecialchars((string) $req_id, ENT_QUOTES);
    $cus_name = htmlspecialchars((string) $cus_name, ENT_QUOTES);

    $html = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

    if ($cus_status > 20) {
        $html .= "<a><span data-reqid='{$req_id}' data-cusid='{$cus_id}' data-toggle='modal' data-target='.loansummarychart' class='loansummary-chart' >Loan Summary</span></a>";
        $html .= "<a><span class='noc-summary' data-reqid='{$req_id}' data-cusid='{$cus_id}' data-cusname='{$cus_name}' data-toggle='modal' data-target='.noc-summary-modal' >NOC Summary</span></a>";
    }

    $html .= "</div></div>";
    return $html;
}
?>
<style>
    .dropdown-content {
        color: black;
    }

    .img-show {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
        background-color: white;
    }
</style>
<script>
    //datatable initialization and other link click
    var table = $('#custStatusTable').DataTable();
    table.destroy();
    // Declare table variable to store the DataTable instance
    var custStatusTable = $('#custStatusTable').DataTable({
        ...getStateSaveConfig('custStatusTable'),
        'processing': true,
        'iDisplayLength': 10,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                action: function(e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Customer_Status_List'); // or any base
                    config.title = dynamic; // for versions that use title as filename
                    config.filename = dynamic; // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
    });

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(custStatusTable, 'custStatusTable');

    // 1️⃣ Run once immediately after initialization
    customerStatusOnClickEvents();

    // 2️⃣ Also re-run whenever DataTable redraws (pagination, search, etc.)
    $('#custStatusTable').on('draw.dt', function() {
        customerStatusOnClickEvents();
    });
</script>

<?php
// Close the database connection
$connect = null;
?>