<?php
include '../ajaxconfig.php';
include '../moneyFormatIndia.php';
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
        </tr>
    </thead>
    <tbody>

        <?php
        $req_id = $_POST['req_id'];
        $cus_id = $_POST['cus_id'];
        $goldInfo = $connect->query("SELECT gold_sts, gold_type, Purity, gold_Count, gold_Weight, gold_Value, gold_upload FROM `gold_info` WHERE req_id = '$req_id' ORDER BY id DESC");

        $i = 1;
        while ($gold = $goldInfo->fetch()) {
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php if($gold["gold_sts"] == '0'){ echo 'Old';}else if($gold["gold_sts"] == '1'){echo 'New'; } ?></td>
                <td> <?php echo $gold["gold_type"]; ?></td>
                <td> <?php echo $gold["Purity"]; ?></td>
                <td><?php echo $gold["gold_Count"]; ?></td>
                <td><?php echo $gold["gold_Weight"]; ?></td>
                <td><?php echo moneyFormatIndia($gold["gold_Value"]); ?></td>
                <td> <a href="uploads/gold_info/<?php echo $gold['gold_upload']; ?>" target="_blank" style="color: #4ba39b;"> <?php echo $gold['gold_upload']; ?> </a></td>
            </tr>
        <?php  } ?>
    </tbody>
</table>