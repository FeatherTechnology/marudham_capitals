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
            <th width='50'>Loan ID</th>
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
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $run = $connect->query("SELECT lc.loan_category,lc.sub_category,lc.loan_amt_cal,lc.due_amt_cal,lc.net_cash_cal,lc.collection_method,ii.req_id,ii.updated_date,ii.cus_status,
        rc.agent_id,lcc.loan_category_creation_name as loan_catrgory_name, us.collection_access
        from acknowlegement_loan_calculation lc JOIN in_issue ii ON lc.req_id = ii.req_id JOIN request_creation rc ON ii.req_id = rc.req_id 
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id JOIN user us ON us.user_id = $user_id
        WHERE lc.cus_id_loan = $cus_id and ii.cus_status = 21 "); //21 means loan has been closed form closed window for noc

        $i = 1;
        while ($row = $run->fetch()) {
            $qry = $connect->query("SELECT closed_sts,consider_level,created_date FROM `closed_status` WHERE req_id = '" . $row['req_id'] . "' ");

            $runqry = $qry->fetch();
        ?>
            <tr>
                <td></td>
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
                <td><?php echo 'Closed'; ?></td>
                <td><?php if ($runqry['closed_sts'] == '1') {
                        echo 'Consider';
                    } elseif ($runqry['closed_sts'] == '2') {
                        echo 'Waitlist';
                    } elseif ($runqry['closed_sts'] == '3') {
                        echo 'Blocklist';
                    } else {
                        echo '';
                    } ?></td>
                <td><?php if ($runqry['consider_level'] == '1') {
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
                    } ?></td>
                <td><?php echo "<span class='btn btn-success noc-window' style='font-size: 17px;position: relative;top: 0px; background-color:#009688;' data-value='" . $row['req_id'] . "''>NOC</span>"; ?></td>

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
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function(settings) {
                this.api().column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
                searchFunction('loanListTable');
            },
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
        });
    });
</script>

<?php
// Close the database connection
$connect = null;
?>