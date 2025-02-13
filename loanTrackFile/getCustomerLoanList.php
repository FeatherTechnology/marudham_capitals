<?php
session_start();
$user_id = $_SESSION["userid"];
include('../ajaxconfig.php');


if (isset($_POST['cus_id'])) {
    $cus_id = $_POST['cus_id'];
}

$records = array();

$result = $connect->query("SELECT req.req_id,req.prompt_remark,req.cus_status,
    CASE WHEN req.cus_status >= 14 THEN ii.updated_date ELSE req.dor END AS `updated_date`,
    CASE WHEN req.cus_status >= 14 THEN ii.loan_id ELSE req.req_code END AS `code`,
    CASE WHEN req.cus_status IN (12,2,6,7) THEN vlc.loan_category WHEN req.cus_status IN (3,13,14,15,16,17,20,21) THEN alc.loan_category ELSE req.loan_category END AS loan_category,
    CASE WHEN req.cus_status IN (12,2,6,7) THEN vlc.sub_category WHEN req.cus_status IN (3,13,14,15,16,17,20,21) THEN alc.sub_category ELSE req.sub_category END AS sub_category,
    CASE WHEN req.cus_status IN (12,2,6,7) THEN vlc.loan_amt WHEN req.cus_status IN (3,13,14,15,16,17,20,21) THEN alc.loan_amt ELSE req.loan_amt END AS loan_amt,
    CASE WHEN req.cus_status IN (12,2,6,7,3,13,14,15,16,17,20,21) THEN cp.cus_name ELSE req.cus_name END AS cus_name
    FROM request_creation req
    LEFT JOIN customer_profile cp ON req.req_id = cp.req_id
    LEFT JOIN verification_loan_calculation vlc ON req.req_id = vlc.req_id
    LEFT JOIN acknowlegement_loan_calculation alc ON req.req_id = alc.req_id
    LEFT JOIN in_issue ii ON req.req_id = ii.req_id
    where req.cus_id = $cus_id and (req.cus_status <= 21) ORDER BY req.created_date DESC");

if ($result->rowCount() > 0) {
    $i = 0;
    while ($row = $result->fetch()) {

        $records[$i]['updated_date'] = date('d-m-Y', strtotime($row['updated_date']));
        $records[$i]['code'] = $row['code'];

        $req_id = $row['req_id'];
        $records[$i]['req_id'] = $row['req_id'];
        $cus_name = $row['cus_name'];

        $loan_category = $row['loan_category'] ?? '';
        $qry = $connect->query("SELECT * FROM loan_category_creation where loan_category_creation_id = $loan_category");
        $row1 = $qry->fetch();
        $records[$i]['loan_category'] = $row1['loan_category_creation_name'];

        $records[$i]['sub_category'] = $row['sub_category'];
        $records[$i]['loan_amt'] = $row['loan_amt'];
        $cus_status = $row['cus_status'];


        //for Charts
        $records[$i]['chart_action'] = "<div class='dropdown'><button class='btn btn-outline-secondary'><i class='fa'>&#xf107;</i></button><div class='dropdown-content'> ";

        if ($cus_status >= 14) {
            $records[$i]['chart_action'] .= "<a><span data-toggle='modal' data-target='.DueChart' class='due-chart' value='" . $req_id . "' data-cusid='" . $cus_id . "'> Due Chart</span></a>
                <a><span data-toggle='modal' data-target='.PenaltyChart' class='penalty-chart' value='" . $req_id . "' data-cusid='" . $cus_id . "'> Penalty Chart</span></a>
                <a><span data-toggle='modal' data-target='.collectionChargeChart' class='coll-charge-chart' value='" . $req_id . "' data-cusid='" . $cus_id . "'> Fine Chart</span></a>
                <a><span data-toggle='modal' data-target='#commitmentChart' class='commitment-chart' data-reqid='" . $req_id . "' data-cusid='" . $cus_id . "'> Commitment Chart </span></a>";
        }
        $records[$i]['chart_action'] .= "</div></div>";

        $i++;
    }
}

?>
<table class="table table-bordered" id="custLoanListTable">
    <thead>
        <th width="10%">S.No</th>
        <th>Date</th>
        <th>Req ID/Loan ID</th>
        <th>Loan Category</th>
        <th>Sub Category</th>
        <th>Loan Amount</th>
        <th>Chart</th>
        <th>Track Loan</th>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < sizeof($records); $i++) { ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo $records[$i]['updated_date']; ?></td>
                <td><?php echo $records[$i]['code']; ?></td>
                <td><?php echo $records[$i]['loan_category']; ?></td>
                <td><?php echo $records[$i]['sub_category']; ?></td>
                <td><?php echo $records[$i]['loan_amt']; ?></td>
                <td><?php echo $records[$i]['chart_action']; ?></td>
                <td><button class="btn btn-primary track-btn" data-req_id='<?php echo $records[$i]['req_id']; ?>' onclick="event.preventDefault()">Track</button></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<input type="hidden" name="docSts" id="docSts">
<div id="printcollection" style="display: none"></div>



<style>
    .dropdown-content {
        color: black;
    }

    .img-show {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
        background-color: white;
    }
</style>
<script>
    //datatable initialization and other link click
    var table = $('#custLoanListTable').DataTable();
    table.destroy();
    $('#custLoanListTable').DataTable({
        'processing': true,
        'iDisplayLength': 5,
        "lengthMenu": [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
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
    });
    customerStatusOnClickEvents();
</script>

<?php
// Close the database connection
$connect = null;
?>