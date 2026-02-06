<?php
require '../ajaxconfig.php';
include '../moneyFormatIndia.php';

$qry = $connect->query("SELECT * From cus_old_data where cus_id = '" . $_POST['cus_id'] . "' ");

?>

<table class="table custom-table" id="oldCusData_table">
    <thead>
        <tr>
            <th width="50"> S.No </th>
            <th> Customer ID </th>
            <th> Customer Name </th>
            <th> Mobile </th>
            <th> Area </th>
            <th> Sub Area </th>
            <th> Loan Category </th>
            <th> Sub Category </th>
            <th> Loan Amount</th>
            <th> Due Chart</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $i = 1;
        while ($row = $qry->fetch()) {
        ?>

            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['cus_id']; ?></td>
                <td><?php echo $row['cus_name']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['area']; ?></td>
                <td><?php echo $row['sub_area']; ?></td>
                <td><?php echo $row['loan_cat']; ?></td>
                <td><?php echo $row['sub_cat']; ?></td>
                <td><?php echo moneyFormatIndia($row['loan_amt']); ?></td>
                <td><a href="uploads/updateFile/cus_data_old/<?php echo $row['due_chart_file']; ?>" target="_blank">Show File</a></td>
            </tr>


        <?php
        }

        ?>

    </tbody>
</table>


<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var oldCusData_table = $('#oldCusData_table').DataTable({
            ...getStateSaveConfig('oldCusData_table'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
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
                searchFunction('oldCusData_table');
            }
        });

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(oldCusData_table, 'oldCusData_table');
    });
</script>


<?php
// Close the database connection
$connect = null;
?>