<?php
include '../ajaxconfig.php';
if (isset($_POST['taluk'])) {
    $taluk = $_POST['taluk'];
}
?>

<table class="table custom-table" id="areaTable">
    <thead>
        <tr>
            <th width="25%">S. No</th>
            <th>Area Name</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM area_list_creation WHERE taluk = '" . $taluk . "' AND status=0 ORDER BY area_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php if (isset($ct["area_name"])) {
                            echo $ct["area_name"];
                        } ?></td>
                    <td>
                        <a id="edit_area" value="<?php if (isset($ct["area_id"])) {
                                                        echo $ct["area_id"];
                                                    } ?>"><span class="icon-border_color"></span></a> &nbsp
                        <a id="delete_area" value="<?php if (isset($ct["area_id"])) {
                                                        echo $ct["area_id"];
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
        $('#areaTable').DataTable({
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
                searchFunction('areaTable');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ]
        });
    });
</script>

<?php
// Close the database connection
$connect = null;
?>