<?php
session_start();
include '../ajaxconfig.php';
include '../moneyFormatIndia.php';

if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .dropbtn {
        color: white;
        /* background-color: #009688; */
        /* padding: 10px; */
        font-size: 10px;
        border: none;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #F9F9F9;
        min-width: 160px;
        margin-top: -50px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 10px 10px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #fafafa;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3E8E41;
    }

    .btn-outline-secondary {
        color: #383737;
        border-color: #383737;
        position: inherit;
        /* left: -20px; */
    }
</style>

<table class="table custom-table" id='loanListTable'>
    <thead>
        <tr>
            <th width='50'>Loan ID</th>
            <th>Doc ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
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
            
            $cus_id = $_POST['cus_id'];
            $screen = $_POST['screen'] ?? '';

            //for both NOC & NOC handover using this screen for loan list so if noc screen means show till handover but action may vary and handover screen means show only in noc handover. 
            if($screen == 'nochandover'){
                $cus_sts = "23";

            }else{
                $cus_sts = "21,22,23";
            }

            $run = $connect->query("SELECT ii.loan_id, lc.cus_name_loan as cus_name, ad.doc_id, lcc.loan_category_creation_name as loan_catrgory_name, lc.sub_category, iv.agent_id, ii.updated_date, lc.loan_amt_cal, ii.req_id, ii.cus_status
            FROM acknowlegement_loan_calculation lc 
            JOIN acknowlegement_documentation ad ON lc.req_id = ad.req_id 
            JOIN in_issue ii ON lc.req_id = ii.req_id 
            JOIN in_verification iv ON ii.req_id = iv.req_id 
            JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
            WHERE lc.cus_id_loan = $cus_id and ii.cus_status IN ($cus_sts) "); //21 means loan has been closed form closed window for noc

            while ($row = $run->fetch()) {
                $qry = $connect->query("SELECT created_date, closed_sts, consider_level FROM `closed_status` WHERE req_id = '" . $row['req_id'] . "' ");
                $runqry = $qry->fetch();
        ?>
            <tr>
                <td><?php echo $row["loan_id"]; ?></td>
                <td><?php echo $row["doc_id"]; ?></td>
                <td><?php echo $row["loan_catrgory_name"]; ?></td>
                <td><?php echo $row["sub_category"]; ?></td>
                <td>
                    <?php
                    if ($row["agent_id"] != '' || $row["agent_id"] != NULL) {
                        $run1 = $connect->query('SELECT ag_name from agent_creation where ag_id = "' . $row['agent_id'] . '" ');
                        echo $run1->fetch()['ag_name'];
                    }
                    ?>
                </td>
                <td><?php echo date('d-m-Y', strtotime($row["updated_date"])); ?></td>
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td>
                <td><?php echo date('d-m-Y', strtotime($runqry['created_date'])); ?></td> <!-- closed date-->
                <td><?php echo 'NOC'; ?></td>
                <td><?php if ($row['cus_status'] == '21') {
                        echo 'Pending';
                    } elseif ($row['cus_status'] == '22' || $row['cus_status'] == '23' ) {
                        echo 'Completed';
                    } else {
                        echo '';
                    } ?></td>
                <td>
                    <?php if ($runqry['consider_level'] == '1') {
                        echo 'Bronze';
                    } elseif ($runqry['consider_level'] == '2') {
                        echo 'Silver';
                    } elseif ($runqry['consider_level'] == '3') {
                        echo 'Gold';
                    } elseif ($runqry['consider_level'] == '4') {
                        echo 'Platinum';
                    } elseif ($runqry['consider_level'] == '5') {
                        echo 'Diamond';
                    } else {
                        echo '';
                    } ?>
                </td>
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

                                    <a href="#" title="NOC Letter" class="noc-letter" data-reqid="<?= $row['req_id']; ?>" data-cusid="<?= $cus_id; ?>"> NOC Letter </a>

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