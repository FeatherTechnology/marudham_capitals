<?php
session_start();
include '../ajaxconfig.php';

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .btn-outline-secondary {
        color: #383737;
        border-color: #383737;
        position: inherit;
        left: -20px;
    }
</style>
<?php
function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash;
}
?>
<table class="table custom-table" id='loanListTable'>
    <thead>
        <tr>
            <th width="50">Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Loan date</th>
            <th>Loan Amount</th>
            <th>Balance Amount</th>
            <th>Collection Format</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Collect</th>
            <th>Charts</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $run = $connect->query("SELECT lc.due_start_from,lc.loan_category,lc.sub_category,lc.loan_amt_cal,lc.due_amt_cal,lc.net_cash_cal,lc.collection_method,ii.loan_id,ii.req_id,ii.updated_date,ii.cus_status,
        rc.agent_id,lcc.loan_category_creation_name as loan_catrgory_name, us.collection_access
        from acknowlegement_loan_calculation lc 
        LEFT JOIN in_issue ii ON lc.req_id = ii.req_id 
        LEFT JOIN request_creation rc ON ii.req_id = rc.req_id 
        LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id 
        LEFT JOIN user us ON us.user_id = '$user_id'
        WHERE lc.cus_id_loan = '$cus_id' and (ii.cus_status >= 14 and ii.cus_status < 20) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need

        $i = 1;
        $curdate = date('Y-m-d');
        while ($row = $run->fetch()) {
            // if($bal_amt[$i-1] != '0'){

        ?>
            <tr>
                <td><?php echo $row['loan_id']; ?></td>
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
                <td><?php echo moneyFormatIndia($bal_amt[$i - 1]); ?></td>
                <td><?php if ($row["collection_method"] == '1') {
                        echo 'By Self';
                    } else if ($row["collection_method"] == '2') {
                        echo 'Spot Collection';
                    } else if ($row["collection_method"] == '3') {
                        echo 'Cheque Collection';
                    } else if ($row["collection_method"] == '4') {
                        echo 'ECS';
                    } ?></td>
                <td><?php echo 'Present'; ?></td>
                <td><?php if (date('Y-m-d', strtotime($row['due_start_from'])) > date('Y-m-d', strtotime($curdate))  and $bal_amt[$i - 1] != 0) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
                        if ($row['cus_status'] == '15') {
                            echo $subStatus = 'Error';
                        } elseif ($row['cus_status'] == '16') {
                            echo $subStatus = 'Legal';
                        } else {
                            echo $subStatus = 'Current';
                        }
                    } else {
                        if ($pending_sts[$i - 1] == 'true' && $od_sts[$i - 1] == 'false') { //using i as 1 so subract it with 1
                            if ($row['cus_status'] == '15') {
                                echo $subStatus = 'Error';
                            } elseif ($row['cus_status'] == '16') {
                                echo $subStatus = 'Legal';
                            } else {
                                echo $subStatus = 'Pending';
                            }
                        } else if ($od_sts[$i - 1] == 'true' && $due_nil_sts[$i - 1] == 'false') {
                            if ($row['cus_status'] == '15') {
                                echo $subStatus = 'Error';
                            } elseif ($row['cus_status'] == '16') {
                                echo $subStatus = 'Legal';
                            } else {
                                echo $subStatus = 'OD';
                            }
                        } elseif ($due_nil_sts[$i - 1] == 'true') {
                            if ($row['cus_status'] == '15') {
                                echo $subStatus = 'Error';
                            } elseif ($row['cus_status'] == '16') {
                                echo $subStatus = 'Legal';
                            } else {
                                echo $subStatus = 'Due Nil';
                            }
                        } elseif ($pending_sts[$i - 1] == 'false') {
                            if ($row['cus_status'] == '15') {
                                echo $subStatus = 'Error';
                            } elseif ($row['cus_status'] == '16') {
                                echo $subStatus = 'Legal';
                            } else {
                                if ($closed_sts[$i - 1] == 'true') {
                                    echo $subStatus = "Move To Close";
                                } else {
                                    echo $subStatus = 'Current';
                                }
                            }
                        }
                    } 
                    //Need to update customer status so updating here with the live status.
                    $balAmnt = $bal_amt[$i - 1];
                    $current_date = date('Y-m-d');
                    $connect->query("UPDATE `customer_status` SET `cus_id`='$cus_id',`sub_status`='$subStatus',`bal_amnt`='$balAmnt',`insert_login_id`='$user_id',`created_date`='$current_date' WHERE `req_id`='".$row['req_id']."' ");
                    
                    ?></td>
                <td><?php echo "<span class='btn btn-success collection-window' style='font-size: 17px;position: relative;top: 0px; background-color:#009688;";
                    if ($row['cus_status'] == '16') {
                        echo 'display:none';
                    } //|| $row['cus_status']== '15' || $closed_sts[$i-1] == 'true'
                    echo " ' data-value='" . $row['req_id'] . "'' tabindex='0'>$</span>"; ?></td>
                <td>
                    <?php
                    $action = "<div class='dropdown' style='float:right'><button class='btn btn-outline-secondary' ";

                    $action .= "><i class='fa'>&#xf107;</i></button><div class='dropdown-content'>";
                    $action .= "<a><span data-toggle='modal' data-target='.DueChart' class='due-chart' value='" . $row['req_id'] . "' > Due Chart</span></a>
                        <a><span data-toggle='modal' data-target='.PenaltyChart' class='penalty-chart' value='" . $row['req_id'] . "' > Penalty Chart</span></a>
                        <a><span data-toggle='modal' data-target='.collectionChargeChart' class='coll-charge-chart' value='" . $row['req_id'] . "' > Fine Chart</span></a>
                        <a><span data-toggle='modal' data-target='#commitmentChart' class='commitment-chart' data-reqid='" . $row['req_id'] . "' > Commitment Chart </span></a>";
                    $action .= "</div></div>";
                    echo $action;
                    ?>
                </td>
                <td>
                    <?php
                    $action = "<div class='dropdown' style='float:right'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'>";
                    
                    if ($row['collection_access'] == '0') {
                        $action .= "<a href='' class='move-error' value='" . $row['req_id'] . "' > Move To Error</a>
                            <a href='' class='move-legal' value='" . $row['req_id'] . "' > Move To Legal</a>
                            <a href='' class='return-sub' value='" . $row['req_id'] . "' > Return Sub Status</a>
                            <a><span data-toggle='modal' data-target='.collectionCharges' class='coll-charge' value='" . $row['req_id'] . "' > Fine </span></a>";
                        //if balance is eqauls to zero, then that loan must be able to moved as closed
                        // if($closed_sts[$i-1] == 'true'){
                        //     $action .= "<a href='' class='move-closed' value='".$row['req_id']."' > Move To Closed</a>";
                        // }
                    }
                    $action .= "<a><span data-toggle='modal' data-target='#addCommitment' class='add-commitment-chart' data-reqid='" . $row['req_id'] . "' > New Commitment </span></a>";
                    $action .= "</div></div>";
                    echo $action;
                    ?>
                </td>
            </tr>

        <?php $i++;
        } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(function() {
        $('#loanListTable').DataTable({
            'processing': true,
            'iDisplayLength': 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
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
    $('.dropdown').off().click(function(event) {
        event.preventDefault();
        $('.dropdown').not(this).removeClass('active');
        $(this).toggleClass('active');
    });

    $(document).off().click(function(event) {
        var target = $(event.target);
        if (!target.closest('.dropdown').length) {
            $('.dropdown').removeClass('active');
        }
    });
    $('.due-chart, .penalty-chart, .coll-charge-chart, .coll-charge, .add-commitment-chart, .commitment-chart').css('color', 'black');
</script>