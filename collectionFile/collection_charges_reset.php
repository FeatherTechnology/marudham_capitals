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
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $coll_charges = $connect->query("SELECT * FROM `collection_charges` where req_id = '$req_id' && coll_date !='' order by id desc");

        $i = 0;
        while ($charges = $coll_charges->fetch()) {
        ?>

            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo date('d-m-Y',strtotime($charges["coll_date"])); ?></td>
                <td><?php echo $charges["coll_purpose"]; ?></td>
                <td><?php echo $charges["coll_charge"]; ?></td>
            </tr>

        <?php
        }     
        
        // Close the database connection
        $connect = null;
        ?>
    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var coll_purpose_data = $('#coll_purpose_data').DataTable({
            ...getStateSaveConfig('coll_purpose_data'),
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
                        var dynamic = curDateJs('Fine_table'); // or any base
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
        initColVisFeatures(coll_purpose_data, 'coll_purpose_data');
    });
</script>