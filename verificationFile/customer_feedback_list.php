<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="feedback_table">
    <thead>
        <tr>
            <th width="20%"> S.No </th>
            <th> User Name</th>
            <th> Created Date </th>
            <th> Feedback Label </th>
            <th> Feedback </th>
            <th> Remarks </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $feedbackDetails = $connect->query("SELECT vcf.*,cfn.feedback_name ,u.fullname FROM `verification_cus_feedback` vcf join cus_feedback_name cfn on cfn.id = vcf.feedback_label join user u on u.user_id = vcf.insert_login_id WHERE vcf.`cus_id`='$cus_id' order by id desc");

        $i = 1;
        while ($feedback = $feedbackDetails->fetch()) {
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $feedback["fullname"]; ?></td>
                <td><?php echo date('d-m-Y', strtotime($feedback["inserted_date"])); ?></td>
                <td><?php echo $feedback["feedback_name"]; ?></td>
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
        // Declare table variable to store the DataTable instance
        var feedback_table = $('#feedback_table').DataTable({
            ...getStateSaveConfig('feedback_table'),
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
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Customer_feedback_info'); // or any base
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

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(feedback_table, 'feedback_table');
    });
</script>
<?php
// Close the database connection
$connect = null;
?>