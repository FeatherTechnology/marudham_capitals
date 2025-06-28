<?php
include '../ajaxconfig.php';

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
            <th width="50"> S.No </th>
            <th> Gold Status </th>
            <th> Gold Type </th>
            <th> Purity </th>
            <th> Count </th>
            <th> Weight </th>
            <th> Value </th>
            <th> Upload </th>
            <!-- <th> Availability </th> -->
            <!-- <th> Action </th> -->
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $goldInfo = $connect->query("SELECT * FROM `gold_info` where req_id = '$req_id' order by id desc");

        $i = 1;
        while ($gold = $goldInfo->fetch()) {
            // $temp_sts = $gold['temp_sts'];
            $id = $gold['id'];
        ?>
            <tr>
                <td><?php echo $i;$i++; ?></td>
                <td><?php if($gold["gold_sts"] == '0'){ echo 'Old';}else if($gold["gold_sts"] == '1'){echo 'New'; } ?></td>
                <td> <?php echo $gold["gold_type"]; ?></td>
                <td> <?php echo $gold["Purity"]; ?></td>
                <td><?php echo $gold["gold_Count"]; ?></td>
                <td><?php echo $gold["gold_Weight"]; ?></td>
                <td><?php echo moneyFormatIndia($gold["gold_Value"]); ?></td>
                <td> <a href="uploads/gold_info/<?php echo $gold['gold_upload']; ?>" target="_blank" style="color: #4ba39b;"> <?php echo $gold['gold_upload']; ?> </a></td>
                <!-- <td><?php #echo $temp_sts == 0 ? 'YES':'NO'; ?></td> -->
                <!-- <td> -->
                    <?php #if($temp_sts == 0){//zero means document available,so show button for take out as temprory ?>
                        <!-- <button class="btn btn-danger temp-take-out" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='gold' data-toggle='modal' data-target='.temp-take-out-modal'>Take Out</button> -->
                    <?php #}else if($temp_sts == 1){//one means document not available, taken for temp purpose?>
                        <!-- <button class="btn btn-success temp-take-in" data-req_id='<?php echo $req_id; ?>' data-cus_id='<?php echo $cus_id; ?>' data-tableid = '<?php echo $id;?>' data-doc='gold' data-toggle='modal' data-target='.temp-take-in-modal'>Take In</button> -->
                    <?php #} ?>
                <!-- </td> -->
            </tr>

        <?php  } ?>
    </tbody>
</table>