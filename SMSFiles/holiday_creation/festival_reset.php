<?php
include '../../ajaxconfig.php';
?>

<table class="table custom-table " id="festivaldatatable" >
    <thead>
        <tr>
            <th width="50">S.No</th>
            <th>Holiday Date</th>
            <th>Holiday</th>
            <th>Comments</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $festivalInfo = $connect -> query("SELECT * FROM `holiday_creation` where status = 0 order by holiday_id desc");
            $i = 1;
            while ($festival = $festivalInfo ->fetch()) {
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo date('d-m-Y',strtotime($festival["holiday_date"])); ?></td>
                    <td><?php echo $festival["holiday_name"]; ?></td>
                    <td><?php echo $festival["comments"]; ?></td>
                    <td>
                        <a id="festival_edit" value="<?php  echo $festival['holiday_id'];?>" > <span class="icon-border_color"></span></a> &nbsp
                        <a id="festival_delete" value="<?php echo $festival['holiday_id']; ?>" > <span class='icon-trash-2'></span> </a>
                    </td>
                </tr>
        <?php $i++;
            }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#festivaldatatable').DataTable({
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
        });
    });
</script>