<?php
session_start();
include '../ajaxconfig.php';
include '../moneyFormatIndia.php';

if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}
?>

<table class="table custom-table" id='loanListTable'>
    <thead>
        <tr>
            <th width='50'>Loan ID</th>
            <th>Doc ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Responsible</th>
            <th>Loan date</th>
            <th>Loan Amount</th>
            <th>Closed Date</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $userQry = $connect->query("SELECT noc_replace_access FROM user WHERE user_id = $user_id");
            $rowuser = $userQry->fetch();
            $nocReplaceAccess = $rowuser['noc_replace_access'];
            
            $cus_id = $_POST['cus_id'] ?? '';
            $req_id = $_POST['reqid'] ?? '';
            $screen = $_POST['screen'] ?? '';

            //for both NOC & NOC handover using this screen for loan list so if noc screen means show till handover but action may vary and handover screen means show only in noc handover. 
            $screen_condition = ""; 
            if($screen == 'nochandover'){
                $cus_sts = "23";
                $screen_condition = " AND lc.req_id = $req_id "; 
            }else{
                $cus_sts = "21,22,23";
                $screen_condition = " AND lc.cus_id_loan = $cus_id AND (n.receive_status = 0 OR n.req_id IS NULL)"; 
            }

            $run = $connect->query("SELECT ii.loan_id, lc.cus_name_loan as cus_name, ad.doc_id, lcc.loan_category_creation_name as loan_catrgory_name, lc.sub_category, ac.ag_name, iv.responsible, ii.updated_date, lc.loan_amt_cal, ii.req_id, ii.cus_status, cs.created_date, cs.consider_level
            FROM acknowlegement_loan_calculation lc 
            JOIN acknowlegement_documentation ad ON lc.req_id = ad.req_id 
            JOIN in_issue ii ON lc.req_id = ii.req_id 
            JOIN in_verification iv ON ii.req_id = iv.req_id 
            JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
            LEFT JOIN noc n ON ii.req_id = n.req_id
            LEFT JOIN agent_creation ac ON iv.agent_id = ac.ag_id
            LEFT JOIN closed_status cs ON lc.req_id = cs.req_id
            WHERE ii.cus_status IN ($cus_sts) $screen_condition "); //21 means loan has been closed form closed window for noc

            $consider_lvl = ['1' => 'Bronze', '2' => 'Silver', '3' => 'Gold', '4' => 'Platinum', '5' => 'Diamond'];
            while ($row = $run->fetch()) {
        ?>
            <tr>
                <td><?php echo $row["loan_id"]; ?></td>
                <td><?php echo $row["doc_id"]; ?></td>
                <td><?php echo $row["loan_catrgory_name"]; ?></td>
                <td><?php echo $row["sub_category"]; ?></td>
                <td><?php echo $row["ag_name"] ?? '';?></td> <!-- Agent -->
                <td><?php echo ($row['responsible'] == '0') ? 'Yes' : (!empty($row['ag_name']) && $row['responsible'] != '0' ? 'No' : ''); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row["updated_date"])); ?></td>
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td>
                <td><?php echo date('d-m-Y', strtotime($row['created_date'])); ?></td> <!-- closed date-->
                <td><?php echo 'NOC'; ?></td>
                <td><?php if ($row['cus_status'] == '21') {
                        echo 'Pending';
                    } elseif ($row['cus_status'] == '22' || $row['cus_status'] == '23' ) {
                        echo 'Completed';
                    } else {
                        echo '';
                    } ?></td>
                <td><?php echo $consider_lvl[$row['consider_level']] ?? ''; ?></td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary"><i class="fa">&#xf107;</i></button>
                            <div class="dropdown-content">
                                <?php if ($nocReplaceAccess == 0 && $screen == 'noc'){ //need noc replace access //if noc replace access user open handover means they can handover noc so show noc option. // if having noc replace means show only noc replace and noc summary;
                                    if ($row['cus_status'] == '21'){ //noc replace show only if cus status is 21. ?>

                                        <a href="#" class="noc-replace" data-value="<?= $row['req_id']; ?>"> Replace </a>
                                
                                    <?php }  
                                } else{ //NOC & NOC handover using this same screen for loan list so if 21=IN-NOC, 23=NOC-Completed means show NOC to submit noc in NOC & NOC handover but they process are different.
                                    if ($row['cus_status'] == '21' || $row['cus_status'] == '23') { ?>

                                        <a href="#" class="noc-window" data-value="<?= $row['req_id']; ?>"> NOC </a>

                                    <?php } 
                                } 
                                
                                if ($row['cus_status'] > '21' || ($nocReplaceAccess == 0 && $screen == 'noc')){ //if NOC completed or replace access user then show summary.
                                ?>

                                    <a href="#" class="noc-summary" data-reqid="<?= $row['req_id']; ?>" data-cusid="<?= $cus_id; ?>" data-cusname="<?= $row['cus_name']; ?>" data-toggle="modal" data-target=".noc-summary-modal"> NOC Summary </a>

                                <?php } 
                                if ($row['cus_status'] > '21'){ //if NOC Completed show NOC letter.
                                ?>

                                    <a href="#" title="NOC Letter" class="noc-letter" data-reqid="<?= $row['req_id']; ?>"> NOC Letter </a>

                                <?php } ?>

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
        var loanListTable = $('#loanListTable').DataTable({
            ...getStateSaveConfig('loanListTable'),
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "drawCallback": function(settings) {
                searchFunction('loanListTable');
            },
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

        // Pass the table variable to the initColVisFeatures function
        initColVisFeatures(loanListTable, 'loanListTable');
    });
</script>

<?php
// Close the database connection
$connect = null;
?>