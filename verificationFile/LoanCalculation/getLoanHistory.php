<?php
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';
?>

<table class="table custom-table" id='loanListTable'>
    <thead>
        <tr>
            <th>Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Responsible</th>
            <th>Loan date</th>
            <th>Loan Amount</th>
            <th>Closing Date</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];

        $run = $connect->query("SELECT ii.loan_id, lcc.loan_category_creation_name as loan_catrgory_name, lc.sub_category, ac.ag_name, iv.responsible, ii.updated_date, lc.loan_amt_cal, cs.updated_date AS closed_date, cs.closed_sts, cs.consider_level, ii.cus_status, lc.due_start_from, ii.req_id, c.sub_status
        FROM acknowlegement_loan_calculation lc 
        JOIN in_issue ii ON lc.req_id = ii.req_id 
        JOIN in_verification iv ON ii.req_id = iv.req_id 
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id 
        LEFT JOIN agent_creation ac ON ac.ag_id = iv.agent_id
        LEFT JOIN closed_status cs ON ii.req_id = cs.req_id
        LEFT JOIN customer_status c ON ii.req_id = c.req_id
        WHERE lc.cus_id_loan = $cus_id AND (ii.cus_status >= 14) ORDER BY CAST(ii.req_id AS UNSIGNED) DESC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];

        while ($row = $run->fetch()) {
            //Show NOC button until closed_status submit so we check the count of closed status against the request id.
            $ii_req_id = $row["req_id"];
        ?>
            <tr>
                <td><?php echo $row['loan_id']; ?></td> <!-- id -->
                <td><?php echo $row["loan_catrgory_name"]; ?></td> <!-- Loan Cat -->
                <td><?php echo $row["sub_category"]; ?></td> <!-- Loan Sub Cat -->
                <td><?php echo $row["ag_name"] ?? '';?></td> <!-- Agent -->
                <td><?php echo ($row['responsible'] == '0') ? 'Yes' : (!empty($row['ag_name']) && $row['responsible'] != '0' ? 'No' : ''); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row["updated_date"])); ?></td> <!-- Loan date -->
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td> <!-- Loan Amount -->
                <td><?php echo ($row['closed_date'] !='') ? date('d-m-Y', strtotime($row["closed_date"])) : ''; ?></td> <!-- Closing Date -->
                <td><?php if ($row['cus_status'] < 20) {
                        echo 'Present';
                    } else if ($row['cus_status'] >= 20) {
                        echo 'Closed';
                    } ?>
                </td> <!-- Status -->
                <td>
                    <?php
                        if ($row['cus_status'] <= 20) {
                            echo $row['sub_status'] ?? 'Current';

                        } else if ($row['cus_status'] > 20) { // if status is closed(21) or more than that(22), then show closed status
                            if ($row['closed_sts'] == '1') {
                                echo 'Consider - ' . $consider_lvl_arr[$row['consider_level']];
                                
                            } else if ($row['closed_sts'] == '2') {
                                echo 'Waiting List';
                                
                            } else if ($row['closed_sts'] == '3') {
                                echo 'Block List';
                            }
                        }
                    ?>
                </td> <!-- Sub status -->
                <td> <!-- Action -->
                    <?php
                    $action = "<div class='dropdown'>
                        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                        <div class='dropdown-content'>
                            <a href='' data-reqid='$ii_req_id' data-cusid='$cus_id' data-toggle='modal' data-target='.DueChart' class='due-chart' >Due Chart</a>
                            <a href='' data-reqid='$ii_req_id' data-cusid='$cus_id' data-toggle='modal' data-target='.PenaltyChart' class='penalty-chart' >Penalty Chart</a>
                            <a href='' data-reqid='$ii_req_id' data-cusid='$cus_id' data-toggle='modal' data-target='.collectionChargeChart' class='collcharge-chart' >Fine Chart</a>
                            <a href='' data-reqid='$ii_req_id' data-cusid='$cus_id' data-toggle='modal' data-target='#commitmentChart' class='commitment-chart' >Commitment Chart</a>";
                        if ($row['cus_status'] > 20) { //if request goes to NOC then noc summary can be fetched
                            $action .= "<a href='' data-reqid='$ii_req_id' data-cusid='$cus_id' data-toggle='modal' data-target='.loansummarychart' class='loansummary-chart' >Loan Summary</a>";
                        }
                    $action .= "</div></div>";
                    echo $action;
                    ?>
                </td> <!-- Action -->
            </tr>

        <?php } ?>
    </tbody>
</table>

<script>
    // Declare table variable to store the DataTable instance
    $('#loanListTable').DataTable({
        "order": [ [0, "desc"] ],
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                title: "Loan History",
                action: function(e, dt, button, config) {
                    var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                    var dynamic = curDateJs('Loan_History'); // or any base
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
        'drawCallback': function() {
            searchFunction('loanListTable');
        }
    });
</script>

<?php
// Close the database connection
$connect = null;
?>