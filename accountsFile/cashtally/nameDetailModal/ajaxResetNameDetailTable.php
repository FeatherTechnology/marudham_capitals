<?php
include '../../../ajaxconfig.php';
$opt_for = $_POST['opt_for'];
?>

<table class="table custom-table" id="nameDetailTable">
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>Name</th>
            <th>Area</th>
            <th>Identification</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM name_detail_creation WHERE opt_for = '$opt_for' AND status = 0 ORDER BY name_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td></td>
                    <td><?php if (isset($ct["name"])) {
                            echo $ct["name"];
                        } ?></td>
                    <td><?php if (isset($ct["area"])) {
                            echo $ct["area"];
                        } ?></td>
                    <td><?php if (isset($ct["ident"])) {
                            echo $ct["ident"];
                        } ?></td>
                    <td>
                        <a id="edit_name" value="<?php if (isset($ct["name_id"])) {
                                                        echo $ct["name_id"];
                                                    } ?>"><span class="icon-border_color"></span></a> &nbsp;
                        <a id="delete_name" data-name_id="<?php 
                            echo $ct['name_id'] ?? ''; ?>" data-opt_for="<?php echo $ct['opt_for'] ?? ''; ?>"> <span class="icon-trash-2"></span></a>
                    </td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var nameDetailTable = $('#nameDetailTable').DataTable({
            ...getStateSaveConfig('nameDetailTable'),
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
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Name_Creation_List'); // or any base
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
        initColVisFeatures(nameDetailTable, 'nameDetailTable');
    });
</script>

<?php
// Close the database connection
$connect = null;
?>