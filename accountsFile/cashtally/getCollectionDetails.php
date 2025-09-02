<?php
include('../../ajaxconfig.php');

if (isset($_POST['branch_id'])) {
    $branch_id = $_POST['branch_id'];
}

if (isset($_POST['op_date'])) {
    $op_date = date('Y-m-d', strtotime($_POST['op_date']));
}

$records = array();

$qry = $connect->query("
    WITH user_coll AS (
        SELECT
            c.insert_login_id AS user_id,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) = '$op_date' THEN c.branch END) AS branch_ids_today,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) < '$op_date' THEN c.branch END) AS branch_ids_prev,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) = '$op_date' THEN bc.branch_name END) AS branch_name_today,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) < '$op_date' THEN bc.branch_name END) AS branch_name_prev,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) = '$op_date' THEN lm.line_name END) AS line_name_today,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) < '$op_date' THEN lm.line_name END) AS line_name_prev,
             GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) = '$op_date' THEN c.line END) AS line_ids_today,
            GROUP_CONCAT(DISTINCT CASE WHEN DATE(c.coll_date) < '$op_date' THEN c.line END) AS line_ids_prev,
            SUM(CASE WHEN DATE(c.coll_date) < '$op_date' THEN c.total_paid_track ELSE 0 END) AS coll_amt_ys,
            SUM(CASE WHEN DATE(c.coll_date) = '$op_date' THEN c.total_paid_track ELSE 0 END) AS coll_amt_today
        FROM collection c
        JOIN area_line_mapping lm ON lm.map_id = c.line
        JOIN branch_creation bc ON bc.branch_id = c.branch 
        WHERE c.coll_mode = '1'
          AND c.branch IN ($branch_id)
          AND DATE(c.coll_date) <= '$op_date'
        GROUP BY c.insert_login_id
    ),
    user_hand AS (
        SELECT
            hc.user_id,
            SUM(CASE WHEN DATE(hc.created_date) < '$op_date' THEN hc.rec_amt ELSE 0 END) AS rec_amt_ys,
            SUM(CASE WHEN DATE(hc.created_date) = '$op_date' THEN hc.rec_amt ELSE 0 END) AS rec_amt_today
        FROM ct_hand_collection hc
        WHERE hc.branch_id IN ($branch_id)
          AND DATE(hc.created_date) <= '$op_date'
        GROUP BY hc.user_id
    )

    SELECT
        u.user_id AS insert_login_id,
        u.fullname,
        u.role,
CASE 
    WHEN uc.coll_amt_today > 0 AND (IFNULL(uc.coll_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
        THEN CONCAT_WS(',', uc.branch_ids_prev, uc.branch_ids_today)
    WHEN uc.coll_amt_today > 0 
        THEN uc.branch_ids_today
    ELSE uc.branch_ids_prev
END AS branches,

CASE 
    WHEN uc.coll_amt_today > 0 AND (IFNULL(uc.coll_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
        THEN CONCAT_WS(',', uc.line_ids_prev, uc.line_ids_today)
    WHEN uc.coll_amt_today > 0 
        THEN uc.line_ids_today
    ELSE uc.line_ids_prev
END AS line_ids,
CASE 
    WHEN uc.coll_amt_today > 0 AND (IFNULL(uc.coll_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
        THEN CONCAT_WS(',', uc.branch_name_prev, uc.branch_name_today)
    WHEN uc.coll_amt_today > 0 
        THEN uc.branch_name_today
    ELSE uc.branch_name_prev
END AS branch_name,

CASE 
    WHEN uc.coll_amt_today > 0 AND (IFNULL(uc.coll_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
        THEN CONCAT_WS(',', uc.line_name_prev, uc.line_name_today)
    WHEN uc.coll_amt_today > 0 
        THEN uc.line_name_today
    ELSE uc.line_name_prev
END AS line_name,

        IFNULL(uc.coll_amt_ys, 0) AS coll_amt_ys,
        IFNULL(uh.rec_amt_ys, 0) AS rec_amt_ys,
        IFNULL(uc.coll_amt_today, 0) AS coll_amt_today,
        IFNULL(uh.rec_amt_today, 0) AS rec_amt_today,
        (IFNULL(uc.coll_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) AS pre_bal,
        (IFNULL(uc.coll_amt_today, 0) - IFNULL(uh.rec_amt_today, 0)) AS collected_amt
    FROM user u
    LEFT JOIN user_coll uc ON uc.user_id = u.user_id
    LEFT JOIN user_hand uh ON uh.user_id = u.user_id
    WHERE u.user_id NOT IN (1)
      AND (
            (IFNULL(uc.coll_amt_ys, 0) - IFNULL(uh.rec_amt_ys, 0)) > 0 
            OR 
            (IFNULL(uc.coll_amt_today, 0) - IFNULL(uh.rec_amt_today, 0)) > 0
            OR
            (uh.rec_amt_today > 0)
        )
    ORDER BY u.user_id
");

$i = 0;
while ($row = $qry->fetch()) {
    $branches     = implode(',', array_unique(explode(',', $row['branches'])));
    $line_ids     = implode(',', array_unique(explode(',', $row['line_ids'])));
    $branch_names = implode(',', array_unique(explode(',', $row['branch_name'])));
    $line_names   = implode(',', array_unique(explode(',', $row['line_name'])));

   $records[$i] = [
        'branch_id'     => $branches,
        'line_id'       => $line_ids,
        'user_id'       => $row['insert_login_id'],
        'collected_amt' => $row['coll_amt_today'],
        'line_name'     => $line_names,
        'user_name'     => $row['fullname'],
        'user_type'     => $row['role'],
        'branch_name'   => $branch_names,
        'pre_bal'       => $row['pre_bal'],
        'tot_amt'       => $row['pre_bal'] + $row['coll_amt_today'] - $row['rec_amt_today'] 
    ];
    $i++;
}

// Close the database connection
$connect = null;
?>

<table class="table custom-table" id='collectionTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>User Type</th>
            <th>User Name</th>
            <th>Branch</th>
            <th>Line</th>
            <th>Pre Balance</th>
            <th>Today's Collection</th>
            <th>Total Balance</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $pre_bal = 0;
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
                    <input type='button' id='collect_btn1' name='collect_btn1' class="btn btn-primary collect_btn" data-id="<?php echo $records[$i]['branch_id']; ?>" data-line="<?php echo $records[$i]['line_id']; ?>" data-value="<?php echo $records[$i]['user_id']; ?>" data-toggle="modal" data-target=".coll_modal" value='Receive' onclick="collectBtnClick(this)">
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#collectionTable').DataTable({
            "title": "Collection List",
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
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });
    });
</script>

<?php
//Format number in Indian Format
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
?>