<?php
session_start();
include '../ajaxconfig.php';

if (isset($_SESSION["userid"])) {
    $user_id = $_SESSION["userid"];
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
<table class="table custom-table" id='collectionChargeListTable'>
    <thead>
        <tr>
            <th width='5'> S.No </th>
            <th> Date </th>
            <th> Fine </th>
            <th> Purpose </th>
            <th> Paid Date </th>
            <th> Paid Amount</th>
            <th> Balance Amount</th>
            <th> Waiver Amount</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $run = $connect->query("SELECT * FROM `collection_charges` WHERE `req_id`= '$req_id' ");
        // $run = $connect->query("SELECT cc.coll_date,cc.coll_charge,cc.coll_purpose,c.paid_amt,c.bal_amt,c.penalty_waiver FROM `collection` c LEFT JOIN `collection_charges` cc ON c.req_id = cc.req_id WHERE c.`req_id`= '$req_id' GROUP BY c.coll_id ");

        $i = 1;
        $charge = 0;
        $paid = 0;
        $waiver = 0;
        $bal_amnt = 0;
        while ($row = $run->fetch()) {
            $collCharges = ($row['coll_charge']) ? $row['coll_charge'] : '0';
            $charge = $charge + $collCharges; 
            $paidAmount = ($row['paid_amnt']) ? $row['paid_amnt'] : '0';
            $paid = $paid + $paidAmount;
            $waiverAmount = ($row['waiver_amnt']) ? $row['waiver_amnt'] : '0';
            $waiver = $waiver + $waiverAmount;
            $bal_amnt = $charge - $paid - $waiver;
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php if(isset($row['coll_date'])) echo date('d-m-Y',strtotime($row['coll_date'])); ?></td>
                <td><?php echo $collCharges; ?></td>
                <td><?php echo $row['coll_purpose']; ?></td>
                <td><?php if(isset($row['paid_date'])) echo date('d-m-Y',strtotime($row['paid_date'])); ?></td>
                <td><?php echo $paidAmount; ?></td>
                <td><?php echo $bal_amnt; ?></td>
                <td><?php echo $waiverAmount; ?></td>
            </tr>

        <?php $i++;
        } 
        $sumchargesAmnt = $connect->query("SELECT sum(coll_charge) as charges,sum(paid_amnt) as paidAmnt,sum(waiver_amnt) as charges_waiver FROM `collection_charges` WHERE `req_id`= '$req_id' ");
        $sumAmnt = $sumchargesAmnt->fetch();
        $charges = $sumAmnt['charges'];
        $paid_amt = $sumAmnt['paidAmnt'];
        $charges_waiver = $sumAmnt['charges_waiver'];
        ?>
    </tbody>
    <tr>
        <td></td>
        <td></td>
        <td><b><?php echo $charges; ?></b></td>
        <td></td>
        <td></td>
        <td><b><?php echo $paid_amt; ?></b></td>
        <td><b><?php echo $bal_amnt; ?></b></td>
        <td><b><?php echo $charges_waiver; ?></b></td>
    </tr>
</table>

<script type="text/javascript">
    $(function() {
        $('#collectionChargeListTable').DataTable({
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
    });
</script>