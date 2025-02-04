<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="loancategoryTable">
    <thead>
        <tr>
            <th>S. NO</th>
            <th>LOAN CATEGORY</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM loan_category_creation WHERE 1 AND status=0 ORDER BY loan_category_creation_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php if (isset($ct["loan_category_creation_name"])) {
                            echo $ct["loan_category_creation_name"];
                        } ?></td>
                    <td>
                        <a id="edit_category" value="<?php if (isset($ct["loan_category_creation_id"])) {
                                                            echo $ct["loan_category_creation_id"];
                                                        } ?>"><span class="icon-border_color"></span></a> &nbsp;
                        <a id="delete_category" value="<?php if (isset($ct["loan_category_creation_id"])) {
                                                            echo $ct["loan_category_creation_id"];
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
        $('#loancategoryTable').DataTable({
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
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
            'drawCallback': function() {
                searchFunction('loancategoryTable');
            }
        });
    });
</script>

<?php
// Close the database connection
$connect = null;
?>