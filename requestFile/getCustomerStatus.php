<?php
include('../ajaxconfig.php');

if (isset($_POST['cus_id'])) {
    $cus_id = preg_replace('/\D/', '', $_POST['cus_id']);
}

$records = array();

$result = $connect->query("SELECT req_id, dor, loan_category, sub_category, loan_amt, prompt_remark, cus_status FROM request_creation where cus_id = '" . strip_tags($cus_id) . "' and cus_status <= 22 ORDER BY created_date DESC ");

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
        $records[$i]['loan_amt'] = $row['loan_amt'];
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
        // }

        $i++;
    }
}

?>

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
<tbody>
    <?php for ($i = 0; $i < sizeof($records); $i++) { ?>
        <tr>
            <td><?php echo $i + 1; ?></td>
            <td><?php echo $records[$i]['dor']; ?></td>
            <td><?php echo $records[$i]['loan_category']; ?></td>
            <td><?php echo $records[$i]['sub_category']; ?></td>
            <td><?php echo $records[$i]['loan_amt']; ?></td>
            <td><?php echo $records[$i]['status']; ?></td>
            <td><?php echo $records[$i]['sub_status']; ?></td>
            <td><?php echo $records[$i]['remark']; ?></td>
        </tr>
    <?php } ?>
</tbody>
<script>
    var table = $('#cusHistoryTable').DataTable();
    table.destroy();
    $('#cusHistoryTable').DataTable({
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
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
        ],
    });
</script>
<?php
function getCollectionStatus($connect, $cus_id, $req_id)
{

    $pending_sts = isset($_POST["pending_sts"]) ? explode(',', $_POST["pending_sts"]) : null;
    $od_sts = isset($_POST["od_sts"]) ? explode(',', $_POST["od_sts"]) : null;
    $due_nil_sts = isset($_POST["due_nil_sts"]) ? explode(',', $_POST["due_nil_sts"]) : null;
    $closed_sts = isset($_POST["closed_sts"]) ? explode(',', $_POST["closed_sts"]) : null;
    $bal_amt = isset($_POST["bal_amt"]) ? explode(',', $_POST["bal_amt"]) : null;
    $consider_lvl_arr = [1 => 'Bronze', 2 => 'Silver', 3 => 'Gold', 4 => 'Platinum', 5 => 'Diamond'];

    $retVal = '';

    $run = $connect->query("SELECT lc.due_start_from, ii.cus_status
        FROM acknowlegement_loan_calculation lc 
        JOIN in_issue ii ON lc.req_id = ii.req_id 
        WHERE lc.cus_id_loan = $cus_id and (ii.cus_status >= 14 and ii.cus_status < 20)"); //Customer status greater than or equal to 14 because, after issued data only we need

    $curdate = date('Y-m-d');
    while ($row = $run->fetch()) {
        $i = 1;
        if (date('Y-m-d', strtotime($row['due_start_from'])) > date('Y-m-d', strtotime($curdate))  and $bal_amt[$i - 1] != 0) { //If the start date is on upcoming date then the sub status is current, until current date reach due_start_from date.
            if ($row['cus_status'] == '15') {
                $retVal = 'Error';
            } elseif ($row['cus_status'] == '16') {
                $retVal = 'Legal';
            } else {
                $retVal = 'Current';
            }
        } else {
            if ($row['cus_status'] <= 20) {
                if ($pending_sts[$i - 1] == 'true' && $od_sts[$i - 1] == 'false') {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = 'Pending';
                    }
                } else if ($od_sts[$i - 1] == 'true' && $due_nil_sts[$i - 1] == 'false') {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = 'OD';
                    }
                } elseif ($due_nil_sts[$i - 1] == 'true') {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        $retVal = 'Due Nil';
                    }
                } elseif ($pending_sts[$i - 1] == 'false') {
                    if ($row['cus_status'] == '15') {
                        $retVal = 'Error';
                    } elseif ($row['cus_status'] == '16') {
                        $retVal = 'Legal';
                    } else {
                        if ($closed_sts[$i - 1] == 'true') {
                            $retVal = "Move To Close";
                        } else {
                            $retVal = 'Current';
                        }
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
        $i++;
    }
    return $retVal;
}

// Close the database connection
$connect = null;
?>