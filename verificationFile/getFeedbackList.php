<?php
include '../ajaxconfig.php';
?>

<table class="table custom-table" id="cus_feedbackListTable">
    <thead>
        <tr>
            <th width="20%"> S.No </th>
            <th> Feedback Label </th>
            <th> ACTION </th>

        </tr>
    </thead>
    <tbody>

        <?php
        $feedbackDetails = $connect->query("SELECT id,feedback_name FROM `cus_feedback_name` where 1 order by id desc");

        $i = 1;
        while ($feedback = $feedbackDetails->fetch()) {
        ?>

            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $feedback["feedback_name"]; ?></td>
                <td>
                    <a id="feedback_edit" value="<?php echo $feedback['id']; ?>"> <span class="icon-border_color"></span></a> &nbsp
                    <a id="feedback_delete" value="<?php echo $feedback['id']; ?>"> <span class='icon-trash-2'></span> </a>
                </td>

            </tr>

        <?php $i = $i + 1;
        }     ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
    var cus_feedbackListTable = $('#cus_feedbackListTable').DataTable({
        ...getStateSaveConfig('cus_feedbackListTable'),
        processing: true,
        iDisplayLength: 5,
        lengthMenu: [
            [5, 10, 25, -1],
            [5, 10, 25, "All"]
        ],
        drawCallback: function () {
            this.api().column(0).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        },
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                action: function (e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Customer_feedback_info');
                    config.title = dynamic;
                    config.filename = dynamic;
                    defaultAction.call(this, e, dt, button, config);
                }
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
    });

    // Call search only once
    searchFunction('cus_feedbackListTable');
    initColVisFeatures(cus_feedbackListTable, 'cus_feedbackListTable');
});

</script>
<?php
// Close the database connection
$connect = null;
?>