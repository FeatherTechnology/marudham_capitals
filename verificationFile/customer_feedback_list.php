<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="feedback_table">
    <thead>
        <tr>
            <th width="20%"> S.No </th>
            <th> Feedback Label </th>
            <th> Feedback </th>
            <th> Remarks </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $feedbackDetails = $connect->query("SELECT * FROM `verification_cus_feedback` where cus_id = '$cus_id' order by id desc");

        $i = 1;
        while ($feedback = $feedbackDetails->fetch()) {
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $feedback["feedback_label"]; ?></td>
                <td><?php if ($feedback["cus_feedback"] == '1') {
                        echo 'Bad';
                    } else if ($feedback["cus_feedback"] == '2') {
                        echo 'Poor';
                    } else if ($feedback["cus_feedback"] == '3') {
                        echo 'Average';
                    } else if ($feedback["cus_feedback"] == '4') {
                        echo 'Good';
                    } else if ($feedback["cus_feedback"] == '5') {
                        echo 'Excellent';
                    } ?></td>
                <td><?php echo $feedback["feedback_remark"]; ?></td>
            </tr>

        <?php  } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#feedback_table').DataTable({
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
                searchFunction('feedback_table');
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