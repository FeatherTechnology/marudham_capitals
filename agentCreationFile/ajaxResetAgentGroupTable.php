<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="agentgroupTable">
    <thead>
        <tr>
            <th width="25%">S. NO</th>
            <th>Agent Group</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ctselect = "SELECT * FROM agent_group_creation WHERE 1 AND status=0 ORDER BY agent_group_id DESC";
        $ctresult = $connect->query($ctselect);
        if ($ctresult->rowCount() > 0) {
            $i = 1;
            while ($ct = $ctresult->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php if (isset($ct["agent_group_name"])) {
                            echo $ct["agent_group_name"];
                        } ?></td>
                    <td>
                        <a id="edit_agent_group" value="<?php if (isset($ct["agent_group_id"])) {
                                                            echo $ct["agent_group_id"];
                                                        } ?>"><span class="icon-border_color"></span></a> &nbsp
                        <a id="delete_agent_group" value="<?php if (isset($ct["agent_group_id"])) {
                                                                echo $ct["agent_group_id"];
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
        $('#agentgroupTable').DataTable({
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
                searchFunction('agentgroupTable');
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