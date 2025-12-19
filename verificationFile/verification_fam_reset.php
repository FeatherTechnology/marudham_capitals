<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table " id="famdatatable">
    <thead>
        <tr>
            <th width="25%">S.No</th>
            <th>Name</th>
            <th>Relationship</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
        $famInfo = $connect->query("SELECT * FROM `verification_family_info` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($fam = $famInfo->fetch()) {
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $fam["famname"]; ?></td>
                <td><?php echo $fam["relationship"]; ?></td>
                <td>
                    <a id="verification_fam_edit" value="<?php echo $fam['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="verification_fam_delete" value="<?php echo $fam['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>
            </tr>
        <?php $i = $i + 1;
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var famdatatable = $('#famdatatable').DataTable({
            ...getStateSaveConfig('famdatatable'),
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
                searchFunction('famdatatable');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Family_info'); // or any base
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
        initColVisFeatures(famdatatable, 'famdatatable');
    });
</script>
<?php
// Close the database connection
$connect = null;
?>