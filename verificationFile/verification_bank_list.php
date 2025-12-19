<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="bank_data_table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Bank Name </th>
            <th> Branch Name </th>
            <th> Account Holder Name </th>
            <th> Account Number </th>
            <th> IFSC Code </th>
            <th> Upload </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $bankInfo = $connect->query("SELECT * FROM `verification_bank_info` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($bank = $bankInfo->fetch()) {
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>
                <td> <?php echo $bank['bank_name']; ?></td>
                <td> <?php echo $bank['branch_name']; ?></td>
                <td> <?php echo $bank['acc_holder_name']; ?></td>
                <td> <?php echo $bank['acc_no']; ?></td>
                <td> <?php echo $bank['ifsc_code']; ?></td>
                <td> <a href="uploads/bankUploads/<?php echo $bank['upload']; ?>" target="_blank" style="color: #4ba39b;"> <?php echo $bank['upload']; ?> </a></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>



<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var bank_data_table = $('#bank_data_table').DataTable({
            ...getStateSaveConfig('bank_data_table'),
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
                searchFunction('bank_data_table');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Bank_info'); // or any base
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
        initColVisFeatures(bank_data_table, 'bank_data_table');
    });
</script>
<?php
// Close the database connection
$connect = null;
?>