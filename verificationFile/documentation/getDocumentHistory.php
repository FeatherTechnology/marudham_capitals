<?php
session_start();
include '../../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
}
if (isset($_POST["screen"])) {
    $screen = $_POST["screen"];
} else {
    $screen = '';
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
<style>
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
<table class="table custom-table" id='DocListTable'>
    <thead>
        <tr>
            <th width='50'>Loan ID</th>
            <th>Loan Category</th>
            <th>Sub Category</th>
            <th>Agent</th>
            <th>Loan date</th>
            <th>Loan Amount</th>
            <th>Closing Date</th>
            <th>Status</th>
            <th>Sub Status</th>
            <th>Document Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        // $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];
        $run = $connect->query("SELECT lc.due_start_from,lc.cus_name_loan,lc.loan_category,lc.sub_category,lc.loan_amt_cal,lc.due_amt_cal,lc.net_cash_cal,lc.collection_method,ii.loan_id,ii.req_id,ii.updated_date,ii.cus_status,
        rc.agent_id,lcc.loan_category_creation_name as loan_catrgory_name, us.collection_access
        from acknowlegement_loan_calculation lc 
        LEFT JOIN in_issue ii ON lc.req_id = ii.req_id 
        LEFT JOIN request_creation rc ON ii.req_id = rc.req_id 
        LEFT JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id 
        LEFT JOIN user us ON us.user_id = '$user_id'
        WHERE lc.cus_id_loan = '$cus_id' and (ii.cus_status >= 13 and ii.cus_status <= 22) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC "); //Customer status greater than or equal to 14 because, after issued data only we need  

        $i = 1;
        $curdate = date('Y-m-d');
        while ($row = $run->fetch()) {
            //Show NOC button until closed_status submit so we check the count of closed status against the request id.
            $cus_name = $row["cus_name_loan"];
            $ii_req_id = $row["req_id"];
            $closedSts = $connect->query("SELECT * FROM `closed_status` WHERE `req_id` ='" . strip_tags($ii_req_id) . "' ");
            $closed_row = $closedSts->fetch();
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
                <td><?php if(isset($row["updated_date"])) echo date('d-m-Y', strtotime($row["updated_date"])); ?></td> <!-- Loan date -->
                <td><?php echo moneyFormatIndia($row["loan_amt_cal"]); ?></td> <!-- Loan Amount -->

                <td><?php
                    if ($closed_cnt > 0) {
                        echo date('d-m-Y', strtotime($closed_row["updated_date"])); ?> <!-- Closing Date -->
                    <?php } ?>
                </td>

                <td><?php if ($row['cus_status'] < 20) {
                        echo 'Present';
                    } else if ($row['cus_status'] >= 20) {
                        echo 'Closed';
                    } ?>
                </td> <!-- Status -->
                <td>
                    <?php
                    if (date('Y-m-d', strtotime($row['due_start_from'])) > date('Y-m-d', strtotime($curdate))  and $bal_amt[$i - 1] != 0) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
                        if ($row['cus_status'] == '15') {
                            echo 'Error';
                        } elseif ($row['cus_status'] == '16') {
                            echo 'Legal';
                        } else {
                            echo 'Current';
                        }
                    } else {
                        if ($row['cus_status'] <= 20) {
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
                                        echo "In Closed";
                                    } else {
                                        echo 'Current';
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
                        }
                    }
                    ?>
                </td> <!-- Sub status -->
                <td><!-- Document status -->
                    <?php
                    $doc_status = '';
                    if ($row['cus_status'] <= 20) { //show for present contents and closed customer but not submitted in closed
                        if (getDocumentStatus($connect, $ii_req_id, $cus_id) == false) {
                            $doc_status =  'Document Pending';
                            echo 'Document Pending';
                        } else {
                            $doc_status = 'Document Completed';
                            echo 'Document Completed';
                        }
                    } else if ($row['cus_status'] > 20 and $row['cus_status'] < 22) { // show for closed(which are submitted in closed) and noc contents 
                        if (getNOCDocDetails($connect, $ii_req_id, $cus_id) == 'completed') { // this function will be true when user started to give NOC to customer, then that will be in noc pending
                            echo 'NOC Completed';
                        } else {
                            echo 'NOC Pending';
                        }
                    } else if ($row['cus_status'] == 22) {
                        echo 'NOC Completed';
                    }
                    ?>
                </td>
                <td> <!-- Action -->
                    <?php
                    $action = "<div class='dropdown'>
                        <button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button>
                        <div class='dropdown-content'>";
                    if ($row['cus_status'] > 20) { //if request goes to NOC then noc summary can be fetched
                        $action .= "<a href='' class='noc-summary' data-reqid='$ii_req_id' data-cusid='$cus_id' data-cusname='$cus_name' data-toggle='modal' data-target='.noc-summary-modal' >NOC Summary</a>";
                    }
                    if ($screen == 'update' && $row['cus_status'] <= 20) { //cus status <= 20 will allow only document statuses only to edit, not NOC
                        $action .= "<a href='' class='edit-doc' data-reqid='$ii_req_id' data-cusid='$cus_id' data-cusname='$cus_name'>Edit Documents</a>";
                    }
                    $action .= "</div></div>";
                    echo $action;
                    ?>
                </td> <!-- Action -->
            </tr>

        <?php $i++;
        } ?>
    </tbody>
</table>

<?php
function getNOCDocDetails($connect, $req_id, $cus_id)
{

    $response = 'completed';

    $qry = $connect->query("SELECT * FROM signed_doc where req_id ='$req_id' and cus_id = '$cus_id' and noc_given = 0 ");
    if ($qry->rowCount() > 0) { // if condition true, then signed doc any one is given other may be pending to give
        $response = 'pending';
    }

    $qry = $connect->query("SELECT * FROM cheque_no_list where req_id ='$req_id' and cus_id = '$cus_id' and noc_given = 0 ");
    if ($qry->rowCount() > 0) { // if condition true, then Cheque doc any one is given other may be pending to give
        $response = 'pending';
    }

    $qry = $connect->query("SELECT * FROM acknowlegement_documentation where req_id ='$req_id' and cus_id_doc = '$cus_id' and (mortgage_process_noc = 0 or mortgage_document_noc = 0 or endorsement_process_noc = 0 or en_RC_noc = 0 or en_Key_noc = 0 ) ");
    if ($qry->rowCount() > 0) { // if condition true, then acknowlegement documentation any one is given other may be pending to give
        $response = 'pending';
    }

    $qry = $connect->query("SELECT * FROM gold_info where req_id ='$req_id' and cus_id = '$cus_id' and noc_given = 0 ");
    if ($qry->rowCount() > 0) { // if condition true, then Gold doc any one is given other may be pending to give
        $response = 'pending';
    }

    $qry = $connect->query("SELECT * FROM document_info where req_id ='$req_id' and cus_id = '$cus_id' and doc_info_upload_noc = 0 ");
    if ($qry->rowCount() > 0) { // if condition true, then Document doc any one is given other may be pending to give
        $response = 'pending';
    }

    return $response;
}

function getNOCSubmitted($connect, $req_id, $cus_id)
{
    // should check whether all documents have been given to customer but not removed from list
}

function getDocumentStatus($connect, $req_id, $cus_id)
{

    $response1 = 'completed';

    // $sts_qry = $connect->query("SELECT id,doc_Count FROM signed_doc_info where cus_id = '$cus_id' and req_id = '$req_id' ");//echo "SELECT id,doc_Count FROM signed_doc_info where cus_id = '$cus_id' and req_id = '$req_id' "; 
    // if($sts_qry->rowCount() > 0){
    //     while($sts_row=$sts_qry->fetch()){

    //         $sts_qry1 = $connect->query("SELECT * FROM signed_doc where cus_id = '$cus_id' and req_id = '$req_id' and signed_doc_id='".$sts_row['id']."' "); //echo ' $sts_qry1->rowCount():',$sts_qry1->rowCount(),' docCount:',$sts_row['doc_Count'],'---';
    //         if($sts_qry1->rowCount() == $sts_row['doc_Count'] && $response1 != 'pending' ){ // check whether mentioned count of signed document has been collected from customer or not
    //             $response1 = 'completed';// if condition true then all documents are collected
    //             //completed
    //         }else{
    //             $response1= 'pending';
    //         }
    //     }
    // }


    $response2 = 'completed';
    // $sts_qry = $connect->query("SELECT id,cheque_count FROM cheque_info where cus_id = '$cus_id' and req_id = '$req_id' ");
    // if($sts_qry->rowCount() > 0){

    //     while($sts_row=$sts_qry->fetch()){

    //         $sts_qry1 = $connect->query("SELECT * FROM cheque_upd where cus_id = '$cus_id' and req_id = '$req_id' and cheque_table_id='".$sts_row['id']."' ");
    //         if($sts_qry1->rowCount() == $sts_row['cheque_count'] && $response2 != 'pending'){ // check whether mentioned count of Cheque has been collected from customer or not
    //             $response2 = 'completed';// if condition true then all documents are collected
    //         }else{
    //             $response2 = 'pending';
    //         }
    //     }
    // }


    $response3 = 'completed';
    $sts_qry = $connect->query("SELECT mortgage_process,mortgage_document_pending,endorsement_process,Rc_document_pending FROM acknowlegement_documentation where cus_id_doc = '$cus_id' and req_id = '$req_id' ");

    if ($sts_qry->rowCount() > 0) {
        while ($sts_row = $sts_qry->fetch()) { //check any one of document for mortgage or endorsement is pending then response will be pending

            if ($sts_row['mortgage_process'] == '0') {
                if ($sts_row['mortgage_document_pending'] == 'YES') {
                    $response3 = 'pending';
                }
            }
            if ($sts_row['endorsement_process'] == '0') {
                if ($sts_row['Rc_document_pending'] == 'YES') {
                    $response3 = 'pending';
                }
            }
        }
    }


    $response4 = 'completed';
    // $sts_qry = $connect->query("SELECT * FROM document_info where cus_id = '$cus_id' and req_id = '$req_id' ");

    // if($sts_qry->rowCount() > 0){
    //     while($sts_row = $sts_qry->fetch()){

    //         if($sts_row['doc_upload'] == '' || $sts_row['doc_upload'] == null ){ // check any of document that are added in verification is not still uploaded
    //             $response4 = 'pending';
    //         }
    //     }
    // }


    if ($response1 == 'completed' and $response2 == 'completed' and $response3 == 'completed' and $response4 == 'completed') {
        $response = true;
    } else {
        $response = false;
    }

    return $response;
}
?>

<script>
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

    $('.noc-summary').click(function() {
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        var cus_name = $(this).data('cusname');

        $.ajax({
            url: 'verificationFile/documentation/getNOCSummary.php',
            data: {
                'req_id': req_id,
                'cus_id': cus_id
            },
            type: 'post',
            cache: false,
            success: function(html) {
                $('#nocsummaryModal').empty();
                $('#nocsummaryModal').html(html);
            }
        }).then(function() {

            // To get the Signed Document List on Checklist
            $.ajax({
                url: 'nocFile/getSignedDocList.php',
                data: {
                    'req_id': req_id,
                    'cus_name': cus_name
                },
                type: 'post',
                cache: false,
                success: function(response) {

                    $('#signDocDiv').empty()
                    $('#signDocDiv').html(response);

                }
            }).then(function() {
                remove4columns('signDocTable');
            })


            // To get the unused Cheque List on Checklist
            $.ajax({
                url: 'nocFile/getChequeDocList.php',
                data: {
                    'req_id': req_id,
                    'cus_name': cus_name
                },
                type: 'post',
                cache: false,
                success: function(response) {

                    $('#chequeDiv').empty()
                    $('#chequeDiv').html(response);
                }
            }).then(function() {
                remove4columns('chequeTable');
            })

            // To get the Mortgage List on Checklist
            $.ajax({
                url: 'nocFile/getMortgageList.php',
                data: {
                    'req_id': req_id,
                    'cus_name': cus_name
                },
                type: 'post',
                cache: false,
                success: function(response) {

                    $('#mortgageDiv').empty()
                    $('#mortgageDiv').html(response);
                }
            }).then(function() {
                remove4columns('mortgageTable');
            })

            // To get the Endorsement List on Checklist
            $.ajax({
                url: 'nocFile/getEndorsementList.php',
                data: {
                    'req_id': req_id,
                    'cus_name': cus_name
                },
                type: 'post',
                cache: false,
                success: function(response) {

                    $('#endorsementDiv').empty()
                    $('#endorsementDiv').html(response);
                }
            }).then(function() {
                remove4columns('endorsementTable');
            })

            // To get the Gold List on Checklist
            $.ajax({
                url: 'nocFile/getGoldList.php',
                data: {
                    'req_id': req_id,
                    'cus_name': cus_name
                },
                type: 'post',
                cache: false,
                success: function(response) {

                    $('#goldDiv').empty()
                    $('#goldDiv').html(response);
                }
            }).then(function() {
                remove4columns('goldTable');
            })

            // To get the Document List on Checklist
            $.ajax({
                url: 'nocFile/getDocumentList.php',
                data: {
                    'req_id': req_id,
                    'cus_name': cus_name
                },
                type: 'post',
                cache: false,
                success: function(response) {

                    $('#documentDiv').empty()
                    $('#documentDiv').html(response);
                }
            }).then(function() {
                remove4columns('documentTable');
            })

            // setTimeout(() => {
            //     console.log('asdfasdfasdf')
            //     $('#signDocTable').DataTable().destroy();
            //     $('#chequeTable').DataTable().destroy();
            //     $('#mortgageTable').DataTable().destroy();
            //     $('#endorsementTable').DataTable().destroy();
            //     $('#goldTable').DataTable().destroy();
            //     $('#documentTable').DataTable().destroy();
            // }, 1500);
        })
    })

    function remove4columns(tablename) {
        $('input[type=checkbox]').attr('disabled', true)
    }


    $('#DocListTable').DataTable({
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        dom: 'lBfrtip',
        buttons: [{
                extend: 'excel',
                title: "Document History"
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
        'drawCallback': function() {
            searchFunction('DocListTable');
        }
    });
</script>

<?php
// Close the database connection
$connect = null;
?>