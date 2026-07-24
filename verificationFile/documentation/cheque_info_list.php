<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table" id="cheque_table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Holder type </th>
            <th> Holder Name </th>
            <th> Relationship </th>
            <th> Bank Name </th>
            <th> Cheque No </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $chequeInfo = $connect->query("SELECT holder_type, holder_name, cheque_relation, chequebank_name, cheque_count, vfi.famname FROM `cheque_info` ci LEFT JOIN verification_family_info vfi ON ci.holder_relationship_name = vfi.id WHERE ci.req_id = '$req_id' ORDER BY ci.id DESC");

        $i = 1;
        $holderType = ['0' => 'Customer', '1' => 'Guarantor', '2' => 'Family Members'];
        while ($cheque = $chequeInfo->fetch()) {
        ?>
            <tr>
                <td><?php echo $i++; ?></td>

                <td> <?php echo $holderType[$cheque['holder_type']] ?? ''; ?> </td>

                <td> 
                    <?php if(in_array($cheque["holder_type"], [0,1])){
                        echo $cheque["holder_name"];
                    } elseif ($cheque["holder_type"] == '2') {
                        echo $cheque["famname"];
                    } ?>
                </td>
                <td><?php echo $cheque["cheque_relation"]; ?></td>
                <td><?php echo $cheque["chequebank_name"]; ?></td>
                <td><?php echo $cheque["cheque_count"]; ?></td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        $('#cheque_table').DataTable({
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
                searchFunction('cheque_table');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Cheque_info'); // or any base
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
    });
</script>
<?php
// Close the database connection
$connect = null;
?>