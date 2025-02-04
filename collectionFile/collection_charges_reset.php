<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="coll_purpose_data">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Date </th>
            <th> Purpose </th>
            <th> Amount </th>
            <!-- <th> ACTION </th> -->
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $coll_charges = $connect->query("SELECT * FROM `collection_charges` where req_id = '$req_id' && coll_date !='' order by id desc");

        $i = 1;
        while ($charges = $coll_charges->fetch()) {
        ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo date('d-m-Y',strtotime($charges["coll_date"])); ?></td>
                <td><?php echo $charges["coll_purpose"]; ?></td>
                <td><?php echo $charges["coll_charge"]; ?></td>
                <!-- <td>
                    <a id="verification_bank_edit" value="<?php echo $charges['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="verification_bank_delete" value="<?php echo $charges['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td> -->
            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#coll_purpose_data').DataTable({
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