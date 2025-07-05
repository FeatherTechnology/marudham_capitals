<?php
include('../../ajaxconfig.php');

if(isset($_POST['branch_id'])){
    $branch_id = $_POST['branch_id'];
}

if(isset($_POST['op_date'])){
    $op_date = date('Y-m-d',strtotime($_POST['op_date']));
}

$records = array();

    $qry = $connect->query("SELECT 
        u.fullname,
        u.role,
        c.insert_login_id,
        GROUP_CONCAT(DISTINCT c.branch) AS branches,
        GROUP_CONCAT(DISTINCT lm.line_name) AS line_name,
        SUM(c.total_paid_track) AS total_paid,

        -- Pre balance till yesterday
        (SELECT IFNULL(SUM(total_paid_track),0) FROM collection WHERE branch IN ($branch_id) AND insert_login_id = c.insert_login_id AND coll_mode='1' AND date(created_date) < '$op_date') AS coll_amt_ys,
        (SELECT IFNULL(SUM(rec_amt),0) FROM ct_hand_collection WHERE branch_id IN ($branch_id) AND user_id = c.insert_login_id AND date(created_date) < '$op_date') AS rec_amt_ys,

        -- Overall till today
        (SELECT IFNULL(SUM(total_paid_track),0) FROM collection WHERE branch IN ($branch_id) AND insert_login_id = c.insert_login_id AND coll_mode='1' AND date(created_date) <= '$op_date') AS coll_amt_today,
        (SELECT IFNULL(SUM(rec_amt),0) FROM ct_hand_collection WHERE branch_id IN ($branch_id) AND user_id = c.insert_login_id AND date(created_date) <= '$op_date') AS rec_amt_today,

        -- Branch names
        (SELECT GROUP_CONCAT(branch_name SEPARATOR ', ') FROM branch_creation WHERE FIND_IN_SET(branch_id, GROUP_CONCAT(DISTINCT c.branch))) AS branch_name

    FROM 
        collection c
    JOIN 
        area_line_mapping lm ON c.line = lm.map_id
    JOIN 
        user u ON c.insert_login_id = u.user_id
    WHERE 
        c.branch IN ($branch_id) 
        AND DATE(c.created_date) = '$op_date' 
        AND c.coll_mode = '1' 
        AND u.role != 2
    GROUP BY 
        c.insert_login_id");

    $i=0;
    while($row = $qry->fetch()){

        $records[$i] = [
            'branch_id'      => $row['branches'],
            'user_id'        => $row['insert_login_id'],
            'collected_amt'  => $row['total_paid'],
            'line_name'      => $row['line_name'],
            'user_name'      => $row['fullname'],
            'user_type'      => $row['role'],
            'branch_name'    => $row['branch_name'],

            'pre_bal'        => $row['coll_amt_ys'] - $row['rec_amt_ys'],
            'tot_amt'        => $row['coll_amt_today'] - $row['rec_amt_today'],
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
            for($i=0; $i<sizeof($records); $i++){
        ?>
            <tr>
                <td></td>
                <td><?php if($records[$i]['user_type'] == '1'){echo 'Director';}elseif($records[$i]['user_type'] == '3'){echo 'Staff';}?></td>
                <td><?php echo $records[$i]['user_name'];?></td>
                <td><?php echo $records[$i]['branch_name'];?></td>
                <td><?php echo $records[$i]['line_name'];?></td>
                <td><?php echo moneyFormatIndia($records[$i]['pre_bal']) ;?></td>
                <td><?php echo moneyFormatIndia($records[$i]['collected_amt']);?></td>
                <td><?php echo moneyFormatIndia($records[$i]['tot_amt']);?></td>
                <td>
                    <input type='button' id='collect_btn1' name='collect_btn1' class="btn btn-primary collect_btn" data-id = "<?php echo $records[$i]['branch_id']; ?>" data-value = "<?php echo $records[$i]['user_id']; ?>" data-toggle="modal" data-target=".coll_modal" value='Receive' onclick="collectBtnClick(this)">
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#collectionTable').DataTable({
            "title":"Collection List",
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
function moneyFormatIndia($num) {
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