<?php
include '../../ajaxconfig.php';

$unpaid_req_ids = $_POST['unpaid_req_ids'] ?? '';
$from_date = $_POST['from_date'] ?? '';
$to_date = $_POST['to_date'] ?? '';

if (empty($unpaid_req_ids)) {
    echo '<tr><td colspan="5" class="text-center">No unpaid records found</td></tr>';
    exit;
}

// Clean req_ids
$req_id_list = array_filter(array_map('intval', explode(',', $unpaid_req_ids)));
if (empty($req_id_list)) {
    echo '<tr><td colspan="5" class="text-center">No valid req_ids</td></tr>';
    exit;
}

$placeholders = str_repeat('?,', count($req_id_list) - 1) . '?';

// ✅ FIXED: Get HIGHEST PRIORITY commitment per req_id + handle collections
$query = "
    SELECT 
        ii.req_id, 
        ii.loan_id, 
        cr.autogen_cus_id, 
        cr.cus_id, 
        cr.customer_name,
        CASE 
            WHEN cs.last_paid_date = 1 THEN '1-10'
            WHEN cs.last_paid_date = 2 THEN '11-15'
            WHEN cs.last_paid_date = 3 THEN '16-20'
            WHEN cs.last_paid_date = 4 THEN '21-25'
            WHEN cs.last_paid_date = 5 THEN '26-30'
            ELSE ''
        END as last_paid_date,
        COALESCE(
            -- Priority 1: HIGHEST priority commitment (fstatus priority: 1>2-7>8)
            CASE 
                WHEN priority_com.fstatus = 1 THEN 
                    CASE priority_com.ftype
                        WHEN 2 THEN 'Mobile - Commitment'
                        WHEN 1 THEN 'Direct - Commitment'
                    END
                WHEN priority_com.fstatus IN (2,3,4,5,6,7) THEN 
                    CASE priority_com.ftype
                        WHEN 2 THEN 'Mobile - Unavailable'
                        WHEN 1 THEN 'Direct - Unavailable'
                    END
                WHEN priority_com.fstatus = 8 THEN 
                    CASE priority_com.ftype
                        WHEN 2 THEN 'Mobile - Paid'
                        WHEN 1 THEN 'Direct - Paid'
                    END
            END,
            -- Priority 2: Collection payment
            CASE WHEN coll.req_id IS NOT NULL THEN 'Paid (Collection)' END,
            -- Priority 3: To Follow (no commitment, no collection)
            'To Follow'
        ) as status
    FROM in_issue ii
    INNER JOIN customer_register cr ON ii.cus_id = cr.cus_id
    JOIN customer_status cs ON ii.req_id = cs.req_id
    -- ✅ LEFT JOIN with highest priority commitment per req_id
    LEFT JOIN (
        SELECT 
            com.req_id,
            com.ftype,
            com.fstatus,
            ROW_NUMBER() OVER (
                PARTITION BY com.req_id 
                ORDER BY 
                    FIELD(com.fstatus, 1, 2,3,4,5,6,7, 8),  -- Priority: 1 > 2-7 > 8
                    com.created_date DESC  -- Newest first if same priority
            ) as rn
        FROM commitment com
        WHERE com.created_date BETWEEN ? AND ?
    ) priority_com ON ii.req_id = priority_com.req_id AND priority_com.rn = 1
    -- ✅ Collection payment check
    LEFT JOIN collection coll ON ii.req_id = coll.req_id 
        AND DATE(coll.coll_date) BETWEEN ? AND ?
        AND coll.due_amt_track > 0
    WHERE ii.req_id IN ($placeholders)
    ORDER BY 
        CASE status 
            WHEN 'Mobile - Commitment' THEN 1
            WHEN 'Direct - Commitment' THEN 2
            WHEN 'Mobile - Unavailable' THEN 3
            WHEN 'Direct - Unavailable' THEN 4
            WHEN 'Mobile - Paid' THEN 5
            WHEN 'Direct - Paid' THEN 5
            WHEN 'Paid (Collection)' THEN 6
            ELSE 7  -- To Follow last
        END,
        cr.customer_name
";

$stmt = $connect->prepare($query);
$params = [$from_date.' 00:00:00', $to_date.' 23:59:59', $from_date, $to_date, ...$req_id_list];
$stmt->execute($params);

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table custom-table" id="unPaiddatatable">
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>Aadhar Number</th>
            <th>Customer ID</th>
            <th>Loan ID</th>
            <th>Customer Name</th>
            <th>Last Paid Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($records)): ?>
            <tr><td colspan="5" class="text-center text-muted">No unpaid records found</td></tr>
        <?php else: ?>
            <?php foreach ($records as $index => $result): ?>
            <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo ($result['cus_id']); ?></td>
                <td><?php echo ($result['autogen_cus_id']); ?></td>
                <td><?php echo ($result['loan_id']); ?></td>
                <td><?php echo ($result['customer_name']); ?></td>
               <td><?php echo ($result['last_paid_date']); ?></td>
               <td><?php echo ($result['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script type="text/javascript">
$(function() {
    var unPaiddatatable = $('#unPaiddatatable').DataTable({
        ...getStateSaveConfig('unPaiddatatable'),
        'processing': true,
        'iDisplayLength': 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "createdRow": function(row, data, dataIndex) {
            $(row).find('td:first').html(dataIndex + 1);
        },
        "drawCallback": function(settings) {
            this.api().column(0).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
            searchFunction('unPaiddatatable');
        },
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                action: function(e, dt, button, config) {
                    var dynamic = curDateJs('Unpaid_Info_Report');
                    config.title = dynamic;
                    config.filename = dynamic;
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                }
            },
            { extend: 'colvis', collectionLayout: 'fixed four-column' }
        ],
    });

    initColVisFeatures(unPaiddatatable, 'unPaiddatatable');
});
</script>
