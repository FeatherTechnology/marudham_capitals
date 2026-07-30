<?php
include '../../ajaxconfig.php';
include '../../moneyFormatIndia.php';
?>

<table class="table custom-table" id='DocHistTable'>
    <thead>
        <tr>
            <th width='50'>Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Responsible</th>
            <th>Loan date</th>
            <th>Loan Amount</th>
            <th>Closing Date</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Document Status</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $cus_id = $_POST['cus_id'];
        $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];

        $run = $connect->query("SELECT ii.loan_id, lcc.loan_category_creation_name as loan_catrgory_name, lc.sub_category, ac.ag_name, iv.responsible, ii.updated_date, lc.loan_amt_cal, cls.updated_date AS closed_date, ii.cus_status, ii.req_id, cs.sub_status, cls.closed_sts, cls.consider_level
        FROM acknowlegement_loan_calculation lc 
        LEFT JOIN in_issue ii ON lc.req_id = ii.req_id 
        LEFT JOIN in_verification iv ON ii.req_id = iv.req_id 
        LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
        LEFT JOIN customer_status cs ON ii.req_id = cs.req_id
        LEFT JOIN agent_creation ac ON ac.ag_id = iv.agent_id
        LEFT JOIN closed_status cls ON ii.req_id = cls.req_id
        WHERE lc.cus_id_loan = '$cus_id' AND (ii.cus_status >= 13) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $curdate = date('Y-m-d');
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
            <td><?php echo date('d-m-Y',strtotime($row["updated_date"])); ?></td> <!-- Loan date -->
            <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td> <!-- Loan Amount -->
            <td><?php echo ($row['closed_date'] !='') ? date('d-m-Y', strtotime($row["closed_date"])) : ''; ?></td> <!-- Closing Date -->            
            <td><?php if($row['cus_status'] < 20){echo 'Present';}else if($row['cus_status'] >= 20){ echo 'Closed';} ?>
            </td> <!-- Status -->
            <td>
                <?php 
                    if($row['cus_status'] <= 20){
                        echo $row['sub_status'];

                    }else if($row['cus_status'] > 20){// if status is closed(21) or more than that(22), then show closed status
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
            <td><!-- Document status -->
                <?php
                    if($row['cus_status'] <= 20){//show for present contents and closed customer but not submitted in closed
                        if(getDocumentStatus($connect, $ii_req_id) == false){
                            echo 'Document Pending';

                        }else{
                            echo 'Document Completed';
                        }
                    }else if($row['cus_status'] == 21){ // show for closed(which are submitted in closed) and noc contents 
                        echo 'NOC Pending';

                    } else if ($row['cus_status'] >= 22 && $row['cus_status'] <= 23) {
                        echo 'NOC Completed';

                    } else if($row['cus_status'] == 24){
                        echo 'NOC Handovered';

                    } else if($row['cus_status'] == 25){
                        echo 'Agent Handovered';

                    }
                ?>
            </td>
        </tr>

        <?php } ?>
    </tbody>
</table>

<?php
function getDocumentStatus($connect, $req_id){

    $response = 'completed';
    $sts_qry = $connect->query("SELECT doc_sts FROM acknowlegement_documentation WHERE req_id = '$req_id' ");

    $sts_row = $sts_qry->fetch();
    if ($sts_row['doc_sts'] == 'NO') {
        $response = 'pending';
    }

    return ($response == 'completed') ? true : false;
}
?>

<script>
    $(document).on('click', '.noc-summary', function (e) {
        e.preventDefault();
        let req_id = $(this).data('reqid');
        var cus_name = $(this).data('cusname');
        $.ajax({
            url: 'verificationFile/documentation/getNOCSummary.php',
            data: { req_id, cus_name },
            type: 'post',
            cache: false,
            success: function(html){
                $('#nocsummaryModal').html(html);
            }
        });
    });

    // Declare table variable to store the DataTable instance
    $('#DocHistTable').DataTable({
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
                    var dynamic = curDateJs('Document_History'); // or any base
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
    });
</script>

<?php
// Close the database connection
$connect = null;
?>