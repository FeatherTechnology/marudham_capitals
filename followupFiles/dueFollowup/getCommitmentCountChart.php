<?php
include('../../ajaxconfig.php');

$req_id = $_POST['req_id'];

$sql = $connect->query("SELECT * FROM `closed_loan_commitment_count` WHERE `req_id` = '$req_id'");

$sno = 1;
?>

<table class="table custom-table" id='commitment_count_chart'>
    <thead>
        <th width='20'>S No</th>
        <th>1-10</th>
        <th>11-15</th>
        <th>16-20</th>
        <th>21-25</th>
        <th>26-31</th>
    </thead>
    <tbody>
        <?php while ($row =  $sql->fetch()) { ?>
            <tr>
                <td><?php echo $sno++; ?></td>
                <td><?php echo $row['one_to_ten']; ?></td>
                <td><?php echo $row['eleven_to_fifteen']; ?></td>
                <td><?php echo $row['sixteen_to_twenty']; ?></td>
                <td><?php echo $row['twentyone_to_twentyfive']; ?></td>
                <td><?php echo $row['twentysix_to_thirtyone']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    $('#commitment_count_chart').DataTable({
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                action: function(e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('commitment_count_chart'); // or any base
                    config.title = dynamic; // for versions that use title as filename
                    config.filename = dynamic; // for html5 filename
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
    });
</script>

<style>
    @media (max-width: 598px) {
        #commChartDiv {
            overflow: auto;
        }
    }
</style>

<?php
// Close the database connection
$connect = null;
?>