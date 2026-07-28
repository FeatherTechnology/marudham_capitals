<?php
include '../ajaxconfig.php';
include '../moneyFormatIndia.php';
?>
<table class="table custom-table" id='penaltyListTable'>
    <thead>
        <tr>
            <th width='20'> S.No </th>
            <th> Penalty Date </th>
            <th> Penalty </th>
            <th> Paid Date </th>
            <th> Paid Amount </th>
            <th> Balance Amount </th>
            <th> Waiver Amount </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $run = $connect->query("SELECT * FROM `penalty_charges` WHERE `req_id`= '$req_id' ORDER BY created_date ");

        $i = 1;
        $penalt = 0;
        $paid = 0;
        $waiver = 0;
        while ($row = $run->fetch()) {
            $penaltys = ($row['penalty']) ? $row['penalty'] : '0';
            $penalt += $penaltys; 
            $paid_amount = ($row['paid_amnt']) ? $row['paid_amnt'] : '0';
            $paid += $paid_amount;
            $waivers = ($row['waiver_amnt']) ? $row['waiver_amnt'] : '0';
            $waiver += $waivers;
            $bal_amnt = $penalt - $paid - $waiver;
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo ($row['penalty_date'] !='') ? date('d-m-Y',strtotime($row['penalty_date'])) : ''; ?></td>
                <td><?php echo $penaltys; ?></td>
                <td><?php echo ($row['paid_date'] !='') ? date('d-m-Y',strtotime($row['paid_date'])) : ''; ?></td>
                <td><?php echo $paid_amount; ?></td>
                <td><?php echo $bal_amnt; ?></td>
                <td><?php echo $waivers; ?></td>
            </tr>

        <?php } ?>

    </tbody>
    <tr>
        <td></td>
        <td></td>
        <td><b><?php echo moneyFormatIndia($penalt); ?></b></td>
        <td></td>
        <td><b><?php echo moneyFormatIndia($paid); ?></b></td>
        <td></td>
        <td><b><?php echo moneyFormatIndia($waiver); ?></b></td>
    </tr>
</table>

<script type="text/javascript">
    $(function() {
        $('#penaltyListTable').DataTable({
            'processing': true,
            'iDisplayLength': 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    action: function (e, dt, button, config) {
                        var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                        var dynamic = curDateJs('Penalty_chart'); // or any base
                        config.title = dynamic;      // for versions that use title as filename
                        config.filename = dynamic;   // for html5 filename
                        defaultAction.call(this, e, dt, button, config);
                    }
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ]
        });
    });
</script>