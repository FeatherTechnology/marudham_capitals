<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table " id="grpTable">
    <thead>
        <tr>
            <th style="width: 5px;">S.No</th>
            <th>Name</th>
            <th>Age</th>
            <th>Aadhar No</th>
            <th>Mobile No</th>
            <th>Gender</th>
            <th>Designation</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $cus_id = $_POST['cus_id'];
        $grpInfo = $connect->query("SELECT * FROM `verification_group_info` where cus_id='$cus_id' order by id desc");

        $i = 1;
        while ($grp = $grpInfo->fetch()) {
        ?>
            <tr>
                <td> <?php echo $i++; ?></td>
                <td> <?php echo $grp['group_name']; ?></td>
                <td> <?php echo $grp['group_age']; ?></td>
                <td> <?php echo $grp['group_aadhar']; ?></td>
                <td> <?php echo $grp['group_mobile']; ?></td>
                <td> <?php if (isset($grp['group_gender'])) {
                            if ($grp['group_gender'] == '1') {
                                echo 'Male';
                            } elseif ($grp['group_gender'] == '2') {
                                echo 'Female';
                            } elseif ($grp['group_gender'] == '3') {
                                echo 'Other';
                            }
                        } ?></td>
                <td> <?php echo $grp['group_designation']; ?></td>
            </tr>

        <?php  } ?>

    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        $('#grpTable').DataTable({
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
                searchFunction('grpTable');
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