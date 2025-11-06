<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="feedback_table_data">
    <thead>
        <tr>
            <th width="20%"> S.No </th>
            <th> Feedback Label </th>
            <th> Feedback </th>
            <th> Remarks </th>
            <th> ACTION </th>

        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $feedbackDetails = $connect->query("SELECT * FROM `verification_cus_feedback` WHERE `cus_id`='$cus_id' order by id desc");

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

                <td>
                    <a id="cus_feedback_edit" value="<?php echo $feedback['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="cus_feedback_delete" value="<?php echo $feedback['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>

            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#feedback_table_data').DataTable({
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
                searchFunction('feedback_table_data');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Customer_feedback_info'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
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