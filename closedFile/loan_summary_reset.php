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
        $req_id = $_POST['reqId'];
        $feedbackDetails = $connect->query("SELECT * FROM `loan_summary_feedback` WHERE `req_id`='$req_id' order by id desc");

        $i = 1;
        while ($feedback = $feedbackDetails->fetch()) {
        ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $feedback["feedback_label"]; ?></td>
                <td><?php if($feedback["cus_feedback"] == '1'){ echo 'Bad';}else if($feedback["cus_feedback"] == '2'){ echo 'Poor';}else if($feedback["cus_feedback"] == '3'){ echo 'Average';}else if($feedback["cus_feedback"] == '4'){ echo 'Good';}else if($feedback["cus_feedback"] == '5'){ echo 'Excellent';} ?></td>
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