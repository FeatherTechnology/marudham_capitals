<?php

include('../../ajaxconfig.php');

$cus_id = $_POST['cus_id'];

$sql = $connect->query("SELECT a.*,b.fullname, CASE b.role WHEN 1 then 'Director' when 2 then 'Agent' when 3 then 'Staff' end as role FROM loan_followup a 
        JOIN user b ON a.insert_login_id = b.user_id WHERE a.cus_id = '$cus_id'  ORDER BY a.id DESC "); //order by desc will show last entered data of promotion table

//this query will take loan followup data from that table with username and user type according to inserted login id and using switch case in query for output

?>


<table class="table custom-table" id='loan_follow_chart'>
    <thead>
        <th width='20'>Date</th>
        <th>Stage</th>
        <th>Label</th>
        <th>Remark</th>
        <th>User Type</th>
        <th>User</th>
        <th>Follow Date</th>
    </thead>
    <tbody>
        <?php while ($row =  $sql->fetch()) { ?>
            <tr>
                <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td>
                <td><?php echo $row['stage']; ?></td>
                <td><?php echo $row['label']; ?></td>
                <td><?php echo $row['remark']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo date('d-m-Y', strtotime($row['follow_date'])); ?></td>

            </tr>
        <?php } ?>

    </tbody>
</table>

<script>
    // Declare table variable to store the DataTable instance
    var loan_follow_chart = $('#loan_follow_chart').DataTable({
        ...getStateSaveConfig('loan_follow_chart'),
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
                    var dynamic = curDateJs('Loan_Followup_Chart'); // or any base
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
    })

    // Pass the table variable to the initColVisFeatures function
    initColVisFeatures(loan_follow_chart, 'loan_follow_chart');
</script>
<style>
    @media (max-width: 598px) {
        #loanFollowChartDiv {
            overflow: auto;
        }
    }
</style>

<?php
// Close the database connection
$connect = null;
?>