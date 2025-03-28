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
            <th>Status</th>
            <th>Sub Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $run = $connect->query("SELECT lc.due_start_from,lc.loan_category,lc.sub_category,lc.loan_amt_cal,lc.due_amt_cal,lc.net_cash_cal,lc.collection_method,ii.loan_id,ii.req_id,ii.updated_date,ii.cus_status,
        rc.agent_id,lcc.loan_category_creation_name as loan_catrgory_name, us.collection_access
        from acknowlegement_loan_calculation lc JOIN in_issue ii ON lc.req_id = ii.req_id JOIN request_creation rc ON ii.req_id = rc.req_id 
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id JOIN user us ON us.user_id = $user_id
        WHERE lc.cus_id_loan = $cus_id and (ii.cus_status >= 14 and ii.cus_status <= 20) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $i = 1;
        $curdate = date('Y-m-d');
        $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
        while ($row = $run->fetch()) {
            //Show NOC button until closed_status submit so we check the count of closed status against the request id.
            $ii_req_id = $row["req_id"];
            $closedSts = $connect->query("SELECT * FROM `closed_status` WHERE `req_id` ='" . strip_tags($ii_req_id) . "' ");
            $closed_cnt = $closedSts->rowCount();

        ?>
            <tr>
                <td><?php echo $row['loan_id']; ?></td> <!-- id -->
                <td><?php echo $row["loan_catrgory_name"]; ?></td> <!-- Loan Cat -->
                <td><?php echo $row["sub_category"]; ?></td> <!-- Loan Sub Cat -->
                <td>
                    <?php
                    if ($row["agent_id"] != '' || $row["agent_id"] != NULL) {
                        $run1 = $connect->query('SELECT ag_name from agent_creation where ag_id = "' . $row['agent_id'] . '" ');
                        echo $run1->fetch()['ag_name'];
                    }
                    ?>
                </td> <!-- Agent -->
                <td><?php echo date('d-m-Y', strtotime($row["updated_date"])); ?></td> <!-- Loan date -->
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td> <!-- Loan Amount -->
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
                            } else if ($od_sts[$i - 1] == 'true') {
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
                        $closedSts = $connect->query("SELECT * FROM `closed_status` WHERE `req_id` ='" . strip_tags($ii_req_id) . "' ");
                        $closedStsrow = $closedSts->fetch();
                        $rclosed = $closedStsrow['closed_sts'];
                        $consider_lvl = $closedStsrow['consider_level'];
                        if ($rclosed == '1') {
                            echo 'Consider - ' . $consider_lvl_arr[$consider_lvl];
                        }
                        if ($rclosed == '2') {
                            echo 'Waiting List';
                        }
                        if ($rclosed == '3') {
                            echo 'Block List';
                        }
                    } ?></td> <!-- Sub status -->
                <td>
                    <?php
                    if ($closed_cnt == '0') {
                        if ($row['cus_status'] == '20') { // 20 is collection completed.
                            echo  $action = "<div class='dropdown'><span class='btn btn-primary noc-window'  data-value='" . $row['req_id'] . "'>  Close </span></div>";
                        }
                    } else {
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