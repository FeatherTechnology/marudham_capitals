<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="concernsubjectTable">
    <thead>
        <tr>
            <th width="25">S.No</th>
            <th>Subject</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM concern_subject WHERE 1 AND status=0 ORDER BY concern_sub_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php if (isset($ct["concern_subject"])) {
                            echo $ct["concern_subject"];
                        } ?></td>
                    <td>
                        <a id="edit_subject" value="<?php if (isset($ct["concern_sub_id"])) {
                                                        echo $ct["concern_sub_id"];
                                                    } ?>"><span class="icon-border_color"></span></a> &nbsp;
                        <a id="delete_subject" value="<?php if (isset($ct["concern_sub_id"])) {
                                                            echo $ct["concern_sub_id"];
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
        $('#concernsubjectTable').DataTable({
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
                searchFunction('concernsubjectTable')
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
