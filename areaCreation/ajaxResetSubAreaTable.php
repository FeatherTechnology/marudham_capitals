<?php
include '../ajaxconfig.php';
if (isset($_POST['area'])) {
    $area = $_POST['area'];
}
?>

<table class="table custom-table" id="subAreaTable">
    <thead>
        <tr>
            <th width="25%">S. No</th>
            <th>Sub Area Name</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM sub_area_list_creation WHERE area_id_ref = '" . $area . "' AND status=0 ORDER BY sub_area_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php if (isset($ct["sub_area_name"])) {
                            echo $ct["sub_area_name"];
                        } ?></td>
                    <td>
                        <a id="edit_sub_area" value="<?php if (isset($ct["sub_area_id"])) {
                                                            echo $ct["sub_area_id"];
                                                        } ?>"><span class="icon-border_color"></span></a> &nbsp;
                        <a id="delete_sub_area" value="<?php if (isset($ct["sub_area_id"])) {
                                                            echo $ct["sub_area_id"];
                                                        } ?>"><span class='icon-trash-2'></span>
                        </a>
                    </td>
                </tr>
        <?php $i = $i + 1;
            }
        } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var subAreaTable = $('#subAreaTable').DataTable({
            ...getStateSaveConfig('subAreaTable'),
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
                searchFunction('subAreaTable');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('SubArea_table'); // or any base
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
        initColVisFeatures(subAreaTable, 'subAreaTable');
    });
</script>

<?php
// Close the database connection
$connect = null;
?>