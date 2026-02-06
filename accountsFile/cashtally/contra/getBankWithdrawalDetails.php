
<?php
session_start();
include('../../../ajaxconfig.php');
include('../../../moneyFormatIndia.php');

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
        // Declare table variable to store the DataTable instance
        var bwdTable = $('#bwdTable').DataTable({
            ...getStateSaveConfig('bwdTable'),
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
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Contra_Bank_Withdrawal_List'); // or any base
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
        initColVisFeatures(bwdTable, 'bwdTable');
    });
</script>