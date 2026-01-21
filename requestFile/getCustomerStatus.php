<?php
include('../ajaxconfig.php');
include '../moneyFormatIndia.php';

if (isset($_POST['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
}

$screen = isset($_POST['screen']) ? $_POST['screen'] : '';

$records = array();

$result = $connect->query("SELECT req_id, dor, loan_category, sub_category, loan_amt, prompt_remark, cus_status FROM request_creation where cus_id = '" . strip_tags($cus_id) . "' ORDER BY created_date DESC ");

if ($result->rowCount() > 0) {
    $i = 0;
    while ($row = $result->fetch()) {

        $records[$i]['dor'] = date('d-m-Y', strtotime($row['dor']));

        $loan_category = $row['loan_category'];
        $req_id = $row['req_id'];
        $qry = $connect->query("SELECT * FROM loan_category_creation where loan_category_creation_id = $loan_category");
        $row1 = $qry->fetch();
        $records[$i]['loan_category'] = $row1['loan_category_creation_name'];

        $records[$i]['sub_category'] = $row['sub_category'];
        $records[$i]['loan_amt'] = moneyFormatIndia($row['loan_amt']);
        $records[$i]['remark'] = $row['prompt_remark'] ?? '';
        $cus_status = $row['cus_status'];
        // if($cus_status != '10' and $cus_status != '11'){
        if ($cus_status == '0') {
            $records[$i]['status'] = 'Request';
            $records[$i]['sub_status'] = 'Requested';
        } else
            if ($cus_status == '1' or $cus_status == '10' or $cus_status == '11' or $cus_status == '12') {
            $records[$i]['status'] = 'Verification';
            $records[$i]['sub_status'] = 'In Verification';
        } else
            if ($cus_status == '2') {
            $records[$i]['status'] = 'Approval';
            $records[$i]['sub_status'] = 'In Approval';
        } else
            if ($cus_status == '3') {
            $records[$i]['status'] = 'Acknowledgement';
            $records[$i]['sub_status'] = 'In Acknowledgement';
        } else
            if ($cus_status == '4') {
            $records[$i]['status'] = 'Request';
            $records[$i]['sub_status'] = 'Cancelled';
        } else
            if ($cus_status == '5') {
            $records[$i]['status'] = 'Verification';
            $records[$i]['sub_status'] = 'Cancelled';
        } else
            if ($cus_status == '6') {
            $records[$i]['status'] = 'Approval';
            $records[$i]['sub_status'] = 'Cancelled';
        } else
            if ($cus_status == '7') {
            $records[$i]['status'] = 'Issue';
            $records[$i]['sub_status'] = 'Issued';
        } else
            if ($cus_status == '8') {
            $records[$i]['status'] = 'Request';
            $records[$i]['sub_status'] = 'Revoked';
        }
        if ($cus_status == '9') {
            $records[$i]['status'] = 'Verification';
            $records[$i]['sub_status'] = 'Revoked';
        }
        if ($cus_status == '13') {
            $records[$i]['status'] = 'Loan Issue';
            $records[$i]['sub_status'] = 'In Issue';
        }
        if ($cus_status >= '14' and $cus_status <= '17') {
            $records[$i]['status'] = 'Present';
            $records[$i]['sub_status'] = getCollectionStatus($connect, $cus_id, $req_id);
        }
        if ($cus_status == '20') {
            $records[$i]['status'] = 'Closed';
            $records[$i]['sub_status'] = 'In Closed';
        }
        if ($cus_status >= '21') { //21 means in NOC
            // if moved from Closed, then sub status will be consider level of closed window
            $records[$i]['status'] = 'Closed';

            $Qry = $connect->query("SELECT closed_sts,consider_level from closed_status where cus_id = $cus_id and req_id = '" . $req_id . "' ");
            $closed_status = ['', 'Consider', 'Waiting List', 'Block List']; // first one is empty because select value of consider sts is starting at 1
            $consider_level = ['', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond']; // first one is empty because select value of consider sts is starting at 1
            $runqry = $Qry->fetch();
            $substatuslocal = $closed_status[$runqry['closed_sts']];
            if ($runqry['closed_sts'] == '1') {
                $substatuslocal .= ' - ' . $consider_level[$runqry['consider_level']];
            }
            $records[$i]['sub_status'] = $substatuslocal;
        }
        if ($screen == 'acknowledgement') { //21 means in NOC
            $Qry = $connect->query("SELECT doc_sts, doc_remarks, update_remarks 
                            FROM acknowlegement_documentation 
                            WHERE cus_id_doc = $cus_id AND req_id = '" . $req_id . "' ");

            $runqry1 = $Qry->fetch();

            $records[$i]['doc_status'] = '';

            if ($cus_status >= 14 && $cus_status < 21) {
                if ($runqry1 && $runqry1['doc_sts'] == 'NO') {
                    $records[$i]['doc_status'] = 'Document Pending';
                } else {
                    $records[$i]['doc_status'] = 'Document Completed';
                }
            } else if ($cus_status >= 21 && $cus_status <= 23) {
                if ($cus_status == 21) {
                    $records[$i]['doc_status'] = 'NOC Pending';
                } else {
                    $records[$i]['doc_status'] = 'NOC Completed';
                }
            } else if ($cus_status >= 24) {
                $records[$i]['doc_status'] = 'NOC Handovered';
            }

            // Only assign remarks if fetch returned a row
            $records[$i]['doc_remarks'] = $runqry1['doc_remarks'] ?? '';
            $records[$i]['update_remarks'] = $runqry1['update_remarks'] ?? '';
        }


        $i++;
    }
}
?>

    <?php if ($screen == 'acknowledgement') { ?>
        <thead>
            <tr>
                <th width="25" rowspan="2">S. No</th>
                <th rowspan="2">Date</th>
                <th rowspan="2">Loan Category</th>
                <th rowspan="2">Sub Category</th>
                <th rowspan="2">Amount</th>
                <th colspan="3">Loan Status</th>
                <th colspan="3">Document Status</th>
            </tr>
            <tr>
                <th>Status</th>
                <th>Sub Status</th>
                <th>Remark</th>
                <th>Status</th>
                <th>Acknowledgement Remark</th>
                <th>Update Remark</th>
            </tr>
        </thead>
    <?php } else { ?>
        <thead>
            <tr>
                <th width="25">S. No</th>
                <th>Date</th>
                <th>Loan Category</th>
                <th>Sub Category</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Sub Status</th>
                <th>Remark</th>
            </tr>
        </thead>
    <?php } ?>

    <tbody>
        <?php for ($i = 0; $i < sizeof($records); $i++) { ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo $records[$i]['dor']; ?></td>
                <td><?php echo $records[$i]['loan_category']; ?></td>
                <td><?php echo $records[$i]['sub_category']; ?></td>
                <td><?php echo $records[$i]['loan_amt']; ?></td>
                <?php if ($screen == 'acknowledgement') { ?>
                    <td><?= $records[$i]['status'] ?></td>
                    <td><?= $records[$i]['sub_status'] ?></td>
                    <td><?= $records[$i]['remark'] ?></td>
                    <td><?= $records[$i]['doc_status'] ?></td>
                    <td><?= $records[$i]['doc_remarks'] ?></td>
                    <td><?= $records[$i]['update_remarks'] ?></td>
                <?php } else { ?>
                    <td><?= $records[$i]['status'] ?></td>
                    <td><?= $records[$i]['sub_status'] ?></td>
                    <td><?= $records[$i]['remark'] ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>

<script>
    var table = $('#cusHistoryTable').DataTable();
    table.destroy();
    // Declare table variable to store the DataTable instance
    var cusHistoryTable = $('#cusHistoryTable').DataTable({
        'processing': true,
        'iDisplayLength': 10,
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
        },
        dom: 'lBfrtip',
       buttons: [{
        extend: 'excel',
        action: function(e, dt, button, config) {
            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
            var dynamic = curDateJs('Customer_status_List');
            // or any base config.title = dynamic; // for versions that use title as filename
            config.filename = dynamic; // for html5 filename
            defaultAction.call(this, e, dt, button, config);
        }
        }, {
            extend: 'colvis',
            collectionLayout: 'fixed four-column',
        }],
    });

</script>
<?php
function getCollectionStatus($connect, $cus_id, $req_id)
{

    $pending_sts = isset($_POST["pending_sts"]) && $_POST["pending_sts"] !== '' ? explode(',', $_POST["pending_sts"]) : [];
    $od_sts = isset($_POST["od_sts"]) && $_POST["od_sts"] !== '' ? explode(',', $_POST["od_sts"]) : [];
    $due_nil_sts = isset($_POST["due_nil_sts"]) && $_POST["due_nil_sts"] !== '' ? explode(',', $_POST["due_nil_sts"]) : [];
    $bal_amt = isset($_POST["bal_amt"]) && $_POST["bal_amt"] !== '' ? explode(',', $_POST["bal_amt"]) : [];
    $closed_sts = isset($_POST["closed_sts"]) && $_POST["closed_sts"] !== '' ? explode(',', $_POST["closed_sts"]) : [];
    $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];

    $boolMapper = function ($value) {
        return in_array(strtolower(trim((string)$value)), ['1', 'true', 'yes'], true);
    };

    $pending_sts = array_map($boolMapper, $pending_sts);
    $od_sts = array_map($boolMapper, $od_sts);
    $due_nil_sts = array_map($boolMapper, $due_nil_sts);
    $closed_sts = array_map($boolMapper, $closed_sts);
    $bal_amt = array_map(function ($value) {
        $value = trim($value);
        return $value === '' ? 0 : (float)$value;
    }, $bal_amt);

    $retVal = 'Current';

    $run = $connect->query("SELECT lc.due_start_from,lc.loan_category,lc.sub_category,lc.loan_amt_cal,lc.due_amt_cal,lc.net_cash_cal,lc.collection_method,ii.loan_id,ii.req_id,ii.updated_date,ii.cus_status,
    rc.agent_id,lcc.loan_category_creation_name as loan_catrgory_name
    from acknowlegement_loan_calculation lc JOIN in_issue ii ON lc.req_id = ii.req_id JOIN request_creation rc ON ii.req_id = rc.req_id 
    JOIN loan_category_creation lcc ON lc.loan_category = lcc.loan_category_creation_id
    WHERE lc.cus_id_loan = $cus_id and (ii.cus_status >= 14 and ii.cus_status < 20) ORDER BY CAST(ii.req_id AS UNSIGNED) ASC"); //Customer status greater than or equal to 14 because, after issued data only we need

    $curdate = date('Y-m-d');
    $index = 0;

    while ($row = $run->fetch()) {
        $currentBal = $bal_amt[$index] ?? 0;

        if ($row['req_id'] != $req_id) {
            $index++;
            continue;
        }

        $isPending = $pending_sts[$index] ?? false;
        $isOd = $od_sts[$index] ?? false;
        $isDueNil = $due_nil_sts[$index] ?? false;
        $isClosed = $closed_sts[$index] ?? false;

        if (date('Y-m-d', strtotime($row['due_start_from'])) > $curdate && $currentBal != 0) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
            if ($row['cus_status'] == '15') {
                $retVal = 'Error';
            } elseif ($row['cus_status'] == '16') {
                $retVal = 'Legal';
            } else {
                $retVal = 'Current';
            }
        } else {
            if ($row['cus_status'] <= 20) {
                if ($isPending && !$isOd) {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = 'Pending';
                    }
                } else if ($isOd && !$isDueNil) {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = 'OD';
                    }
                } elseif ($isDueNil) {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = 'Due Nil';
                    }
                } elseif (!$isPending) {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = $isClosed ? 'Closed' : 'Current';
                    }
                }
            } else if ($row['cus_status'] > 20) { // if status is closed(21) or more than that(22), then show closed status
                $closedSts = $connect->query("SELECT * FROM `closed_status` WHERE `req_id` ='" . strip_tags($req_id) . "' ");
                $closedStsrow = $closedSts->fetch();
                $rclosed = $closedStsrow['closed_sts'];
                $consider_lvl = $closedStsrow['consider_level'];
                if ($rclosed == '1') {
                    $retVal = 'Consider - ' . $consider_lvl_arr[$consider_lvl];
                }
                if ($rclosed == '2') {
                    $retVal = 'Waiting List';
                }
                if ($rclosed == '3') {
                    $retVal = 'Block List';
                }
            }
        }

        break;
    }

    return $retVal;
}

// Close the database connection
$connect = null;
?>