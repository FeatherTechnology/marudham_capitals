<?php
session_start();
include '../../ajaxconfig.php';

if(isset($_SESSION["userid"])){
    $user_id = $_SESSION["userid"];
}


function moneyFormatIndia($num) {
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
<table class="table custom-table" id='LoanHistTable'>
    <thead>
        <tr>
            <th>Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Loan date</th>
            <th>Loan Amount</th>
            <th>Closing Date</th>
            <th>Status</th>
            <th>Sub Status</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $run = $connect->query("SELECT lc.due_start_from,lc.loan_category,lc.sub_category,lc.loan_amt_cal,lc.due_amt_cal,lc.net_cash_cal,lc.collection_method,ii.loan_id,ii.req_id,ii.updated_date,ii.cus_status,
        rc.agent_id,lcc.loan_category_creation_name as loan_catrgory_name, us.collection_access, cs.sub_status
        from acknowlegement_loan_calculation lc JOIN in_issue ii ON lc.req_id = ii.req_id JOIN request_creation rc ON ii.req_id = rc.req_id 
        JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id JOIN user us ON us.user_id = $user_id
        LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
        WHERE lc.cus_id_loan = $cus_id and (ii.cus_status >= 14 and ii.cus_status <= 22) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $i = 1;
        $curdate = date('Y-m-d');
        while ($row = $run->fetch()) {
            //Show NOC button until closed_status submit so we check the count of closed status against the request id.
            $ii_req_id = $row["req_id"];
            $closedSts = $connect->query("SELECT * FROM `closed_status` WHERE `req_id` ='".strip_tags($ii_req_id)."' ");
            $closed_row = $closedSts->fetch();
            $closed_cnt = $closedSts->rowCount();
            
        ?>
        <tr>
            <td><?php echo $row['loan_id']; ?></td> <!-- id -->
            <td><?php echo $row["loan_catrgory_name"]; ?></td> <!-- Loan Cat -->
            <td><?php echo $row["sub_category"]; ?></td> <!-- Loan Sub Cat -->
            <td>
                <?php 
                    if($row["agent_id"] != '' || $row["agent_id"] != NULL){
                        $run1 = $connect->query('SELECT ag_name from agent_creation where ag_id = "'.$row['agent_id'].'" ');
                        echo $run1->fetch()['ag_name'];
                    } 
                ?>
            </td> <!-- Agent -->
            <td><?php echo date('d-m-Y',strtotime($row["updated_date"])); ?></td> <!-- Loan date -->
            <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td> <!-- Loan Amount -->

            <td><!-- Closing Date -->
                <?php 
                if($closed_cnt > 0){
                    echo date('d-m-Y',strtotime(($closed_row["updated_date"]) ? $closed_row["updated_date"] : $closed_row["created_date"]));  
                } ?>
            </td>
            
            <td><?php if($row['cus_status'] < 20){echo 'Present';}else if($row['cus_status'] >= 20){ echo 'Closed';} ?>
            </td> <!-- Status -->
            <td>
                <?php 
                if($row['cus_status'] <= 20){
                    echo $row['sub_status'];

                }else if($row['cus_status'] > 20){// if status is closed(21) or more than that(22), then show closed status
                    
                    $closedSts = $connect->query("SELECT * FROM `closed_status` WHERE `req_id` ='".strip_tags($ii_req_id)."' ");
                    $rclosed = $closedSts->fetch()['closed_sts'];
                    if($rclosed == '1'){echo 'Consider';}
                    if($rclosed == '2'){echo 'Waiting List';}
                    if($rclosed == '3'){echo 'Block List';}
                }
                ?>
            </td> <!-- Sub status -->
        </tr>

        <?php  $i++;} ?>
    </tbody>
</table>


<script>
    $('#LoanHistTable').dataTable({
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
    })
    $('.dropdown').unbind('click');
    $('.dropdown').click(function(event) {
        event.preventDefault();
        $('.dropdown').not(this).removeClass('active');
        $(this).toggleClass('active');
    });
    
    $(document).click(function(event) {
        var target = $(event.target);
        if (!target.closest('.dropdown').length) {
            $('.dropdown').removeClass('active');
        }
    });
</script>

<?php
// Close the database connection
$connect = null;
?>