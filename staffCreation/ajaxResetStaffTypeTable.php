<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="staffTypeTable">
    <thead>
        <tr>
            <th width="25%">S. No</th>
            <th>Staff Type</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM staff_type_creation WHERE 1 AND status=0 ORDER BY staff_type_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php if (isset($ct["staff_type_name"])) {
                            echo $ct["staff_type_name"];
                        } ?></td>
                    <td>
                        <a id="edit_staff_type" value="<?php if (isset($ct["staff_type_id"])) {
                                                            echo $ct["staff_type_id"];
                                                        } ?>"><span class="icon-border_color"></span></a> &nbsp
                        <a id="delete_staff_type" value="<?php if (isset($ct["staff_type_id"])) {
                                                                echo $ct["staff_type_id"];
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
        $('#staffTypeTable').DataTable({
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
                searchFunction('staffTypeTable');
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