<?php
session_start();
include '../ajaxconfig.php';
include '../moneyFormatIndia.php';

if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}
if (isset($_POST["pending_sts"])) {
    $pending_sts = explode(',', $_POST["pending_sts"]);
}
if (isset($_POST["od_sts"])) {
    $od_sts = explode(',', $_POST["od_sts"]);
}
if (isset($_POST["due_nil_sts"])) {
    $due_nil_sts = explode(',', $_POST["due_nil_sts"]);
}
if (isset($_POST["closed_sts"])) {
    $closed_sts = explode(',', $_POST["closed_sts"]);
}
if (isset($_POST["bal_amt"])) {
    $bal_amt = explode(',', $_POST["bal_amt"]);
}

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
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $run = $connect->query("SELECT ii.loan_id, lcc.loan_category_creation_name AS loan_catrgory_name, lc.sub_category, ac.ag_name, rc.responsible, ii.updated_date, lc.loan_amt_cal, cc.closing_date, ii.cus_status, lc.due_start_from, ii.req_id, cs.closed_sts, cs.consider_level
        FROM acknowlegement_loan_calculation lc 
        JOIN in_issue ii ON lc.req_id = ii.req_id 
        JOIN request_creation rc ON ii.req_id = rc.req_id  
        JOIN closing_customer cc ON cc.req_id = ii.req_id
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id 
        LEFT JOIN agent_creation ac ON rc.agent_id = ac.ag_id
        LEFT JOIN closed_status cs ON lc.req_id = cs.req_id
        WHERE lc.cus_id_loan = $cus_id and (ii.cus_status >= 14 and ii.cus_status <= 20) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $i = 1;
        $curdate = date('Y-m-d');
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
                <td><?php echo moneyFormatIndia($bal_amt[$i - 1]); ?></td> <!-- Balance Amount -->
                <td><?php if ($row['cus_status'] < 20) {
                        echo 'Present';
                    } else if ($row['cus_status'] >= 20) {
                        echo 'Closed';
                    } ?>
                </td> <!-- Status -->
                <td><?php
                    if ($row['cus_status'] <= '20') {
                        if (date('Y-m-d', strtotime($row['due_start_from'])) > date('Y-m-d', strtotime($curdate))  and $bal_amt[$i - 1] != 0) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
                            if ($row['cus_status'] == '15') {
                                echo 'Error';
                            } elseif ($row['cus_status'] == '16') {
                                echo 'Legal';
                            } else {
                                echo 'Current';
                            }
                        } else {
                            if ($pending_sts[$i - 1] == 'true' && $od_sts[$i - 1] == 'false') {
                                if ($row['cus_status'] == '15') {
                                    echo 'Error';
                                } elseif ($row['cus_status'] == '16') {
                                    echo 'Legal';
                                } else {
                                    echo 'Pending';
                                }
                            } else if ($od_sts[$i - 1] == 'true' && $due_nil_sts[$i - 1] == 'false') {
                                if ($row['cus_status'] == '15') {
                                    echo 'Error';
                                } elseif ($row['cus_status'] == '16') {
                                    echo 'Legal';
                                } else {
                                    echo 'OD';
                                }
                            } elseif ($due_nil_sts[$i - 1] == 'true') {
                                if ($row['cus_status'] == '15') {
                                    echo 'Error';
                                } elseif ($row['cus_status'] == '16') {
                                    echo 'Legal';
                                } else {
                                    echo 'Due Nil';
                                }
                            } elseif ($pending_sts[$i - 1] == 'false') {
                                if ($row['cus_status'] == '15') {
                                    echo 'Error';
                                } elseif ($row['cus_status'] == '16') {
                                    echo 'Legal';
                                } else {
                                    if ($closed_sts[$i - 1] == 'true') {
                                        echo "Closed";
                                    } else {
                                        echo 'Current';
                                    }
                                }
                            }
                        }
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

        <?php $i++;
        } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        // Declare table variable to store the DataTable instance
        var loanListTable = $('#loanListTable').DataTable({
            ...getStateSaveConfig('loanListTable'),
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

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(loanListTable, 'loanListTable');
    });
</script>

<?php 
// Close the database connection
$connect = null;
?>