<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="bank_table_data">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Bank Name </th>
            <!-- <th> Branch Name </th> -->
            <th> Account Holder Name </th>
            <th> Account Number </th>
            <th> Upload </th>
            <!-- <th> IFSC Code </th> -->
            <th> ACTION </th>
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
                <td><?php echo $i; ?></td>
                <td><?php echo $bank["bank_name"]; ?></td>
                <td><?php echo $bank["acc_holder_name"]; ?></td>
                <td><?php echo $bank["acc_no"]; ?></td>
                <td> <a href="uploads/bankUploads/<?php echo $bank['upload']; ?>" target="_blank" style="color: #4ba39b;"> <?php echo $bank['upload']; ?> </a></td>
                <td>
                    <a id="verification_bank_edit" value="<?php echo $bank['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="verification_bank_delete" value="<?php echo $bank['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#bank_table_data').DataTable({
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
                searchFunction('bank_table_data');
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
// Close the database connection
$connect = null;
?>