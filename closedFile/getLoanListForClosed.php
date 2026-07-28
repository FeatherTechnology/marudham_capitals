<?php
include '../ajaxconfig.php';
include '../moneyFormatIndia.php';
?>
<table class="table custom-table" id='loanListTable'>
    <thead>
        <tr>
            <th width="50">Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Responsible</th>
            <th>Loan Date</th>
            <th>Loan Amount</th>
            <th>In Closed Date</th>
            <th>Balance Amount</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $run = $connect->query("SELECT ii.loan_id, lcc.loan_category_creation_name AS loan_catrgory_name, lc.sub_category, ac.ag_name, rc.responsible, ii.updated_date, lc.loan_amt_cal, cc.closing_date, ii.cus_status, lc.due_start_from, ii.req_id, cs.closed_sts, cs.consider_level, sts.sub_status, sts.bal_amnt
        FROM acknowlegement_loan_calculation lc 
        JOIN in_issue ii ON lc.req_id = ii.req_id 
        JOIN request_creation rc ON ii.req_id = rc.req_id  
        JOIN closing_customer cc ON cc.req_id = ii.req_id
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id 
        LEFT JOIN agent_creation ac ON rc.agent_id = ac.ag_id
        LEFT JOIN closed_status cs ON lc.req_id = cs.req_id
        LEFT JOIN customer_status sts ON lc.req_id = sts.req_id
        WHERE lc.cus_id_loan = $cus_id and (ii.cus_status >= 14 and ii.cus_status <= 20) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
        while ($row = $run->fetch()) {
        ?>
            <tr>
                <td><?php echo $row['loan_id']; ?></td> <!-- id -->
                <td><?php echo $row["loan_catrgory_name"]; ?></td> <!-- Loan Cat -->
                <td><?php echo $row["sub_category"]; ?></td> <!-- Loan Sub Cat -->
                <td><?php echo $row["ag_name"] ?? '';?></td> <!-- Agent -->
                <td><?php echo ($row['responsible'] == '0') ? 'Yes' : (!empty($row['ag_name']) && $row['responsible'] != '0' ? 'No' : ''); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row["updated_date"])); ?></td> <!-- Loan date -->
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td> <!-- Loan Amount -->
                <td><?php echo date('d-m-Y', strtotime($row["closing_date"])); ?></td> <!-- In Closed Date-->
                <td><?php echo moneyFormatIndia($row['bal_amnt']); ?></td> <!-- Balance Amount -->
                <td><?php if ($row['cus_status'] < 20) {
                        echo 'Present';
                    } else if ($row['cus_status'] >= 20) {
                        echo 'Closed';
                    } ?>
                </td> <!-- Status -->
                <td><?php
                    if ($row['cus_status'] <= '20') {
                        echo $row["sub_status"] ?? '';

                    } else if ($row['cus_status'] > 20) { // if status is closed(21) or more than that(22), then show closed status
                        if ($row['closed_sts'] == '1') {
                            echo 'Consider - ' . $consider_lvl_arr[$row['consider_level']];
                        }
                        if ($row['closed_sts'] == '2') {
                            echo 'Waiting List';
                        }
                        if ($row['closed_sts'] == '3') {
                            echo 'Block List';
                        }
                    } ?></td> <!-- Sub status -->
                <td>
                    <?php
                        if ($row['closed_sts'] == '' && $row['cus_status'] == '20') { // 20 is collection completed.
                            echo "<div class='dropdown'><span class='btn btn-primary noc-window'  data-value='" . $row['req_id'] . "'>  Close </span></div>";
                        }
                    ?>
                </td> <!-- Action -->
            </tr>

        <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        $('#loanListTable').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Loan_List'); // or any base
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
            'drawCallback': function() {
                searchFunction('loanListTable');
            }
        });
    });
</script>

<?php 
// Close the database connection
$connect = null;
?>