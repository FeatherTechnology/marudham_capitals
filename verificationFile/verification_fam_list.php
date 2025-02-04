<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table " id="famTable">
    <thead>
        <tr>
            <th style="width: 5px;">S.No</th>
            <th>Name</th>
            <th>Relationship</th>
            <!-- <th>Remark</th> -->
            <!-- <th>Address</th> -->
            <th>Age</th>
            <th>Aadhar No</th>
            <th>Mobile No</th>
            <th>Occupation</th>
            <th>Income</th>
            <th>Blood Group</th>
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
                <td> <?php echo $i++; ?></td>
                <td> <?php echo $fam['famname']; ?></td>
                <td> <?php echo $fam['relationship']; ?></td>
                <!-- <td> <?php echo ($fam['relationship'] == 'Other') ? $fam['other_remark'] : '---'; ?></td>
                <td> <?php echo ($fam['relationship'] == 'Other') ? $fam['other_address'] : '---'; ?></td> -->
                <td> <?php echo $fam['relation_age']; ?></td>
                <td> <?php echo $fam['relation_aadhar']; ?></td>
                <td> <?php echo $fam['relation_Mobile']; ?></td>
                <td> <?php echo $fam['relation_Occupation']; ?></td>
                <td> <?php echo $fam['relation_Income']; ?></td>
                <td> <?php echo $fam['relation_Blood']; ?></td>
            </tr>
        <?php //$i = $i + 1;
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#famTable').DataTable({
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
                searchFunction('famTable');
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