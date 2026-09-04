<?php
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';
?>
<table class="table custom-table" id='loanListTable'>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Responsible</th>
            <th>Loan Amount</th>
            <th>Collection Format</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Charts</th>
            <th>Info</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $cus_sts = $_POST['cus_sts'];

        $run = $connect->query("SELECT ii.loan_id, lcc.loan_category_creation_name as loan_catrgory_name, lc.sub_category, ac.ag_name, iv.responsible, lc.loan_amt_cal, lc.collection_method, ii.cus_status, ii.req_id, cs.sub_status, cls.closed_sts, cls.consider_level
        FROM acknowlegement_loan_calculation lc 
        LEFT JOIN in_issue ii ON lc.req_id = ii.req_id 
        LEFT JOIN in_verification iv ON ii.req_id = iv.req_id 
        LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id 
        LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
        LEFT JOIN agent_creation ac ON ac.ag_id = iv.agent_id
        LEFT JOIN closed_status cls ON ii.req_id = cls.req_id
        WHERE lc.cus_id_loan = '$cus_id' AND (ii.cus_status >= 14 AND ii.cus_status < 20)"); //Customer status greater than or equal to 14 because, after issued data only we need

        $i = 1;
        $curdate = date('Y-m-d');
        $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
        $coll_method = ['1' => 'By Self', '2' => 'Spot Collection', '3' => 'Cheque Collection', '4' => 'ECS'];

        while ($row = $run->fetch()) {
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['loan_id']; ?></td>
                <td><?php echo $row["loan_catrgory_name"]; ?></td>
                <td><?php echo $row["sub_category"]; ?></td>
                <td><?php echo $row["ag_name"] ?? '';?></td> <!-- Agent -->
                <td><?php echo ($row['responsible'] == '0') ? 'Yes' : (!empty($row['ag_name']) && $row['responsible'] != '0' ? 'No' : ''); ?></td>
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td>
                <td><?php echo $coll_method[$row["collection_method"]];?></td>
                <td><?php echo 'Present'; ?></td>
                <td><?php
                    if ($row['cus_status'] <= '20') {
                        echo $row['sub_status'];

                    } else if ($row['cus_status'] > '20') { // if status is closed(21) or more than that(22), then show closed status                        
                        if ($row['closed_sts'] == '1') {
                            echo 'Consider - ' . $consider_lvl_arr[$row['consider_level']];
                            
                        } else if ($row['closed_sts'] == '2') {
                            echo 'Waiting List';
                            
                        } else if ($row['closed_sts'] == '3') {
                            echo 'Block List';

                        }
                    }
                    ?>
                </td>
                
                <?php
                $req_id     = $row['req_id'];
                $collect_method = $row['collection_method'];
                $has_cus_sts = !empty($cus_sts);
                ?>

                <!-- Column 1: Charts Dropdown -->
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary">
                            <i class="fa">&#xf107;</i>
                        </button>
                        <div class="dropdown-content">
                            <a><span data-toggle="modal" data-target=".DueChart" class="due-chart" value="<?= $req_id ?>">Due Chart </span></a>
                                <a><span data-toggle="modal" data-target=".PenaltyChart" class="penalty-chart" value="<?= $req_id ?>">Penalty Chart</span></a>
                                <a><span data-toggle="modal" data-target=".collectionChargeChart" class="coll-charge-chart" value="<?= $req_id ?>">Fine Chart </span></a>
                            <a><span data-toggle="modal" data-target="#commitmentChart" class="commitment-chart" data-reqid="<?= $req_id ?>"><?= !empty($has_cus_sts) ? 'Commitment Chart' : 'Followup Chart' ?></span>
                            </a>
                        </div>
                    </div>
                </td>

                <!-- Column 2: Info & History Dropdown -->
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary">
                            <i class="fa">&#xf107;</i>
                        </button>
                        <div class="dropdown-content">
                            <?php if ($has_cus_sts): ?>
                                <a href="due_followup_info&upd=<?= $req_id ?>&pgeView=1">Customer Profile</a>
                                <a href="due_followup_info&upd=<?= $req_id ?>&pgeView=2">Documentation</a>
                                <a href="due_followup_info&upd=<?= $req_id ?>&pgeView=3">Loan Calculation</a>
                                <a href="" class="loan-history-window">Loan History</a>
                                <a href="" class="doc-history-window">Document History</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>

                <!-- Column 3: Actions Dropdown -->
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary">
                            <i class="fa">&#xf107;</i>
                        </button>
                        <div class="dropdown-content">
                            <a>
                                <span data-toggle="modal" data-target="#addCommitment" class="add-commitment-chart" data-reqid="<?= $req_id ?>"data-coll_mtd="<?= $collect_method ?>"><?= $has_cus_sts ? 'New Commitment' : 'Followup' ?></span>
                            </a>
                        </div>
                    </div>
                </td>
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
                    action: function(e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Loan_List'); // or any base
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
    });

    $('.due-chart, .penalty-chart, .coll-charge-chart, .coll-charge, .add-commitment-chart, .commitment-chart').css('color', 'black');
</script>

<?php
// Close the database connection
$connect = null;
?>