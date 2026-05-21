<?php
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';

$branch_id = $_POST['branch_id'] ?? '';
$op_date = date('Y-m-d', strtotime($_POST['op_date'])) ?? '';
$to_date = date('Y-m-d', strtotime($op_date . ' +1 day'));

//if date function in MySQL it affect index so using this type. if add time static like '2026-02-16 00:00:00, 2026-02-16 23:59:59' based on datatype it may be incorrect. so below way is right.
$same_date_condtn = "c.coll_date >= '$op_date' AND c.coll_date < '$to_date'";
$lessthan_date_condtn = "c.coll_date < '$op_date'";

$records = array();

$qry = $connect->query("
    WITH user_waiver AS (
        SELECT
            c.insert_login_id AS user_id,
            GROUP_CONCAT(DISTINCT CASE WHEN $same_date_condtn THEN c.branch END) AS branch_ids_today,
            GROUP_CONCAT(DISTINCT CASE WHEN $lessthan_date_condtn THEN c.branch END) AS branch_ids_prev,
            GROUP_CONCAT(DISTINCT CASE WHEN $same_date_condtn THEN c.line END) AS line_ids_today,
            GROUP_CONCAT(DISTINCT CASE WHEN $lessthan_date_condtn THEN c.line END) AS line_ids_prev,
            SUM(CASE WHEN $lessthan_date_condtn THEN c.pre_close_waiver ELSE 0 END) AS waiver_amt_ys,
            SUM(CASE WHEN $same_date_condtn THEN c.pre_close_waiver ELSE 0 END) AS waiver_amt_today
        FROM collection c
        WHERE c.coll_mode = '1'
          AND c.branch IN ($branch_id)
          AND c.coll_date < '$to_date'
          AND c.pre_close_waiver > 0
        GROUP BY c.insert_login_id
    ),
    user_hand AS (
        SELECT
            hw.user_id,
            SUM(CASE WHEN hw.created_date < '$op_date' THEN hw.rec_amt ELSE 0 END) AS rec_amt_ys,
            SUM(CASE WHEN hw.created_date >= '$op_date' AND hw.created_date < '$to_date' THEN hw.rec_amt ELSE 0 END) AS rec_amt_today
        FROM ct_hand_waiver hw
        WHERE hw.branch_id IN ($branch_id)
          AND hw.created_date < '$to_date'
        GROUP BY hw.user_id
    )

    SELECT
        u.user_id AS insert_login_id,
        u.fullname,
        u.role,
        CASE 
            WHEN uw.waiver_amt_today > 0 AND (IFNULL(uw.waiver_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
                THEN CONCAT_WS(',', uw.branch_ids_prev, uw.branch_ids_today)
            WHEN uw.waiver_amt_today > 0 
                THEN uw.branch_ids_today
            ELSE uw.branch_ids_prev
        END AS branch_ids,

        CASE 
            WHEN uw.waiver_amt_today > 0 AND (IFNULL(uw.waiver_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
                THEN CONCAT_WS(',', uw.line_ids_prev, uw.line_ids_today)
            WHEN uw.waiver_amt_today > 0 
                THEN uw.line_ids_today
            ELSE uw.line_ids_prev
        END AS line_ids,

        IFNULL(uw.waiver_amt_ys, 0) AS waiver_amt_ys,
        IFNULL(uh.rec_amt_ys, 0) AS rec_amt_ys,
        IFNULL(uw.waiver_amt_today, 0) AS waiver_amt_today,
        IFNULL(uh.rec_amt_today, 0) AS rec_amt_today,
        (IFNULL(uw.waiver_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) AS pre_bal,
        (IFNULL(uw.waiver_amt_today, 0) - IFNULL(uh.rec_amt_today, 0)) AS collected_amt
        FROM user u
        LEFT JOIN user_waiver uw ON uw.user_id = u.user_id
        LEFT JOIN user_hand uh ON uh.user_id = u.user_id
        WHERE u.user_id NOT IN (1)
        AND (
                (IFNULL(uw.waiver_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
                OR 
                (IFNULL(uw.waiver_amt_today, 0) - IFNULL(uh.rec_amt_today, 0)) > 0
                OR
                (uh.rec_amt_today > 0)
            )
        ORDER BY u.user_id
    ");

$i = 0;

while ($row = $qry->fetch()) {

    $branch_ids = implode(',', array_unique(explode(',', $row['branch_ids'])));
    $line_ids = implode(',', array_unique(explode(',', $row['line_ids'])));

    $branchqry = $connect->query("SELECT branch_name FROM branch_creation WHERE branch_id IN ($branch_ids)");
    $branch = $branchqry->fetchAll(PDO::FETCH_ASSOC);

    $lineqry = $connect->query("SELECT line_name FROM area_line_mapping WHERE map_id IN ($line_ids)");
    $line = $lineqry->fetchAll(PDO::FETCH_ASSOC);

    $branchNames = implode(',', array_unique(array_column($branch, 'branch_name')));
    $lineNames   = implode(',', array_unique(array_column($line, 'line_name')));

    $records[$i] = [
        'branch_id'     => $branch_ids,
        'line_id'       => $line_ids,
        'user_id'       => $row['insert_login_id'],
        'collected_amt' => $row['waiver_amt_today'],
        'line_name'     => $lineNames,
        'user_name'     => $row['fullname'],
        'user_type'     => $row['role'],
        'branch_name'   => $branchNames,
        'pre_bal'       => $row['pre_bal'],
        'tot_amt'       => ($row['pre_bal'] + $row['waiver_amt_today']) - $row['rec_amt_today']
    ];
    $i++;
}

// Close the database connection
$connect = null;
?>

<table class="table custom-table" id='waiverTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <th>Branch</th>
            <th>Region</th>
            <th>Pre Balance</th>
            <th>Today's Waiver</th>
            <th>Total Balance</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i < sizeof($records); $i++) {
        ?>
            <tr>
                <td></td>
                <td><?php if ($records[$i]['user_type'] == '1') {
                        echo 'Director';
                    } elseif ($records[$i]['user_type'] == '3') {
                        echo 'Staff';
                    } ?></td>
                <td><?php echo $records[$i]['user_name']; ?></td>
                <td><?php echo $records[$i]['branch_name']; ?></td>
                <td><?php echo $records[$i]['line_name']; ?></td>
                <td><?php echo moneyFormatIndia($records[$i]['pre_bal']); ?></td>
                <td><?php echo moneyFormatIndia($records[$i]['collected_amt']); ?></td>
                <td><?php echo moneyFormatIndia($records[$i]['tot_amt']); ?></td>
                <td>
                    <input type='button' id='waiver_rec_btn1' name='waiver_rec_btn1' class="btn btn-primary waiver_rec_btn" data-id="<?php echo $records[$i]['branch_id']; ?>" data-line="<?php echo $records[$i]['line_id']; ?>" data-value="<?php echo $records[$i]['user_id']; ?>" data-toggle="modal" data-target=".waiver_modal" value='Receive' onclick="collectWaiverBtnClick(this)">
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        // Declare table variable to store the DataTable instance
        var waiverTable = $('#waiverTable').DataTable({
            ...getStateSaveConfig('waiverTable'),
            "title": "Waiver List",
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function(settings) {
                this.api().column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Cashtally_Waiver_List'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
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
        initColVisFeatures(waiverTable, 'waiverTable');
    });
</script>