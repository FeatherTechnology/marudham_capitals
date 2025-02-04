<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="property_data_table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Property Type </th>
            <th> Property Measurement </th>
            <th> Property Value </th>
            <th> Property Holder </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $prptyInfo = $connect->query("SELECT * FROM `verification_property_info` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($property = $prptyInfo->fetch()) {
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>
                <td> <?php echo $property['property_type']; ?></td>
                <td> <?php echo $property['property_measurement']; ?></td>
                <td> <?php echo $property['property_value']; ?></td>
                <td> <?php echo $property['property_holder']; ?></td>
            </tr>
        <?php  } ?>

    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#property_data_table').DataTable({
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
                searchFunction('property_data_table');
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