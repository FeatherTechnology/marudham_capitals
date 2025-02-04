
<?php
session_start();
include('../../../ajaxconfig.php');

$user_id = $_SESSION['userid'];
$bankqry = $connect->query("SELECT `bank_details` FROM `user` WHERE `user_id`= $user_id");
$bank_id = $bankqry->fetch()['bank_details'];

$qry = $connect->query("SELECT bwed.*,bc.short_name,bc.acc_no from ct_db_cash_withdraw bwed LEFT JOIN bank_creation bc on bwed.from_bank_id = bc.id where bwed.received = 1 and FIND_IN_SET(bwed.from_bank_id,'$bank_id')");
// 0 means recevied or entered in credit bank deposit. not used current date because any time can be cash deposited to bank 

?>


<table class="table custom-table" id='bwdTable'>
    <thead>
        <tr>
            <th width='50'>S.No</th>
            <th>Ref ID</th>
            <th>Transaction ID</th>
            <th>Bank</th>
            <th>Account No</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while($row = $qry->fetch()){
                
        ?>
            <tr>
                <td></td>
                <td><?php echo $row['ref_code']; ?></td>
                <td><?php echo $row['trans_id']; ?></td>
                <td><?php echo $row['short_name'];?></td>
                <td><?php echo $row['acc_no'];?></td>
                <td><?php echo moneyFormatIndia($row['amt']);?></td>
                <td>
                    <input type='button' id='' name='' class="btn btn-primary receive_bwd" data-value = '<?php echo $row['id']; ?>' data-toggle="modal" data-target=".bwd_modal" value='Receive' onclick="receivebwdBtnClick(this)">
                </td>
            </tr>
        <?php
        }
        ?>

    </tbody>
</table>

<script type='text/javascript'>
    $(function() {
        $('#bwdTable').DataTable({
            "title":"Cash Withdrawal List",
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

// Close the database connection
$connect = null;
?>