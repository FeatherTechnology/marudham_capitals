<?php
include '../../ajaxconfig.php';
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

<table class="table custom-table" id="gold_table">
    <thead>
        <tr>
            <th width="15%"> S.No </th>
            <th> Gold Status </th>
            <th> Gold Type </th>
            <th> Purity </th>
            <th> Count </th>
            <th> Weight </th>
            <th> Value </th>
            <th> Upload </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['reqId'];
        $goldInfo = $connect->query("SELECT * FROM `gold_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        $cnt = 0;
        $weight = 0;
        $goldVal = 0;
        while ($gold = $goldInfo->fetch()) {
            $cnt = $cnt + intval($gold['gold_Count']);
            $weight = $weight + intval($gold["gold_Weight"]);
            $goldVal = $goldVal + intval($gold["gold_Value"]);

        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php if ($gold["gold_sts"] == '0') {
                        echo 'Old';
                    } else if ($gold["gold_sts"] == '1') {
                        echo 'New';
                    } ?></td>
                <td> <?php echo $gold["gold_type"]; ?></td>
                <td> <?php echo $gold["Purity"]; ?></td>
                <td><?php echo $gold["gold_Count"]; ?></td>
                <td><?php echo $gold["gold_Weight"]; ?></td>
                <td><?php echo moneyFormatIndia($gold["gold_Value"]); ?></td>
                <td> <a href="uploads/gold_info/<?php echo $gold['gold_upload']; ?>" target="_blank" style="color: #4ba39b;"> <?php echo $gold['gold_upload']; ?> </a></td>

            </tr>

        <?php $i++;
        } ?>
    </tbody>
    <tr>
        <td> <b> Total </b> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> <b> <?php echo $cnt; ?> </b> </td>
        <td> <b> <?php echo $weight; ?> </b> </td>
        <td> <b> <?php echo moneyFormatIndia($goldVal); ?> </b> </td>
    </tr>
</table>

<script type="text/javascript">
    $(function() {
        $('#gold_table').DataTable({
            // "order": [[0,'desc']],
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    title: "Loan History"
                },
                {
                    extend: 'colvis',
                    collectionLayout: 'fixed four-column',
                }
            ],
            "drawCallback": function(settings) {
                searchFunction('gold_table');
            }
        });
    });
</script>
<?php
// Close the database connection
$connect = null;
?>