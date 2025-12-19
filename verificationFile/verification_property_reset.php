<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table " id="Property_Table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Property Type </th>
            <!-- <th> Property Measurement </th> -->
            <th> Property Value </th>
            <th> Property Holder </th>
            <th> ACTION </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cus_id =$_POST['cus_id'];
        $prptyInfo = $connect->query("SELECT * FROM `verification_property_info` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($property = $prptyInfo->fetch()) {
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $property["property_type"]; ?></td>
                <td><?php echo $property["property_value"]; ?></td>
                <td><?php echo $property["property_holder"]; ?></td>
                <td>
                    <a id="verification_property_edit" value="<?php echo $property['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="verification_property_delete" value="<?php echo $property['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>

        <?php $i = $i + 1;
        }     ?>

    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var Property_Table = $('#Property_Table').DataTable({
            ...getStateSaveConfig('Property_Table'),
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
                searchFunction('Property_Table');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Property_info'); // or any base
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
        initColVisFeatures(Property_Table, 'Property_Table');
    });
</script>
<?php
// Close the database connection
$connect = null;
?>