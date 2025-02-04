<?php
require '../ajaxconfig.php';

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
        $('#oldCusData_table').DataTable({
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
    });
</script>


<?php
function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}

// Close the database connection
$connect = null;
?>